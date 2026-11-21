<?php

namespace App\Support;

/**
 * Canonicalises Indian mobile numbers to the stored form `+91XXXXXXXXXX`.
 *
 * Accepts user input in any of:
 *   9876543210            → +919876543210
 *   09876543210           → +919876543210
 *   919876543210          → +919876543210
 *   +91 98765 43210       → +919876543210
 *   +91-98765-43210       → +919876543210
 *
 * Rejects anything that doesn't resolve to a 10-digit India mobile starting
 * with 6, 7, 8 or 9 (the valid first digits per TRAI).
 */
final class MobileNumber
{
    public static function toCanonical(?string $raw): ?string
    {
        if ($raw === null) return null;
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === '') return null;

        // Strip leading 0 or 91 if the user supplied them.
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        }

        if (strlen($digits) !== 10) return null;
        if (! preg_match('/^[6-9]\d{9}$/', $digits)) return null;

        return '+91'.$digits;
    }

    public static function isValid(?string $raw): bool
    {
        return static::toCanonical($raw) !== null;
    }

    /** `+919876543210` → `9876543210` for display. */
    public static function nationalPart(string $canonical): string
    {
        return str_starts_with($canonical, '+91') ? substr($canonical, 3) : $canonical;
    }
}
