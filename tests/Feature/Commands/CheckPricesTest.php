<?php

use App\Jobs\CheckProductUrlPrice;
use App\Models\ProductUrl;
use Illuminate\Support\Facades\Queue;

test('command dispatches jobs for URLs needing checks', function () {
    Queue::fake();

    ProductUrl::factory()->count(3)->create(['last_checked_at' => null]);

    $this->artisan('prices:check')
        ->assertSuccessful();

    Queue::assertCount(3);
});

test('command skips recently checked URLs', function () {
    Queue::fake();

    ProductUrl::factory()->create(['last_checked_at' => now()]);
    ProductUrl::factory()->create(['last_checked_at' => null]);

    $this->artisan('prices:check')
        ->assertSuccessful();

    Queue::assertCount(1);
});

test('command dispatches jobs for stale URLs', function () {
    Queue::fake();

    ProductUrl::factory()->create(['last_checked_at' => now()->subHours(2)]);

    $this->artisan('prices:check')
        ->assertSuccessful();

    Queue::assertPushed(CheckProductUrlPrice::class);
});
