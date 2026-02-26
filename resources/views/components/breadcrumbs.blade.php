@props(['items' => []])

<nav class="flex items-center gap-1.5 text-sm" aria-label="Breadcrumb">
    @foreach ($items as $item)
        @if (!$loop->last)
            <a href="{{ $item['href'] }}" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200" wire:navigate>
                {{ $item['label'] }}
            </a>
            <flux:icon name="chevron-right" variant="micro" class="size-3.5 text-zinc-400" />
        @else
            <span class="text-zinc-800 dark:text-zinc-200">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
