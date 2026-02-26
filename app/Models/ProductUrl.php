<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductUrl extends Model
{
    /** @use HasFactory<\Database\Factories\ProductUrlFactory> */
    use HasFactory;

    protected $fillable = ['url', 'latest_price_cents', 'last_checked_at', 'last_error'];

    protected function casts(): array
    {
        return [
            'last_checked_at' => 'datetime',
            'latest_price_cents' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceChecks(): HasMany
    {
        return $this->hasMany(PriceCheck::class);
    }

    public function formattedPrice(): ?string
    {
        if ($this->latest_price_cents === null) {
            return null;
        }

        return number_format($this->latest_price_cents / 100, 2);
    }
}
