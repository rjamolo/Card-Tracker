<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CardPrice
 *
 * @property int $id
 * @property string $card_name
 * @property string $source_url
 * @property int|null $price
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $collected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CardPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_name',
        'source_url',
        'price',
        'image_url',
        'collected_at',
    ];

    protected $dates = ['collected_at'];
    protected $casts = [
        'collected_at' => 'datetime',
    ];
    public function history()
    {
        return $this->hasMany(CardPriceHistory::class, 'card_price_id');
    }
}
