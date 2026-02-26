<?php

namespace App\Services;

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
            return ['html' => $result->output(), 'error' => null];
        }

        $stderr = trim($result->errorOutput());
        $lines = array_filter(explode("\n", $stderr));
        $errorLine = end($lines) ?: 'Browser fetch failed';

        return ['html' => null, 'error' => substr($errorLine, 0, 255)];
    }
}
