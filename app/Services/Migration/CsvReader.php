<?php

namespace App\Services\Migration;

class CsvReader
{
    /** @return iterable<array<string,string>> */
    public static function rows(string $path): iterable
    {
        if (!is_file($path)) throw new \InvalidArgumentException("missing: $path");
        $fh = fopen($path, 'r');
        try {
            $header = fgetcsv($fh);
            if (!$header) return;
            while (($row = fgetcsv($fh)) !== false) {
                if (count($row) === 1 && $row[0] === null) continue;
                $row = array_pad($row, count($header), '');
                yield array_combine($header, $row);
            }
        } finally {
            fclose($fh);
        }
    }

    /**
     * Parse a legacy date that may be '0000-00-00' (MySQL zero date).
     * @return array{date: ?string, unknown: bool}
     */
    public static function parseDate(?string $s): array
    {
        $s = trim((string) $s);
        if ($s === '' || str_starts_with($s, '0000-00-00')) {
            return ['date' => null, 'unknown' => true];
        }
        try {
            $d = new \DateTimeImmutable($s);
            return ['date' => $d->format('Y-m-d H:i:s'), 'unknown' => false];
        } catch (\Throwable) {
            return ['date' => null, 'unknown' => true];
        }
    }
}
