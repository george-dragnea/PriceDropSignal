<?php

use App\Services\PageFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

function productPageHtml(string $jsonLd = '{"@type":"Product","offers":{"price":"29.99"}}'): string
{
    $padding = str_repeat('<div>Product content</div>', 500);

    return '<html><head><script type="application/ld+json">'.$jsonLd.'</script></head><body>'.$padding.'</body></html>';
}

test('detects captcha and retries with networkidle', function () {
    Log::spy();
    $captchaHtml = '<html><head><title>example.com</title></head><body><script src="https://ct.captcha-delivery.com/i.js"></script></body></html>';

    Process::fake([
        '*' => Process::sequence()
            ->push(Process::result(output: $captchaHtml))
            ->push(Process::result(output: $captchaHtml)),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
        return $message === 'PageFetcher blocked by captcha'
            && $context['url'] === 'https://example.com/product';
    })->twice();

    Log::shouldHaveReceived('info')->withArgs(function ($message, $context) {
        return $message === 'PageFetcher retrying with networkidle'
            && $context['url'] === 'https://example.com/product';
    })->once();
});

test('retries with networkidle on captcha and succeeds', function () {
    Log::spy();
    $captchaHtml = '<html><head><title>example.com</title></head><body><script src="https://ct.captcha-delivery.com/i.js"></script></body></html>';

    Process::fake([
        '*' => Process::sequence()
            ->push(Process::result(output: $captchaHtml))
            ->push(Process::result(output: productPageHtml())),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->not->toBeNull();
    expect($result['html'])->toContain('"price":"29.99"');
    expect($result['error'])->toBeNull();
});

test('detects captcha via hcaptcha', function () {
    Log::spy();
    $captchaHtml = '<html><body><div class="hcaptcha.com">Challenge</div></body></html>';

    Process::fake([
        '*' => Process::result(output: $captchaHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('detects captcha via cloudflare turnstile', function () {
    Log::spy();
    $captchaHtml = '<html><body><div class="cf-turnstile"></div></body></html>';

    Process::fake([
        '*' => Process::result(output: $captchaHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('detects captcha via recaptcha', function () {
    Log::spy();
    $captchaHtml = '<html><body><div class="g-recaptcha" data-sitekey="abc"></div></body></html>';

    Process::fake([
        '*' => Process::result(output: $captchaHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Captcha bot protection');
});

test('skips captcha detection on large pages to avoid false positives', function () {
    // A real product page that happens to mention "captcha-delivery.com" in cookie consent
    $largeHtml = str_repeat('<div>Product content</div>', 5000)
        .'<span>captcha-delivery.com</span>';

    Process::fake([
        '*' => Process::result(output: $largeHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->not->toBeNull();
    expect($result['error'])->toBeNull();
});

test('detects bot block on small pages without captcha markers', function () {
    Log::spy();
    $stubHtml = '<html><head><title>Loading...</title></head><body><script>window.location.reload();</script></body></html>';

    Process::fake([
        '*' => Process::result(output: $stubHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->toBeNull();
    expect($result['error'])->toBe('Bot detection (page too small)');
});

test('returns html when page is not blocked', function () {
    Process::fake([
        '*' => Process::result(output: productPageHtml()),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    expect($result['html'])->not->toBeNull();
    expect($result['error'])->toBeNull();
});
