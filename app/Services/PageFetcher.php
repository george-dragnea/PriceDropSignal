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

        return ['html' => null, 'error' => trim($result->errorOutput()) ?: 'Browser fetch failed'];
    }
}
