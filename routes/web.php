<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/terms', 'pages.legal.terms')->name('legal.terms');
Route::view('/privacy', 'pages.legal.privacy')->name('legal.privacy');
Route::view('/cookies', 'pages.legal.cookies')->name('legal.cookies');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    Route::get('products', App\Livewire\Products\Index::class)->name('products.index');
    Route::get('products/{product}', App\Livewire\Products\Show::class)->name('products.show');
});

require __DIR__.'/settings.php';
