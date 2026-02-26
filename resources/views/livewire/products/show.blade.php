<div class="w-full max-w-4xl space-y-6">
        <div>
            <flux:button variant="ghost" icon="arrow-left" :href="route('products.index')" wire:navigate size="sm" class="mb-2">
                {{ __('Back to products') }}
            </flux:button>
            <flux:heading size="xl">{{ $product->name }}</flux:heading>
        </div>

        <form wire:submit="addUrl" class="flex items-end gap-3">
            <div class="flex-1">
                <flux:input wire:model="newUrl" :label="__('Track a new URL')" type="url" placeholder="https://www.example.com/product-page" />
            </div>
            <flux:button type="submit" variant="primary" icon="plus">
                {{ __('Add URL') }}
            </flux:button>
        </form>

        @if ($urls->isEmpty())
            <div class="rounded-xl border border-neutral-200 p-8 text-center dark:border-neutral-700">
                <flux:icon name="link" class="mx-auto mb-3 size-10 text-zinc-400" />
                <flux:heading>{{ __('No URLs tracked yet') }}</flux:heading>
                <flux:text class="mt-1">{{ __('Add a product URL above to start tracking its price.') }}</flux:text>
            </div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('URL') }}</flux:table.column>
                    <flux:table.column>{{ __('Price') }}</flux:table.column>
                    <flux:table.column>{{ __('Last Checked') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($urls as $url)
                        <flux:table.row :key="$url->id">
                            <flux:table.cell>
                                <a href="{{ $url->url }}" target="_blank" rel="noopener" class="hover:underline" title="{{ $url->url }}">
                                    {{ Str::limit($url->url, 50) }}
                                </a>
                            </flux:table.cell>
                            <flux:table.cell variant="strong">
                                {{ $url->formattedPrice() ?? '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $url->last_checked_at?->diffForHumans() ?? __('Never') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($url->last_error)
                                    <flux:badge color="red" size="sm">{{ __('Error') }}</flux:badge>
                                @elseif ($url->last_checked_at)
                                    <flux:badge color="green" size="sm">{{ __('OK') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">{{ __('Pending') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell align="end">
                                <flux:modal.trigger name="delete-url-{{ $url->id }}">
                                    <flux:button variant="danger" size="sm" icon="trash">{{ __('Delete') }}</flux:button>
                                </flux:modal.trigger>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            @foreach ($urls as $url)
                <flux:modal name="delete-url-{{ $url->id }}" class="min-w-[22rem]">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">{{ __('Delete URL?') }}</flux:heading>
                            <flux:text class="mt-2">
                                {{ __('This will remove the URL and all its price history. This cannot be undone.') }}
                            </flux:text>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                            </flux:modal.close>
                            <flux:button variant="danger" wire:click="deleteUrl({{ $url->id }})" x-on:click="$flux.modal('delete-url-{{ $url->id }}').close()">{{ __('Delete') }}</flux:button>
                        </div>
                    </div>
                </flux:modal>
            @endforeach

            {{-- Price History Section --}}
            <div class="space-y-4">
                <flux:heading size="lg">{{ __('Price History') }}</flux:heading>

                @foreach ($urls as $url)
                    @if ($url->priceChecks->isNotEmpty())
                        <div class="rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                            <flux:text class="mb-3 font-medium">{{ Str::limit($url->url, 60) }}</flux:text>

                            <div class="space-y-1">
                                @foreach ($url->priceChecks as $check)
                                    <div class="flex items-center justify-between text-sm">
                                        <flux:text>{{ $check->checked_at->format('M d, Y h:i A') }}</flux:text>
                                        <flux:text class="font-mono">{{ $check->formattedPrice() }}</flux:text>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
</div>
