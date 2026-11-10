<?php

namespace App\Services\Migration\Importers;

use App\Models\Order;
use App\Services\Migration\CsvReader;
use App\Services\Migration\ImportSummary;
use Illuminate\Support\Facades\DB;

/**
 * Preserves prefixed legacy order_ref strings exactly as stored.
 * Totals are reproduced in integer paise. Unknown dates become NULL
 * with import_date_unknown = true, so reporting can distinguish
 * "never paid" from "paid at unknown time".
 */
class OrderImporter
{
    public function run(string $csvPath, bool $dryRun): ImportSummary
    {
        $summary = new ImportSummary('orders');

        DB::beginTransaction();
        try {
            foreach (CsvReader::rows($csvPath) as $row) {
                $ref = trim($row['order_ref'] ?? '');
                if ($ref === '') { $summary->skippedCount++; continue; }

                $placed = CsvReader::parseDate($row['placed_at'] ?? '');
                $paid = CsvReader::parseDate($row['paid_at'] ?? '');
                if ($placed['unknown']) $summary->dateUnknownCount++;

                $payload = [
                    'buyer_name'   => $row['buyer_name'],
                    'buyer_email'  => strtolower(trim($row['buyer_email'])),
                    'buyer_mobile' => $row['buyer_mobile'],
                    'shipping_json' => [], // legacy site did not carry shipping blob on this fixture
                    'payment_status' => $row['payment_status'] ?: Order::PAY_PENDING,
                    'dispatch_status' => Order::DISPATCH_UNSHIPPED,
                    'subtotal_paise' => (int) ($row['total_paise'] ?? 0),
                    'discount_paise' => 0,
                    'shipping_paise' => 0,
                    'total_paise' => (int) ($row['total_paise'] ?? 0),
                    'currency' => $row['currency'] ?: 'INR',
                    'placed_at' => $placed['date'],
                    'paid_at'   => $paid['date'],
                    'import_date_unknown' => $placed['unknown'],
                ];
                $summary->statusHit($payload['payment_status']);

                $existing = Order::where('order_ref', $ref)->first();
                if ($existing) {
                    if (!$dryRun) $existing->update($payload);
                    $summary->updatedCount++;
                } else {
                    if (!$dryRun) Order::create(['legacy_id' => $row['legacy_id'] ?? null, 'order_ref' => $ref] + $payload);
                    $summary->createdCount++;
                }
            }
            $dryRun ? DB::rollBack() : DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $summary->exceptions[] = $e->getMessage();
            throw $e;
        }
        return $summary;
    }

    /** Sum paid_paise across the DB — used by the rehearsal comparator. */
    public static function paidTotalPaise(): int
    {
        return (int) Order::query()->where('payment_status', Order::PAY_PAID)->sum('total_paise');
    }
}
