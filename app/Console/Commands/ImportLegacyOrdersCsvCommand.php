<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Migration\CsvReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports the legacy jointerritorialarmy.com orders export.
 *
 * Expected columns (exactly what the legacy admin produces):
 *   S.No., "Order number", Fullname, Mobile, "Alternate mobile", Email,
 *   Address, City, State, Pincode, Book, Quantity, Price, Discount,
 *   Shipping, "Final cost", "Consignment number", Date
 *
 * Mapping:
 *   Order number           -> orders.order_ref  (also legacy_id)
 *   Fullname               -> orders.buyer_name
 *   Mobile                 -> orders.buyer_mobile
 *   Email                  -> orders.buyer_email (lowercased, trimmed)
 *   Address/City/State/Pincode/Alternate mobile -> orders.shipping_json
 *   Date                   -> orders.placed_at
 *   Final cost (INR)       -> orders.total_paise  (multiplied by 100)
 *   Price (INR)            -> order_items.unit_price_paise
 *   Discount (INR)         -> orders.discount_paise
 *   Shipping (INR)         -> orders.shipping_paise
 *   Quantity               -> order_items.quantity + line_total_paise
 *   Book                   -> matches BookEdition by Book.title ILIKE %input%
 *                             falls back to the default edition.
 *   Consignment number     -> shipping_json.consignment (for dispatch lookup)
 *
 * Payment status defaults to 'paid' because the legacy admin only
 * exports fulfilled orders. Pass --status=pending|failed to override.
 */
class ImportLegacyOrdersCsvCommand extends Command
{
    protected $signature = 'import:legacy-orders
        {--source=data-import/orders.csv : Path to the exported CSV}
        {--status=paid : Default payment_status for every imported row}
        {--dry-run : Preview without writing to the DB}
        {--book-slug=tac-examination-book : Fallback book slug when the Book column cannot be matched}';

    protected $description = 'Import the legacy jointerritorialarmy.com orders CSV with the real column names.';

