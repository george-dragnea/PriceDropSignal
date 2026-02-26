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

        Log::info('[PageFetcher] Fetching URL', ['url' => $url, 'script' => $script]);

        $result = Process::timeout(45)->run(['node', $script, $url]);

        $stderr = trim($result->errorOutput());
        $exitCode = $result->exitCode();

        Log::info('[PageFetcher] Process finished', [
            'url' => $url,
            'exit_code' => $exitCode,
            'stderr' => $stderr,
            'html_length' => strlen($result->output()),
        ]);

        if ($result->successful()) {
            return ['html' => $result->output(), 'error' => null];
        }

        Log::warning('[PageFetcher] Fetch failed', [
            'url' => $url,
            'exit_code' => $exitCode,
            'stderr' => $stderr,
        ]);

        // Extract the last meaningful line from stderr as the error message
        $lines = array_filter(explode("\n", $stderr));
        $errorLine = end($lines) ?: 'Browser fetch failed';

        return ['html' => null, 'error' => substr($errorLine, 0, 255)];
    }
}
