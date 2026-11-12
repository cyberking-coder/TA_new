<?php

namespace App\Services\Migration;

/**
 * Aggregates per-import stats so the rehearsal comparator can diff runs.
 * All numbers come from what the importer actually did, never from an assumed count.
 */
class ImportSummary
{
    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $skippedCount = 0;
    public int $dateUnknownCount = 0;
    /** @var array<string,int> */
    public array $perStatusCount = [];
    /** @var list<string> */
    public array $exceptions = [];

    public function __construct(public readonly string $module) {}

    public function statusHit(string $status): void
    {
        $this->perStatusCount[$status] = ($this->perStatusCount[$status] ?? 0) + 1;
    }

    public function totalProcessed(): int
    {
        return $this->createdCount + $this->updatedCount + $this->skippedCount;
    }

    public function toArray(): array
    {
        return [
            'module' => $this->module,
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'skipped' => $this->skippedCount,
            'date_unknown' => $this->dateUnknownCount,
            'per_status' => $this->perStatusCount,
            'exceptions' => $this->exceptions,
            'total_processed' => $this->totalProcessed(),
        ];
    }
}
