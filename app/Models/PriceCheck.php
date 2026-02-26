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

    /**
     * @return array{diff_cents: int, percent: float, direction: string, previous_price_cents: int}|null
     */
    public function priceChangeFromPrevious(): ?array
    {
        $previous = static::where('product_url_id', $this->product_url_id)
            ->where('checked_at', '<', $this->checked_at)
            ->latest('checked_at')
            ->first();

        if (! $previous) {
            return null;
        }

        $diffCents = $this->price_cents - $previous->price_cents;
        $percentChange = ($diffCents / $previous->price_cents) * 100;

        return [
            'diff_cents' => $diffCents,
            'percent' => round($percentChange, 1),
            'direction' => $diffCents < 0 ? 'down' : ($diffCents > 0 ? 'up' : 'same'),
            'previous_price_cents' => $previous->price_cents,
        ];
    }
}
