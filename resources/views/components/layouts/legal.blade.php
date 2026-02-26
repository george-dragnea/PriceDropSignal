<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">

        {{-- Navigation --}}
        <nav class="sticky top-0 z-50 border-b border-zinc-200 bg-white/80 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-app-logo-icon class="size-8 rounded-md" />
                    <span class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ config('app.name', 'PriceDropSignal') }}
                    </span>
                </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-700"
                               wire:navigate>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white"
                               wire:navigate>
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-700"
                                   wire:navigate>
                                    Get Started Free
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        {{-- Content --}}
        <main class="mx-auto max-w-4xl px-6 py-12 lg:py-16">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-200 bg-white py-12 dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                    <div class="flex items-center gap-2">
                        <x-app-logo-icon class="size-6 rounded-md" />
                        <span class="font-semibold text-zinc-900 dark:text-white">
                            {{ config('app.name', 'PriceDropSignal') }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <a href="{{ route('legal.terms') }}" class="transition-colors hover:text-zinc-900 dark:hover:text-white" wire:navigate>Terms</a>
                        <a href="{{ route('legal.privacy') }}" class="transition-colors hover:text-zinc-900 dark:hover:text-white" wire:navigate>Privacy</a>
                        <a href="{{ route('legal.cookies') }}" class="transition-colors hover:text-zinc-900 dark:hover:text-white" wire:navigate>Cookies</a>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        &copy; {{ date('Y') }} {{ config('app.name', 'PriceDropSignal') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
