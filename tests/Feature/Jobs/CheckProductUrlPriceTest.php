<?php

use App\Jobs\CheckProductUrlPrice;
use App\Models\ProductUrl;
use App\Notifications\PriceDropNotification;
use App\Services\PageFetcher;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

function mockFetcher(string $html): void
{
    app()->bind(PageFetcher::class, function () use ($html) {
        $mock = Mockery::mock(PageFetcher::class);
        $mock->shouldReceive('fetch')->andReturn(['html' => $html, 'error' => null]);

        return $mock;
    });
}

function mockFetcherError(string $error): void
{
    app()->bind(PageFetcher::class, function () use ($error) {
        $mock = Mockery::mock(PageFetcher::class);
        $mock->shouldReceive('fetch')->andReturn(['html' => null, 'error' => $error]);

        return $mock;
    });
}

test('job fetches URL and records price from JSON-LD', function () {
    mockFetcher('<html><head><script type="application/ld+json">{"@type":"Product","offers":{"price":"29.99"}}</script></head></html>');

    $url = ProductUrl::factory()->create(['latest_price_cents' => null, 'last_checked_at' => null]);

    CheckProductUrlPrice::dispatchSync($url);

    $url->refresh();
    expect($url->latest_price_cents)->toBe(2999);
    expect($url->last_checked_at)->not->toBeNull();
    expect($url->last_error)->toBeNull();
    expect($url->priceChecks()->count())->toBe(1);
});

test('job sends notification when price drops', function () {
    Notification::fake();
    mockFetcher('<html><head><script type="application/ld+json">{"@type":"Product","offers":{"price":"19.99"}}</script></head></html>');

    $url = ProductUrl::factory()->create(['latest_price_cents' => 2999]);

    CheckProductUrlPrice::dispatchSync($url);

    Notification::assertSentTo(
        $url->product->user,
        PriceDropNotification::class,
        function (PriceDropNotification $notification) {
            return $notification->oldPriceCents === 2999 && $notification->newPriceCents === 1999;
        }
    );
});

test('job does not notify when price increases', function () {
    Notification::fake();
    mockFetcher('<html><head><script type="application/ld+json">{"@type":"Product","offers":{"price":"39.99"}}</script></head></html>');

    $url = ProductUrl::factory()->create(['latest_price_cents' => 2999]);

    CheckProductUrlPrice::dispatchSync($url);

    Notification::assertNothingSent();
});

test('job does not notify on first price check', function () {
    Notification::fake();
    mockFetcher('<html><head><script type="application/ld+json">{"@type":"Product","offers":{"price":"29.99"}}</script></head></html>');

    $url = ProductUrl::factory()->create(['latest_price_cents' => null]);

    CheckProductUrlPrice::dispatchSync($url);

    Notification::assertNothingSent();
});

test('job handles fetch errors gracefully', function () {
    Log::spy();
    mockFetcherError('Navigation timeout');

    $url = ProductUrl::factory()->create(['latest_price_cents' => 2999]);

    CheckProductUrlPrice::dispatchSync($url);

    $url->refresh();
    expect($url->last_error)->toBe('Navigation timeout');
    expect($url->last_checked_at)->not->toBeNull();
    expect($url->latest_price_cents)->toBe(2999);

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) use ($url) {
        return $message === 'Price fetch failed'
            && $context['url'] === $url->url
            && $context['error'] === 'Navigation timeout';
    })->once();
});

test('job handles unparseable pages gracefully', function () {
    Log::spy();
    mockFetcher('<html><body>No price here</body></html>');

    $url = ProductUrl::factory()->create(['latest_price_cents' => 2999]);

    CheckProductUrlPrice::dispatchSync($url);

    $url->refresh();
    expect($url->last_error)->toBe('Could not extract price');
    expect($url->latest_price_cents)->toBe(2999);

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) use ($url) {
        return $message === 'Price extraction failed'
            && $context['url'] === $url->url
            && $context['error'] === 'Could not extract price';
    })->once();
});

test('job has rate limited middleware keyed by domain', function () {
    $url = ProductUrl::factory()->create(['url' => 'https://amazon.com/product/123']);

    $job = new CheckProductUrlPrice($url);
    $middleware = $job->middleware();

    expect($middleware)->toHaveCount(1);
    expect($middleware[0])->toBeInstanceOf(RateLimited::class);
});