    public function handle(): int
    {
        $path = base_path($this->option('source'));
        if (!is_file($path)) {
            $this->error("Not found: $path");
            return self::FAILURE;
        }

        $defaultStatus = $this->option('status');
        $dry = (bool) $this->option('dry-run');
        $fallbackBook = Book::query()->where('slug', $this->option('book-slug'))->with('editions')->first();
        $fallbackEdition = $fallbackBook?->defaultEdition();

        if (!$fallbackEdition) {
            $this->warn("No fallback edition for slug {$this->option('book-slug')}. Items without a matching Book will be skipped.");
        }

        $created = 0; $updated = 0; $skipped = 0; $dateUnknown = 0;
        $perStatus = [];
        $exceptions = [];
        $paidTotal = 0;

        DB::beginTransaction();
        try {
            foreach (CsvReader::rows($path) as $row) {
                $ref = trim((string) ($row['Order number'] ?? ''));
                if ($ref === '') { $skipped++; continue; }

                $qty = max(1, (int) ($row['Quantity'] ?? 1));
                $unitPaise     = self::rupeesToPaise($row['Price'] ?? '0');
                $discountPaise = self::rupeesToPaise($row['Discount'] ?? '0');
                $shippingPaise = self::rupeesToPaise($row['Shipping'] ?? '0');
                $totalPaise    = self::rupeesToPaise($row['Final cost'] ?? '0');
                if ($totalPaise === 0) {
                    // Legacy export occasionally omitted final cost; compute from parts.
                    $totalPaise = max(0, $unitPaise * $qty - $discountPaise + $shippingPaise);
                }

                $placed = CsvReader::parseDate($row['Date'] ?? '');
                if ($placed['unknown']) $dateUnknown++;

                $bookEdition = self::resolveEdition($row['Book'] ?? '', $fallbackEdition);

                $shipping = array_filter([
                    'line1'   => trim((string) ($row['Address'] ?? '')),
                    'city'    => trim((string) ($row['City'] ?? '')),
                    'state'   => trim((string) ($row['State'] ?? '')),
                    'pincode' => trim((string) ($row['Pincode'] ?? '')),
                    'alternate_mobile' => trim((string) ($row['Alternate mobile'] ?? '')) ?: null,
                    'consignment'      => trim((string) ($row['Consignment number'] ?? '')) ?: null,
                ], fn ($v) => $v !== '' && $v !== null);

                $payload = [
                    'buyer_name'    => trim((string) ($row['Fullname'] ?? '')),
                    'buyer_email'   => strtolower(trim((string) ($row['Email'] ?? ''))),
                    'buyer_mobile'  => trim((string) ($row['Mobile'] ?? '')),
                    'shipping_json' => $shipping,
                    'payment_status'  => $defaultStatus,
                    'dispatch_status' => !empty($shipping['consignment']) ? Order::DISPATCH_SHIPPED : Order::DISPATCH_UNSHIPPED,
                    'subtotal_paise' => $unitPaise * $qty,
                    'discount_paise' => $discountPaise,
                    'shipping_paise' => $shippingPaise,
                    'total_paise'    => $totalPaise,
                    'currency'       => 'INR',
                    'placed_at'      => $placed['date'],
                    'paid_at'        => $defaultStatus === Order::PAY_PAID ? $placed['date'] : null,
                    'import_date_unknown' => $placed['unknown'],
                ];
                $perStatus[$defaultStatus] = ($perStatus[$defaultStatus] ?? 0) + 1;
                if ($defaultStatus === Order::PAY_PAID) $paidTotal += $totalPaise;

                $existing = Order::query()->where('order_ref', $ref)->first();
                if ($existing) {
                    if (!$dry) $existing->update($payload);
                    $order = $existing;
                    $updated++;
                } else {
                    if (!$dry) {
                        $order = Order::create([
                            'legacy_id' => $ref,
                            'order_ref' => $ref,
                        ] + $payload);
                    }
                    $created++;
                }

                if (!$dry && isset($order) && $bookEdition) {
                    OrderItem::updateOrCreate(
                        ['order_id' => $order->id, 'book_edition_id' => $bookEdition->id],
                        [
                            'item_description_snapshot' => ($bookEdition->book?->title ?? 'TAC Book').' — Edition '.$bookEdition->edition_name,
                            'unit_price_paise' => $unitPaise ?: $bookEdition->price_paise,
                            'quantity' => $qty,
                            'line_total_paise' => $unitPaise * $qty,
                        ],
                    );
                }
            }

            $dry ? DB::rollBack() : DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $exceptions[] = $e->getMessage();
            $this->error('Import failed: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->line(($dry ? '[DRY RUN] ' : '').'Legacy orders import summary');
        $this->line('  Created:       '.$created);
        $this->line('  Updated:       '.$updated);
        $this->line('  Skipped:       '.$skipped);
        $this->line('  Unknown dates: '.$dateUnknown);
        $this->line('  Per-status:    '.json_encode($perStatus));
        $this->line('  Paid total:    ₹'.number_format(intdiv($paidTotal, 100)).' ('.$paidTotal.' paise)');
        foreach ($exceptions as $ex) $this->warn('  - '.$ex);

        return self::SUCCESS;
    }

    /** Parse a rupee string (may include commas, decimals, Rs symbol) into integer paise. */
    private static function rupeesToPaise(string|int|float|null $v): int
    {
        if ($v === null) return 0;
        $s = (string) $v;
        $s = preg_replace('/[^\d.-]/', '', $s);
        if ($s === '' || $s === '-' || $s === '.') return 0;
        $rupees = (float) $s;
        return (int) round($rupees * 100);
    }

    private static function resolveEdition(string $hint, ?BookEdition $fallback): ?BookEdition
    {
        $hint = trim($hint);
        if ($hint === '') return $fallback;
        $match = Book::query()
            ->where('title', 'like', '%'.$hint.'%')
            ->orWhere('slug', 'like', '%'.\Illuminate\Support\Str::slug($hint).'%')
            ->first();
        return $match?->defaultEdition() ?? $fallback;
    }
}
