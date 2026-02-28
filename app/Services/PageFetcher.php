<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class PageFetcher
{
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

            if ($this->isCaptchaPage($html)) {
                Log::warning('PageFetcher blocked by captcha', ['url' => $url, 'wait_until' => $waitUntil]);

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
        // Real product pages are typically 50KB+; a page under 10KB
        // with no recognizable CAPTCHA is likely a bot detection stub.
        return strlen($html) < 10000;
    }

    private function isCaptchaPage(string $html): bool
    {
        // Real product pages are typically 50KB+; CAPTCHA block pages are tiny
        if (strlen($html) > 50000) {
            return false;
        }

        $markers = [
            'captcha-delivery.com',
            'g-recaptcha',
            'hcaptcha.com',
            'challenges.cloudflare.com',
            'cf-turnstile',
            'arkoselabs.com',
            'funcaptcha',
            'perimeterx.net',
            'px-captcha',
        ];

        foreach ($markers as $marker) {
            if (stripos($html, $marker) !== false) {
                return true;
            }
        }

        return false;
    }
}
