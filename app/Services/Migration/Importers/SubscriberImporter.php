<?php

namespace App\Services\Migration\Importers;

use App\Models\OfficerSubscriber;
use App\Models\SoldierSubscriber;
use App\Models\Suppression;
use App\Services\Migration\CsvReader;
use App\Services\Migration\ImportSummary;
use Illuminate\Support\Facades\DB;

/**
 * Preserves opt-outs: a legacy row with unsubscribed_at populated
 * writes BOTH the subscriber row AND a Suppression record. A repeat
 * subscribe through the public form would be refused per Phase 3.
 */
class SubscriberImporter
{
    public function run(string $audience, string $csvPath, bool $dryRun): ImportSummary
    {
        $summary = new ImportSummary($audience.'_subscribers');
        $model = $audience === 'officer' ? OfficerSubscriber::class : SoldierSubscriber::class;

        DB::beginTransaction();
        try {
            foreach (CsvReader::rows($csvPath) as $row) {
                $email = strtolower(trim($row['email'] ?? ''));
                if ($email === '') { $summary->skippedCount++; continue; }

                $verifiedAt = CsvReader::parseDate($row['verified_at'] ?? '')['date'];
                $unsub      = CsvReader::parseDate($row['unsubscribed_at'] ?? '')['date'];

                $payload = [
                    'name' => $row['name'] ?: null,
                    'mobile' => $row['mobile'] ?: null,
                    'verified_at' => $verifiedAt,
                    'consent_at'  => $verifiedAt,
                    'unsubscribed_at' => $unsub,
                ];

                $existing = $model::where('email', $email)->first();
                if ($existing) {
                    if (!$dryRun) $existing->update($payload);
                    $summary->updatedCount++;
                } else {
                    if (!$dryRun) $model::create(['legacy_id' => $row['legacy_id'] ?? null, 'email' => $email] + $payload);
                    $summary->createdCount++;
                }

                if ($unsub && !$dryRun) {
                    Suppression::updateOrCreate(
                        ['audience' => $audience, 'email' => $email],
                        ['reason' => 'unsubscribed_at_legacy', 'created_at' => $unsub],
                    );
                    $summary->statusHit('suppressed_imported');
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
}
