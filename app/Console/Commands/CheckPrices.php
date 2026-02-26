<?php

namespace App\Console\Commands;

use App\Jobs\CheckProductUrlPrice;
use App\Models\ProductUrl;
use Illuminate\Console\Command;

class CheckPrices extends Command
{
    protected $signature = 'prices:check';

    protected $description = 'Dispatch price check jobs for all tracked URLs';

    public function handle(): int
    {
        $urls = ProductUrl::query()
            ->whereHas('product.user')
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhere('last_checked_at', '<', now()->subHour());
            })
            ->get();

        $count = $urls->count();
        $this->info("Dispatching {$count} price check jobs...");

        foreach ($urls as $url) {
            CheckProductUrlPrice::dispatch($url);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
