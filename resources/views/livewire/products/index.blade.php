<div class="w-full max-w-6xl space-y-6">
    <div>
        <flux:heading size="xl">{{ __('My Products') }}</flux:heading>
        <flux:text>{{ __('Track product prices across the web and get notified when they drop.') }}</flux:text>
    </div>

    {{-- Add product form --}}
    <form wire:submit="addProduct" class="flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <flux:input wire:model="newProductName" :label="__('Product name')" placeholder="e.g. MacBook Pro M4" />
        </div>
        <flux:button type="submit" variant="primary" icon="plus">
            {{ __('Add Product') }}
        </flux:button>
    </form>

    {{-- Search and sort toolbar --}}
    @if ($products->total() > 0 || $search)
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-xs">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search products...')" />
            </div>
            <div class="flex items-center gap-2">
                <flux:text class="text-xs text-zinc-500">{{ __('Sort:') }}</flux:text>
                <flux:button size="sm" :variant="$sortBy === 'name' ? 'filled' : 'ghost'" wire:click="setSort('name')">
                    {{ __('Name') }}
                </flux:button>
                <flux:button size="sm" :variant="$sortBy === 'created_at' ? 'filled' : 'ghost'" wire:click="setSort('created_at')">
                    {{ __('Date') }}
                </flux:button>
            </div>
        </div>
    @endif

    {{-- Products grid --}}
    @if ($products->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 p-12 text-center dark:border-neutral-600">
            <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <flux:icon name="tag" class="size-8 text-zinc-400" />
            </div>
            <flux:heading>{{ $search ? __('No products found') : __('No products yet') }}</flux:heading>
            <flux:text class="mt-1">
                {{ $search
                    ? __('Try a different search term.')
                    : __('Add your first product above to start tracking prices.') }}
            </flux:text>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product) }}" wire:navigate wire:key="product-{{ $product->id }}"
                   class="group block rounded-xl border border-neutral-200 p-5 transition-colors hover:border-brand-300 hover:bg-zinc-50/50 dark:border-neutral-700 dark:hover:border-brand-700 dark:hover:bg-zinc-800/30">
                    {{-- Top row: name + delete --}}
                    <div class="mb-3 flex items-start justify-between">
                        <span class="font-semibold text-zinc-900 group-hover:text-brand-600 dark:text-zinc-100 dark:group-hover:text-brand-400">
                            {{ $product->name }}
                        </span>
                        <flux:modal.trigger name="delete-product-{{ $product->id }}">
                            <flux:button variant="ghost" size="sm" icon="trash"
                                class="relative z-10 opacity-0 transition-opacity group-hover:opacity-100"
                                :aria-label="__('Delete :name', ['name' => $product->name])"
                                x-on:click.prevent.stop />
                        </flux:modal.trigger>
                    </div>

                    {{-- Stats row --}}
                    <div class="mb-3 flex items-center gap-2">
                        <flux:badge size="sm" color="zinc">
                            {{ $product->urls_count }} {{ str('URL')->plural($product->urls_count) }}
                        </flux:badge>
                        @if ($product->has_errors)
                            <flux:badge size="sm" color="red">{{ __('Errors') }}</flux:badge>
                        @endif
                    </div>

                    {{-- Price range --}}
                    @if ($product->urls_min_latest_price_cents)
                        <flux:text class="mb-2 font-mono text-sm">
                            @if ($product->urls_min_latest_price_cents === $product->urls_max_latest_price_cents)
                                {{ number_format($product->urls_min_latest_price_cents / 100, 2) }}
                            @else
                                {{ number_format($product->urls_min_latest_price_cents / 100, 2) }} &ndash; {{ number_format($product->urls_max_latest_price_cents / 100, 2) }}
                            @endif
                        </flux:text>
                    @else
                        <flux:text class="mb-2 text-sm text-zinc-400">{{ __('No prices yet') }}</flux:text>
                    @endif

                    {{-- Footer: date added --}}
                    <flux:text class="text-xs text-zinc-400">
                        {{ __('Added') }} {{ $product->created_at->diffForHumans() }}
                    </flux:text>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @endif

    {{-- Delete modals --}}
    @foreach ($products as $product)
        <flux:modal name="delete-product-{{ $product->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete product?') }}</flux:heading>
                    <flux:text class="mt-2">
                        {{ __('You\'re about to delete ":name" and all its tracked URLs. This cannot be undone.', ['name' => $product->name]) }}
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="danger" wire:click="deleteProduct({{ $product->id }})" x-on:click="$flux.modal('delete-product-{{ $product->id }}').close()">{{ __('Delete') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
