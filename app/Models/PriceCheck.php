<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceCheck extends Model
{
    /** @use HasFactory<\Database\Factories\PriceCheckFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['price_cents', 'checked_at'];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'checked_at' => 'datetime',
        ];
    }

    public function productUrl(): BelongsTo
    {
        return $this->belongsTo(ProductUrl::class);
    }

    public function formattedPrice(): string
    {
        return number_format($this->price_cents / 100, 2);
    }
}
