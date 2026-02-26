<?php

namespace App\Jobs;

use App\Models\ProductUrl;
use App\Notifications\PriceDropNotification;
use App\Services\PageFetcher;
use App\Services\PriceExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckProductUrlPrice implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $backoff = 30;

    public function __construct(public ProductUrl $productUrl) {}

    public function handle(PriceExtractor $extractor, PageFetcher $fetcher): void
    {
        Log::info('[CheckPrice] Starting', [
            'product_url_id' => $this->productUrl->id,
            'url' => $this->productUrl->url,
            'previous_price' => $this->productUrl->latest_price_cents,
        ]);

        $result = $fetcher->fetch($this->productUrl->url);

        if ($result['html'] === null) {
            Log::warning('[CheckPrice] Fetch returned no HTML', [
                'product_url_id' => $this->productUrl->id,
                'error' => $result['error'],
            ]);

            $this->productUrl->update([
                'last_checked_at' => now(),
                'last_error' => $result['error'],
            ]);

            return;
        }

        Log::info('[CheckPrice] Got HTML, extracting price', [
            'product_url_id' => $this->productUrl->id,
            'html_length' => strlen($result['html']),
        ]);

        $priceCents = $extractor->extract($result['html']);

        if ($priceCents === null) {
            Log::warning('[CheckPrice] Could not extract price', [
                'product_url_id' => $this->productUrl->id,
                'html_snippet' => substr($result['html'], 0, 500),
            ]);

            $this->productUrl->update([
                'last_checked_at' => now(),
                'last_error' => 'Could not extract price',
            ]);

            return;
        }

        $previousPriceCents = $this->productUrl->latest_price_cents;

        Log::info('[CheckPrice] Price extracted', [
            'product_url_id' => $this->productUrl->id,
            'price_cents' => $priceCents,
            'previous_price_cents' => $previousPriceCents,
        ]);

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
            Log::info('[CheckPrice] Price dropped! Notifying user', [
                'product_url_id' => $this->productUrl->id,
                'old' => $previousPriceCents,
                'new' => $priceCents,
            ]);

            $this->productUrl->product->user->notify(
                new PriceDropNotification($this->productUrl, $previousPriceCents, $priceCents)
            );
        }
    }
}
