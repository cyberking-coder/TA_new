<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'book_edition_id', 'item_description_snapshot',
        'unit_price_paise', 'quantity', 'line_total_paise',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function bookEdition(): BelongsTo { return $this->belongsTo(BookEdition::class); }
}
