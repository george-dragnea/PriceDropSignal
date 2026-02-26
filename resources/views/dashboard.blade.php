<x-layouts::app :title="__('Dashboard')">
    <div class="w-full max-w-4xl space-y-6">
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
                <flux:text class="text-sm">{{ __('Products Tracked') }}</flux:text>
                <flux:heading size="xl" class="mt-1">{{ auth()->user()->products()->count() }}</flux:heading>
            </div>
            <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
                <flux:text class="text-sm">{{ __('URLs Monitored') }}</flux:text>
                <flux:heading size="xl" class="mt-1">{{ auth()->user()->products()->withCount('urls')->get()->sum('urls_count') }}</flux:heading>
            </div>
        </div>

        <div class="flex">
            <flux:button variant="primary" icon="tag" :href="route('products.index')" wire:navigate>
                {{ __('Manage Products') }}
            </flux:button>
        </div>
    </div>
</x-layouts::app>
