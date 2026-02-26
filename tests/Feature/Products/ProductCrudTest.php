<?php

use App\Livewire\Products\Index;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

test('guests cannot access products page', function () {
    $this->get(route('products.index'))->assertRedirect(route('login'));
});

test('authenticated users can view products page', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('products.index'))->assertOk();
});

test('user can add a product', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('newProductName', 'MacBook Pro')
        ->call('addProduct')
        ->assertHasNoErrors();

    expect($user->products()->count())->toBe(1);
    expect($user->products()->first()->name)->toBe('MacBook Pro');
});

test('product name is required', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Index::class)
        ->set('newProductName', '')
        ->call('addProduct')
        ->assertHasErrors(['newProductName' => 'required']);
});

test('product name cannot exceed 255 characters', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Index::class)
        ->set('newProductName', str_repeat('a', 256))
        ->call('addProduct')
        ->assertHasErrors(['newProductName' => 'max']);
});

test('user can delete their own product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('deleteProduct', $product->id)
        ->assertHasNoErrors();

    expect($user->products()->count())->toBe(0);
});

test('user cannot delete another users product', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $product = Product::factory()->for($otherUser)->create();

    $this->actingAs($user);

    $this->withoutExceptionHandling();

    expect(fn () => Livewire::test(Index::class)->call('deleteProduct', $product->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    expect($product->fresh())->not->toBeNull();
});

test('user cannot view another users product', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $product = Product::factory()->for($otherUser)->create();

    $this->actingAs($user);

    $this->get(route('products.show', $product))->assertForbidden();
});

test('user can view their own product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create();

    $this->actingAs($user);

    $this->get(route('products.show', $product))->assertOk();
});

test('products are listed for authenticated user', function () {
    $user = User::factory()->create();
    $product = Product::factory()->for($user)->create(['name' => 'Test Product']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Test Product');
});
