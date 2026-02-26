<div class="w-full max-w-6xl space-y-8">
    <div>
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>
        <flux:text class="mt-1">{{ __('Your price tracking overview at a glance.') }}</flux:text>
    </div>

    @if ($productCount === 0)
        {{-- Onboarding state --}}
        <div class="rounded-xl border border-neutral-200 p-8 text-center dark:border-neutral-700">
            <flux:icon name="tag" class="mx-auto mb-4 size-12 text-zinc-400" />
            <flux:heading size="lg">{{ __('Welcome to PriceDropSignal!') }}</flux:heading>
            <flux:text class="mx-auto mt-2 max-w-md">
                {{ __('Track product prices across the web and get notified when they drop. Start by adding your first product.') }}
            </flux:text>

            <div class="mt-6">
                <flux:button variant="primary" icon="plus" :href="route('products.index')" wire:navigate>
                    {{ __('Add Your First Product') }}
                </flux:button>
            </div>

            <div class="mx-auto mt-8 grid max-w-lg gap-4 text-left md:grid-cols-3">
                <div class="flex gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold dark:bg-zinc-700">1</div>
                    <div>
                        <flux:heading size="sm">{{ __('Add a product') }}</flux:heading>
                        <flux:text class="text-xs">{{ __('Name it anything you like') }}</flux:text>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold dark:bg-zinc-700">2</div>
                    <div>
                        <flux:heading size="sm">{{ __('Paste a URL') }}</flux:heading>
                        <flux:text class="text-xs">{{ __('From any store or page') }}</flux:text>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold dark:bg-zinc-700">3</div>
                    <div>
                        <flux:heading size="sm">{{ __('Get alerts') }}</flux:heading>
                        <flux:text class="text-xs">{{ __('We check prices & notify you') }}</flux:text>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Summary stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                        <flux:icon name="tag" class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-zinc-500">{{ __('Products') }}</flux:text>
                        <div class="text-xl font-semibold">{{ $productCount }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/30">
                        <flux:icon name="link" class="size-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-zinc-500">{{ __('URLs Monitored') }}</flux:text>
                        <div class="text-xl font-semibold">{{ $urlCount }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/30">
                        <flux:icon name="arrow-trending-down" class="size-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-zinc-500">{{ __('Drops This Week') }}</flux:text>
                        <div class="text-xl font-semibold">{{ $recentPriceDrops->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg {{ $errorCount > 0 ? 'bg-red-50 dark:bg-red-900/30' : 'bg-zinc-50 dark:bg-zinc-800' }}">
                        <flux:icon name="exclamation-triangle" class="size-5 {{ $errorCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-400' }}" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-zinc-500">{{ __('URL Errors') }}</flux:text>
                        <div class="text-xl font-semibold">{{ $errorCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent price drops --}}
        <div class="space-y-3">
            <flux:heading size="lg">{{ __('Recent Price Drops') }}</flux:heading>

            @if ($recentPriceDrops->isEmpty())
                <div class="rounded-xl border border-dashed border-neutral-300 p-6 text-center dark:border-neutral-600">
                    <flux:icon name="arrow-trending-down" class="mx-auto mb-2 size-8 text-zinc-400" />
                    <flux:text>{{ __('No price drops detected this week. We\'ll notify you when prices fall!') }}</flux:text>
                </div>
            @else
                <div class="divide-y divide-neutral-200 rounded-xl border border-neutral-200 dark:divide-neutral-700 dark:border-neutral-700">
                    @foreach ($recentPriceDrops as $check)
                        @php $change = $check->priceChangeFromPrevious(); @endphp
                        @if ($change)
                            <div class="flex items-center justify-between gap-4 p-4">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('products.show', $check->productUrl->product) }}" class="font-medium hover:underline" wire:navigate>
                                        {{ $check->productUrl->product->name }}
                                    </a>
                                    <flux:text class="truncate text-xs">
                                        {{ Str::limit($check->productUrl->url, 60) }}
                                    </flux:text>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-zinc-400 line-through">
                                            ${{ number_format($change['previous_price_cents'] / 100, 2) }}
                                        </span>
                                        <span class="font-mono font-semibold text-green-600 dark:text-green-400">
                                            ${{ $check->formattedPrice() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-end gap-1 text-xs text-green-600 dark:text-green-400">
                                        <flux:icon name="arrow-down" variant="micro" class="size-3" />
                                        {{ abs($change['percent']) }}%
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick add + recent activity --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="flex flex-col rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <flux:heading size="lg" class="mb-4">{{ __('Quick Add Product') }}</flux:heading>
                <form wire:submit="addProduct" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <flux:input wire:model="newProductName" placeholder="e.g. MacBook Pro M4" />
                    </div>
                    <flux:button type="submit" variant="primary" icon="plus">
                        {{ __('Add') }}
                    </flux:button>
                </form>
                <div class="mt-auto pt-4">
                    <flux:button variant="ghost" icon="tag" size="sm" :href="route('products.index')" wire:navigate>
                        {{ __('View all products') }}
                    </flux:button>
                </div>
            </div>

            <div class="flex flex-col rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <flux:heading size="lg" class="mb-4">{{ __('Recent Activity') }}</flux:heading>
                @if ($recentActivity->isEmpty())
                    <flux:text class="text-sm text-zinc-500">{{ __('No activity yet. Add products and URLs to start tracking.') }}</flux:text>
                @else
                    <div class="max-h-72 space-y-3 overflow-y-auto">
                        @foreach ($recentActivity as $check)
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <flux:icon name="currency-dollar" variant="micro" class="size-4 text-zinc-500" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <flux:text class="truncate text-sm">
                                        {{ Str::limit(parse_url($check->productUrl->url, PHP_URL_HOST), 30) }}
                                    </flux:text>
                                    <flux:text class="text-xs text-zinc-400">
                                        ${{ $check->formattedPrice() }} &middot; {{ $check->checked_at->diffForHumans() }}
                                    </flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
