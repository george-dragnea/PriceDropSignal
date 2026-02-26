<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    Route::get('products', App\Livewire\Products\Index::class)->name('products.index');
    Route::get('products/{product}', App\Livewire\Products\Show::class)->name('products.show');
});

require __DIR__.'/settings.php';
