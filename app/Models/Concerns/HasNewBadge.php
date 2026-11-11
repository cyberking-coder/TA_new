<?php

namespace App\Models\Concerns;

/**
 * Shared "NEW" badge behaviour.
 * - `new_until` is a nullable timestamp; nothing shows past that point.
 * - `isNew()` is used by Blade partials to decide whether to render the pulse badge.
 * - `markAsNew(int $days)` extends the window; `unmarkAsNew()` clears it.
 *
 * Models that use this trait must:
 *  - add a `new_until` column (see 2026_11_11 migration)
 *  - add 'new_until' to $fillable
 *  - cast 'new_until' => 'datetime'
 */
trait HasNewBadge
{
    public const NEW_DEFAULT_DAYS = 7;

    public function isNew(): bool
    {
        return $this->new_until && $this->new_until->isFuture();
    }

    public function markAsNew(int $days = self::NEW_DEFAULT_DAYS): void
    {
        $this->update(['new_until' => now()->addDays($days)]);
    }

    public function unmarkAsNew(): void
    {
        $this->update(['new_until' => null]);
    }
}
