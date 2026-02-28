<?php

namespace App\Livewire\Products;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('My Products')]
class Index extends Component
{
    use WithPagination;

    public string $newProductName = '';

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setSort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = $column === 'name' ? 'asc' : 'desc';
        }

        $this->resetPage();
    }

    public function addProduct(): void
    {
        $this->validate([
            'newProductName' => ['required', 'string', 'max:255', 'not_regex:/https?:\/\/|www\./i'],
        ], [
            'newProductName.not_regex' => __('The product name cannot contain a URL.'),
        ]);

        $product = Auth::user()->products()->create(['name' => $this->newProductName]);

        $this->reset('newProductName');

        $this->redirect(route('products.show', $product), navigate: true);
    }

    public function deleteProduct(int $productId): void
    {
        $product = Auth::user()->products()->findOrFail($productId);
        $name = $product->name;
        $product->delete();

        Flux::toast(
            heading: __('Product deleted'),
            text: __('":name" and all its tracked URLs have been removed.', ['name' => $name]),
            variant: 'success',
        );
    }

    public function render(): View
    {
        $query = Auth::user()->products()
            ->withCount('urls')
            ->withMin('urls', 'latest_price_cents')
            ->withMax('urls', 'latest_price_cents')
            ->withExists(['urls as has_errors' => fn ($q) => $q->whereNotNull('last_error')]);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return view('livewire.products.index', [
            'products' => $query->paginate(12),
        ]);
    }
}
