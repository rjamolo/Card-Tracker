<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_price_id',
        'old_price',
        'new_price',
    ];

    public function card()
    {
        return $this->belongsTo(CardPrice::class, 'card_price_id');
    }
}