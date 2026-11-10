<?php

namespace App\Services\Migration\Importers;

use App\Models\Exam;
use App\Models\Notice;
use App\Services\Migration\CsvReader;
use App\Services\Migration\ImportSummary;
use Illuminate\Support\Facades\DB;

class NoticeImporter
{
    public function run(string $csvPath, bool $dryRun): ImportSummary
    {
        $summary = new ImportSummary('notices');
        $examCache = Exam::query()->pluck('id', 'slug')->all();

        DB::beginTransaction();
        try {
            foreach (CsvReader::rows($csvPath) as $row) {
                $legacy = trim($row['legacy_id'] ?? '');
                if ($legacy === '') { $summary->skippedCount++; $summary->exceptions[] = "notice row missing legacy_id"; continue; }

                $examId = $examCache[$row['exam_slug'] ?? ''] ?? null;
                $date = CsvReader::parseDate($row['published_on'] ?? '');
                if ($date['unknown']) $summary->dateUnknownCount++;

                $existing = Notice::where('legacy_id', $legacy)->first();
                $payload = [
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'summary' => $row['summary'] ?? '',
                    'body' => $row['body'] ?? '',
                    'exam_id' => $examId,
                    'published_on' => $date['date'] ? substr($date['date'], 0, 10) : now()->toDateString(),
                    'import_date_unknown' => $date['unknown'],
                    'type' => $row['type'] ?: 'notification',
                    'source_label' => $row['source_label'] ?: null,
                    'source_url' => $row['source_url'] ?: null,
                    'status' => $row['status'] ?: Notice::STATUS_DRAFT,
                    'published_at' => ($row['status'] ?? '') === Notice::STATUS_PUBLISHED
                        ? ($date['date'] ?? now()) : null,
                ];
                $summary->statusHit($payload['status']);

                if ($existing) {
                    if (!$dryRun) $existing->update($payload);
                    $summary->updatedCount++;
                } else {
                    if (!$dryRun) Notice::create(['legacy_id' => $legacy] + $payload);
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
