<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class PageFetcher
{
    /** @var array<string, string> */
    private const CAPTCHA_MARKERS = [
        'captcha-delivery.com' => 'DataDome',
        'g-recaptcha' => 'reCAPTCHA',
        'hcaptcha.com' => 'hCaptcha',
        'challenges.cloudflare.com' => 'Cloudflare',
        'cf-turnstile' => 'Cloudflare',
        'arkoselabs.com' => 'Arkose',
        'funcaptcha' => 'Arkose',
        'perimeterx.net' => 'PerimeterX',
        'px-captcha' => 'PerimeterX',
    ];

    /**
     * Fetch the HTML content of a URL using a headless browser.
     *
     * @return array{html: string|null, error: string|null}
     */
    public function fetch(string $url): array
    {
        $result = $this->attemptFetch($url);

        if ($result['error'] === 'Captcha bot protection' || $result['error'] === 'Bot detection (page too small)') {
            Log::info('PageFetcher retrying with networkidle', ['url' => $url, 'reason' => $result['error']]);

            usleep(random_int(3_000_000, 5_000_000));

            $result = $this->attemptFetch($url, 'networkidle');
        }

        return $result;
    }

    /**
     * @return array{html: string|null, error: string|null}
     */
    private function attemptFetch(string $url, string $waitUntil = 'load'): array
    {
        $script = base_path('scripts/fetch-page.js');

        $result = Process::timeout(45)->run(['node', $script, $url, $waitUntil]);

        if ($result->successful()) {
            $html = $result->output();

            $captchaProvider = $this->detectCaptchaProvider($html);
            if ($captchaProvider !== null) {
                Log::warning('PageFetcher blocked by captcha', [
                    'url' => $url,
                    'wait_until' => $waitUntil,
                    'provider' => $captchaProvider,
                ]);

                return ['html' => null, 'error' => 'Captcha bot protection'];
            }

            if ($this->isBotBlockPage($html)) {
                Log::warning('PageFetcher blocked by bot detection', ['url' => $url, 'html_length' => strlen($html)]);

                return ['html' => null, 'error' => 'Bot detection (page too small)'];
            }

            return ['html' => $html, 'error' => null];
        }

        $stderr = trim($result->errorOutput());
        $lines = array_values(array_filter(
            explode("\n", $stderr),
            fn ($line) => ! preg_match('/^Node\.js v[\d.]+/', trim($line)),
        ));
        $errorLine = end($lines) ?: 'Browser fetch failed';

        Log::warning('PageFetcher failed', ['url' => $url, 'error' => $errorLine]);

        return ['html' => null, 'error' => substr($errorLine, 0, 255)];
    }

    private function isBotBlockPage(string $html): bool
    {
        return strlen($html) < 10000;
    }

    /**
     * Detect the CAPTCHA provider from HTML content.
     * Returns the provider name if detected, null otherwise.
     */
    private function detectCaptchaProvider(string $html): ?string
    {
        // Real product pages are typically 50KB+; CAPTCHA block pages are tiny
        if (strlen($html) > 50000) {
            return null;
        }

        foreach (self::CAPTCHA_MARKERS as $marker => $provider) {
            if (stripos($html, $marker) !== false) {
                return $provider;
            }
        }

        return null;
    }
}
