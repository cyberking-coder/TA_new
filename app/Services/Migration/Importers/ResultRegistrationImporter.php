<?php

namespace App\Services\Migration\Importers;

use App\Models\Exam;
use App\Models\ResultRegistration;
use App\Services\Migration\CsvReader;
use App\Services\Migration\ImportSummary;
use Illuminate\Support\Facades\DB;

class ResultRegistrationImporter
{
    public function run(string $csvPath, bool $dryRun): ImportSummary
    {
        $summary = new ImportSummary('result_registrations');
        $examCache = Exam::query()->pluck('id', 'slug')->all();

        DB::beginTransaction();
        try {
            foreach (CsvReader::rows($csvPath) as $row) {
                $examId = $examCache[$row['exam_slug'] ?? ''] ?? null;
                if (!$examId) { $summary->skippedCount++; $summary->exceptions[] = "unknown exam: {$row['exam_slug']}"; continue; }

                $existing = ResultRegistration::where('exam_id', $examId)
                    ->where('roll_number', $row['roll_number'])->first();
                $payload = [
                    'name'   => $row['name'],
                    'mobile' => $row['mobile'],
                    'email'  => strtolower(trim($row['email'])),
                    'legacy_zone' => $row['legacy_zone'] ?: null,
                ];
                if ($existing) {
                    if (!$dryRun) $existing->update($payload);
                    $summary->updatedCount++;
                } else {
                    if (!$dryRun) ResultRegistration::create(
                        ['legacy_id' => $row['legacy_id'] ?? null, 'exam_id' => $examId, 'roll_number' => $row['roll_number']] + $payload
                    );
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
}
