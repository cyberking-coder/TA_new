<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookEdition extends Model
{
    protected $fillable = ['book_id', 'edition_name', 'price_paise', 'stock_state', 'delivery_blurb', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    public function book(): BelongsTo { return $this->belongsTo(Book::class); }

    public function priceInrFormatted(): string
    {
        // Money is integer paise per PRD. Rupees = paise / 100, no float arithmetic.
        $rupees = intdiv($this->price_paise, 100);
        $paise = $this->price_paise % 100;
        return $paise === 0
            ? '₹'.number_format($rupees, 0)
            : '₹'.number_format($rupees, 0).'.'.str_pad((string) $paise, 2, '0', STR_PAD_LEFT);
    }
}
