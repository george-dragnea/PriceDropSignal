<?php

use App\Services\PageFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

test('detects captcha via captcha-delivery.com', function () {
    Log::spy();
    Process::fake([
        '*' => Process::result(
            output: '<html><head><title>example.com</title></head><body><script src="https://ct.captcha-delivery.com/i.js"></script></body></html>',
        ),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
        return $message === 'PageFetcher blocked by captcha'
            && $context['url'] === 'https://example.com/product';
    })->once();
});

test('detects captcha via hcaptcha', function () {
    Log::spy();
    Process::fake([
        '*' => Process::result(
            output: '<html><body><div class="hcaptcha.com">Challenge</div></body></html>',
        ),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('detects captcha via cloudflare turnstile', function () {
    Log::spy();
    Process::fake([
        '*' => Process::result(
            output: '<html><body><div class="cf-turnstile"></div></body></html>',
        ),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('detects captcha via recaptcha', function () {
    Log::spy();
    Process::fake([
        '*' => Process::result(
            output: '<html><body><div class="g-recaptcha" data-sitekey="abc"></div></body></html>',
        ),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('returns html when page is not blocked', function () {
    Process::fake([
        '*' => Process::result(
            output: '<html><head><script type="application/ld+json">{"@type":"Product","offers":{"price":"29.99"}}</script></head></html>',
        ),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->not->toBeNull();
    expect($result['error'])->toBeNull();
});
