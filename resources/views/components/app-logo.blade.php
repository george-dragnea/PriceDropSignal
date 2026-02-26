@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="PriceDropSignal" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center">
            <x-app-logo-icon class="size-8 rounded-md" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="PriceDropSignal" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center">
            <x-app-logo-icon class="size-8 rounded-md" />
        </x-slot>
    </flux:brand>
@endif
