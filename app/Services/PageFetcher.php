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
        $script = base_path('scripts/fetch-page.js');

        $result = Process::timeout(45)->run(['node', $script, $url]);

        if ($result->successful()) {
            $html = $result->output();

            if ($this->isCaptchaPage($html)) {
                Log::warning('PageFetcher blocked by captcha', ['url' => $url]);

                return ['html' => null, 'error' => 'Captcha bot protection'];
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

    private function isCaptchaPage(string $html): bool
    {
        $markers = [
            'captcha-delivery.com',
            'DataDome',
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
