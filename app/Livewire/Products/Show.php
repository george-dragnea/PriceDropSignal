<?php

namespace App\Livewire\Products;

use App\Jobs\CheckProductUrlPrice;
use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Product Details')]
class Show extends Component
{
    public Product $product;

    public string $newUrl = '';

    public function mount(Product $product): void
    {
        abort_unless($product->user_id === Auth::id(), 403);
        $this->product = $product;
    }

    public function addUrl(): void
    {
        $this->validate([
            'newUrl' => ['required', 'url', 'max:2048'],
        ]);

        $productUrl = $this->product->urls()->create(['url' => $this->newUrl]);

        CheckProductUrlPrice::dispatch($productUrl);

        $this->reset('newUrl');

        Flux::toast(
            heading: __('URL added'),
            text: __('The URL has been added. Checking price now...'),
            variant: 'success',
        );
    }

    public function deleteUrl(int $urlId): void
    {
        $this->product->urls()->findOrFail($urlId)->delete();

        Flux::toast(
            heading: __('URL removed'),
            text: __('The URL and its price history have been deleted.'),
            variant: 'success',
        );
    }

    public function render(): View
    {
        $urls = $this->product->urls()
            ->with(['priceChecks' => fn ($q) => $q->latest('checked_at')->limit(30)])
            ->latest()
            ->get();

        return view('livewire.products.show', [
            'urls' => $urls,
            'urlCount' => $this->product->urls()->count(),
            'okCount' => $this->product->urls()->whereNull('last_error')->whereNotNull('last_checked_at')->count(),
            'errorCount' => $this->product->urls()->whereNotNull('last_error')->count(),
            'hasPendingChecks' => $urls->contains(fn ($url) => $url->last_checked_at === null),
        ]);
    }
}
