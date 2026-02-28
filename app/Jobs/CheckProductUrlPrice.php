<?php

namespace App\Jobs;

use App\Models\ProductUrl;
use App\Notifications\PriceDropNotification;
use App\Services\PageFetcher;
use App\Services\PriceExtractor;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Log;

class CheckProductUrlPrice implements ShouldQueue
{
    use Queueable;

    public int $maxExceptions = 2;

    public int $backoff = 30;

    public function __construct(public ProductUrl $productUrl) {}

    /** @return array<int, object> */
    public function middleware(): array
    {
        return [new RateLimited('price-checking')];
    }

    public function retryUntil(): DateTime
    {
        return now()->addHours(24)->toDateTime();
    }

    public function handle(PriceExtractor $extractor, PageFetcher $fetcher): void
    {
        $result = $fetcher->fetch($this->productUrl->url);

        if ($result['html'] === null) {
            Log::warning('Price fetch failed', [
                'url' => $this->productUrl->url,
                'error' => $result['error'],
            ]);

            $this->productUrl->update([
                'last_checked_at' => now(),
                'last_error' => $result['error'],
            ]);

            return;
        }

        $priceCents = $extractor->extract($result['html']);

        if ($priceCents === null) {
            $htmlLength = strlen($result['html']);
            $snippet = mb_substr(strip_tags($result['html']), 0, 300);

            Log::warning('Price extraction failed', [
                'url' => $this->productUrl->url,
                'error' => 'Could not extract price',
                'html_length' => $htmlLength,
                'html_snippet' => $snippet,
            ]);

            $this->productUrl->update([
                'last_checked_at' => now(),
                'last_error' => "Could not extract price (HTML size: {$htmlLength} bytes)",
            ]);

            return;
        }

        $previousPriceCents = $this->productUrl->latest_price_cents;

        $this->productUrl->priceChecks()->create([
            'price_cents' => $priceCents,
            'checked_at' => now(),
        ]);

        $this->productUrl->update([
            'latest_price_cents' => $priceCents,
            'last_checked_at' => now(),
            'last_error' => null,
        ]);

        if ($previousPriceCents !== null && $priceCents < $previousPriceCents) {
            $this->productUrl->product->user->notify(
                new PriceDropNotification($this->productUrl, $previousPriceCents, $priceCents)
            );
        }
    }
}
