<?php

namespace App\Livewire\Products;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('My Products')]
class Index extends Component
{
    public string $newProductName = '';

    public function addProduct(): void
    {
        $this->validate([
            'newProductName' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->products()->create(['name' => $this->newProductName]);

        $this->reset('newProductName');
    }

    public function deleteProduct(int $productId): void
    {
        Auth::user()->products()->findOrFail($productId)->delete();
    }

    public function render(): View
    {
        return view('livewire.products.index', [
            'products' => Auth::user()->products()
                ->withCount('urls')
                ->latest()
                ->get(),
        ]);
    }
}
