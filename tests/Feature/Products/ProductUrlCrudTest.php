<?php

use App\Livewire\Products\Show;
use App\Models\Product;
use App\Models\ProductUrl;
use App\Models\User;
use Livewire\Livewire;

test('user can add a URL to their product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['product' => $product])
        ->set('newUrl', 'https://www.example.com/product')
        ->call('addUrl')
        ->assertHasNoErrors();

    expect($product->urls()->count())->toBe(1);
    expect($product->urls()->first()->url)->toBe('https://www.example.com/product');
});

test('URL must be valid', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['product' => $product])
        ->set('newUrl', 'not-a-url')
        ->call('addUrl')
        ->assertHasErrors(['newUrl' => 'url']);
});

test('URL is required', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['product' => $product])
        ->set('newUrl', '')
        ->call('addUrl')
        ->assertHasErrors(['newUrl' => 'required']);
});

test('user can delete a URL from their product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();
    $url = ProductUrl::factory()->for($product)->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['product' => $product])
        ->call('deleteUrl', $url->id)
        ->assertHasNoErrors();

    expect($product->urls()->count())->toBe(0);
});

test('URLs are displayed on the product show page', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();
    ProductUrl::factory()->for($product)->create(['url' => 'https://www.example.com/test-product']);

    $this->actingAs($user);

    Livewire::test(Show::class, ['product' => $product])
        ->assertSee('example.com/test-product');
});
