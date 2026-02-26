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

    /**
     * @return array{diff_cents: int, percent: float, direction: string}|null
     */
    public function priceChangeFromPrevious(): ?array
    {
        $checks = $this->priceChecks()->latest('checked_at')->limit(2)->get();

        if ($checks->count() < 2) {
            return null;
        }

        $current = $checks->first();
        $previous = $checks->last();
        $diffCents = $current->price_cents - $previous->price_cents;
        $percent = ($diffCents / $previous->price_cents) * 100;

        return [
            'diff_cents' => $diffCents,
            'percent' => round($percent, 1),
            'direction' => $diffCents < 0 ? 'down' : ($diffCents > 0 ? 'up' : 'same'),
        ];
    }
}
