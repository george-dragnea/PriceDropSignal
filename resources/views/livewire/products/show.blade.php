<div class="w-full max-w-6xl space-y-6" @if ($hasPendingChecks) wire:poll.5s @endif>
    {{-- Breadcrumb --}}
    <x-breadcrumbs :items="[
        ['label' => __('Products'), 'href' => route('products.index')],
        ['label' => $product->name, 'href' => '#'],
    ]" />

    {{-- Product header card --}}
    <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl">{{ $product->name }}</flux:heading>
                <div class="mt-2 flex items-center gap-3">
                    <flux:badge size="sm">{{ $urlCount }} {{ str('URL')->plural($urlCount) }}</flux:badge>
                    @if ($okCount > 0)
                        <flux:badge size="sm" color="green">{{ $okCount }} {{ __('OK') }}</flux:badge>
                    @endif
                    @if ($errorCount > 0)
                        <flux:badge size="sm" color="red">{{ $errorCount }} {{ str('Error')->plural($errorCount) }}</flux:badge>
                    @endif
                </div>
            </div>
            <form wire:submit="addUrl" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                <flux:input wire:model="newUrl" type="url" placeholder="https://..." size="sm" class="sm:w-64" />
                <flux:button type="submit" variant="primary" icon="plus" size="sm">
                    {{ __('Add URL') }}
                </flux:button>
            </form>
        </div>
    </div>

    {{-- URL cards --}}
    @if ($urls->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 p-12 text-center dark:border-neutral-600">
            <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <flux:icon name="link" class="size-8 text-zinc-400" />
            </div>
            <flux:heading>{{ __('No URLs tracked yet') }}</flux:heading>
            <flux:text class="mt-1">{{ __('Add a product URL above to start tracking its price.') }}</flux:text>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($urls as $url)
                @php $change = $url->priceChangeFromPrevious(); @endphp
                <div class="rounded-xl border border-neutral-200 transition-colors hover:border-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600"
                     wire:key="url-{{ $url->id }}"
                     x-data="{ expanded: false }">
                    {{-- Card main content --}}
                    <div class="p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            {{-- Left: URL + status --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    @if ($url->last_error)
                                        <span class="size-2 shrink-0 rounded-full bg-red-500"></span>
                                    @elseif ($url->last_checked_at)
                                        <span class="size-2 shrink-0 rounded-full bg-green-500"></span>
                                    @else
                                        <span class="size-2 shrink-0 rounded-full bg-zinc-400 animate-pulse"></span>
                                    @endif
                                    <a href="{{ $url->url }}" target="_blank" rel="noopener"
                                       class="truncate text-sm hover:underline" title="{{ $url->url }}">
                                        {{ Str::limit($url->url, 70) }}
                                    </a>
                                </div>
                                <flux:text class="mt-1 text-xs text-zinc-400">
                                    {{ __('Last checked') }}: {{ $url->last_checked_at?->diffForHumans() ?? __('Never') }}
                                    @if ($url->last_error)
                                        &middot; <span class="text-red-500">{{ Str::limit($url->last_error, 40) }}</span>
                                    @endif
                                </flux:text>
                            </div>

                            {{-- Right: Price + actions --}}
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    @if ($url->formattedPrice())
                                        <span class="font-mono text-lg font-semibold">${{ $url->formattedPrice() }}</span>
                                        @if ($change && $change['direction'] !== 'same')
                                            <div class="flex items-center justify-end gap-1 text-xs
                                                {{ $change['direction'] === 'down' ? 'text-green-600 dark:text-green-400' : '' }}
                                                {{ $change['direction'] === 'up' ? 'text-red-600 dark:text-red-400' : '' }}">
                                                @if ($change['direction'] === 'down')
                                                    <flux:icon name="arrow-down" variant="micro" class="size-3" />
                                                @elseif ($change['direction'] === 'up')
                                                    <flux:icon name="arrow-up" variant="micro" class="size-3" />
                                                @endif
                                                {{ abs($change['percent']) }}%
                                            </div>
                                        @endif
                                    @elseif (! $url->last_checked_at)
                                        <div class="flex items-center gap-1.5 text-sm text-zinc-400">
                                            <flux:icon name="arrow-path" class="size-3.5 animate-spin" />
                                            {{ __('Checking...') }}
                                        </div>
                                    @else
                                        <flux:text class="text-sm text-zinc-400">{{ __('No price') }}</flux:text>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1">
                                    @if ($url->priceChecks->isNotEmpty())
                                        <flux:button variant="ghost" size="sm" icon="chevron-down"
                                            x-on:click="expanded = !expanded"
                                            ::class="expanded && 'rotate-180'"
                                            class="transition-transform" />
                                    @endif
                                    <flux:modal.trigger name="delete-url-{{ $url->id }}">
                                        <flux:button variant="ghost" size="sm" icon="trash"
                                            class="text-red-500 hover:text-red-700"
                                            :aria-label="__('Delete URL')" />
                                    </flux:modal.trigger>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Expandable price history --}}
                    <div x-show="expanded" x-collapse class="border-t border-neutral-200 dark:border-neutral-700">
                        <div class="p-5">
                            @if ($url->priceChecks->isNotEmpty())
                                {{-- CSS-only mini bar chart --}}
                                <div class="mb-4">
                                    <flux:text class="mb-2 text-xs font-medium text-zinc-500">{{ __('Price Trend') }}</flux:text>
                                    @php
                                        $checks = $url->priceChecks->reverse();
                                        $maxPrice = $checks->max('price_cents');
                                        $minPrice = $checks->min('price_cents');
                                        $range = $maxPrice - $minPrice;
                                        $isFlat = $range === 0;
                                    @endphp
                                    <div class="flex items-end gap-0.5 rounded-lg bg-zinc-50 p-2 dark:bg-zinc-800/50" style="height: 80px;">
                                        @foreach ($checks as $check)
                                            @php
                                                $heightPercent = $isFlat ? 50 : (($check->price_cents - $minPrice) / $range) * 70 + 25;
                                            @endphp
                                            <div class="flex-1 rounded-sm {{ $isFlat ? 'bg-zinc-300 dark:bg-zinc-600' : 'bg-brand-500/70 hover:bg-brand-600 dark:bg-brand-400/60 dark:hover:bg-brand-400' }} transition-all"
                                                 style="height: {{ $heightPercent }}%"
                                                 title="${{ $check->formattedPrice() }} - {{ $check->checked_at->format('M d, H:i') }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-1 flex justify-between">
                                        <flux:text class="text-xs text-zinc-400">
                                            {{ $checks->first()?->checked_at->format('M d') }}
                                        </flux:text>
                                        <flux:text class="text-xs text-zinc-400">
                                            {{ $checks->last()?->checked_at->format('M d') }}
                                        </flux:text>
                                    </div>
                                </div>

                                {{-- Price history list --}}
                                <div class="max-h-64 space-y-1 overflow-y-auto scroll-smooth">
                                    @foreach ($url->priceChecks as $index => $check)
                                        <div class="flex items-center justify-between text-sm">
                                            <flux:text class="text-xs">{{ $check->checked_at->format('M d, Y h:i A') }}</flux:text>
                                            <div class="flex items-center gap-2">
                                                @if ($index < $url->priceChecks->count() - 1)
                                                    @php $prev = $url->priceChecks[$index + 1]; @endphp
                                                    @if ($check->price_cents < $prev->price_cents)
                                                        <span class="text-xs text-green-600 dark:text-green-400">
                                                            -{{ round(abs($check->price_cents - $prev->price_cents) / $prev->price_cents * 100, 1) }}%
                                                        </span>
                                                    @elseif ($check->price_cents > $prev->price_cents)
                                                        <span class="text-xs text-red-600 dark:text-red-400">
                                                            +{{ round(abs($check->price_cents - $prev->price_cents) / $prev->price_cents * 100, 1) }}%
                                                        </span>
                                                    @endif
                                                @endif
                                                <flux:text class="font-mono">${{ $check->formattedPrice() }}</flux:text>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <flux:text class="text-sm text-zinc-400">{{ __('No price checks recorded yet.') }}</flux:text>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Delete modals --}}
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
</div>
