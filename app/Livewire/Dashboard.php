<?php

namespace App\Livewire;

use App\Models\PriceCheck;
use App\Models\ProductUrl;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public string $newProductName = '';

    public function addProduct(): void
    {
        $this->validate([
            'newProductName' => ['required', 'string', 'max:255'],
        ]);

        $product = Auth::user()->products()->create(['name' => $this->newProductName]);

        $this->reset('newProductName');

        Flux::toast(
            heading: __('Product added'),
            text: __('":name" has been created.', ['name' => $product->name]),
            variant: 'success',
        );
    }

    public function render(): View
    {
        $user = Auth::user();

        $productCount = $user->products()->count();
        $urlCount = $productCount > 0
            ? $user->products()->withCount('urls')->get()->sum('urls_count')
            : 0;

        $errorCount = ProductUrl::query()
            ->whereHas('product', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('last_error')
            ->count();

        $recentPriceDrops = collect();
        if ($productCount > 0) {
            $recentPriceDrops = PriceCheck::query()
                ->whereHas('productUrl.product', fn ($q) => $q->where('user_id', $user->id))
                ->where('checked_at', '>=', now()->subWeek())
                ->latest('checked_at')
                ->with('productUrl.product')
                ->limit(20)
                ->get()
                ->filter(function ($check) {
                    $change = $check->priceChangeFromPrevious();

                    return $change && $change['direction'] === 'down';
                })
                ->take(5);
        }

        $recentActivity = collect();
        if ($productCount > 0) {
            $recentActivity = PriceCheck::query()
                ->whereHas('productUrl.product', fn ($q) => $q->where('user_id', $user->id))
                ->latest('checked_at')
                ->with('productUrl.product')
                ->limit(8)
                ->get();
        }

        return view('livewire.dashboard', compact(
            'productCount',
            'urlCount',
            'errorCount',
            'recentPriceDrops',
            'recentActivity',
        ));
    }
}
