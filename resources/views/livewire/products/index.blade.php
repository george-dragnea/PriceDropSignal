<div class="w-full max-w-4xl space-y-6">
        <flux:heading size="xl">{{ __('My Products') }}</flux:heading>
        <flux:text>{{ __('Track product prices across the web and get notified when they drop.') }}</flux:text>

        <form wire:submit="addProduct" class="flex items-end gap-3">
            <div class="flex-1">
                <flux:input wire:model="newProductName" :label="__('Product name')" placeholder="e.g. MacBook Pro M4" />
            </div>
            <flux:button type="submit" variant="primary" icon="plus">
                {{ __('Add Product') }}
            </flux:button>
        </form>

        @if ($products->isEmpty())
            <div class="rounded-xl border border-neutral-200 p-8 text-center dark:border-neutral-700">
                <flux:icon name="tag" class="mx-auto mb-3 size-10 text-zinc-400" />
                <flux:heading>{{ __('No products yet') }}</flux:heading>
                <flux:text class="mt-1">{{ __('Add your first product above to start tracking prices.') }}</flux:text>
            </div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Product') }}</flux:table.column>
                    <flux:table.column>{{ __('Tracked URLs') }}</flux:table.column>
                    <flux:table.column>{{ __('Added') }}</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($products as $product)
                        <flux:table.row :key="$product->id">
                            <flux:table.cell variant="strong">
                                <a href="{{ route('products.show', $product) }}" class="hover:underline" wire:navigate>
                                    {{ $product->name }}
                                </a>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm">{{ $product->urls_count }} {{ str('URL')->plural($product->urls_count) }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>{{ $product->created_at->diffForHumans() }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <flux:modal.trigger name="delete-product-{{ $product->id }}">
                                    <flux:button variant="danger" size="sm" icon="trash">{{ __('Delete') }}</flux:button>
                                </flux:modal.trigger>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

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
        @endif
</div>
