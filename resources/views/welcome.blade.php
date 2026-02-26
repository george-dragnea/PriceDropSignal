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

        {{-- Hero Section --}}
        <section class="relative overflow-hidden bg-gradient-to-b from-brand-50 to-white dark:from-brand-950/20 dark:to-zinc-950">
            <div class="mx-auto max-w-6xl px-6 py-20 lg:py-28">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex items-center rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700 dark:bg-brand-900/40 dark:text-brand-300">
                        Free to use
                    </span>

                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-5xl lg:text-6xl">
                        Never Miss a
                        <span class="text-brand-500">Price Drop</span>
                        Again
                    </h1>

                    <p class="mt-6 text-lg leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-xl">
                        Track prices across any online store. Get instant alerts when prices
                        fall. Save money on the products you love &mdash; automatically.
                    </p>

                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-8 py-3 text-base font-semibold text-white shadow-sm transition-colors hover:bg-brand-700"
                               wire:navigate>
                                Start Tracking Prices
                            </a>
                        @endif
                        <a href="#how-it-works"
                           class="inline-flex items-center justify-center rounded-lg border border-zinc-300 px-8 py-3 text-base font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-900">
                            See How It Works
                        </a>
                    </div>

                    <p class="mt-8 text-sm text-zinc-500 dark:text-zinc-500">
                        Trusted by smart shoppers. Track unlimited products.
                    </p>
                </div>
            </div>

            {{-- Decorative glow --}}
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-full overflow-hidden">
                <div class="absolute left-1/2 top-0 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand-400/10 blur-3xl"></div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="py-16 lg:py-24">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                        Everything You Need to Save Money
                    </h2>
                    <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                        Powerful price tracking tools, designed to be simple.
                    </p>
                </div>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Feature: Price Tracking --}}
                    <div class="rounded-xl border border-zinc-200 p-6 transition-colors hover:border-brand-300 dark:border-zinc-800 dark:hover:border-brand-700">
                        <div class="flex size-14 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-brand-600 dark:text-brand-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Price Tracking</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            Monitor prices from any online store. Just paste a URL and we handle the rest.
                        </p>
                    </div>

                    {{-- Feature: Instant Alerts --}}
                    <div class="rounded-xl border border-zinc-200 p-6 transition-colors hover:border-drop-300 dark:border-zinc-800 dark:hover:border-orange-700">
                        <div class="flex size-14 items-center justify-center rounded-xl bg-drop-100 dark:bg-orange-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-drop-600 dark:text-drop-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Instant Alerts</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            Get notified by email the moment a price drops. Never miss a deal again.
                        </p>
                    </div>

                    {{-- Feature: Multi-Store --}}
                    <div class="rounded-xl border border-zinc-200 p-6 transition-colors hover:border-blue-300 dark:border-zinc-800 dark:hover:border-blue-700">
                        <div class="flex size-14 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-blue-600 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Multi-Store</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            Works with any online retailer. Compare prices across different stores.
                        </p>
                    </div>

                    {{-- Feature: Price History --}}
                    <div class="rounded-xl border border-zinc-200 p-6 transition-colors hover:border-violet-300 dark:border-zinc-800 dark:hover:border-violet-700">
                        <div class="flex size-14 items-center justify-center rounded-xl bg-violet-100 dark:bg-violet-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-violet-600 dark:text-violet-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Price History</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            See how prices change over time with visual charts. Buy at the perfect moment.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- How It Works --}}
        <section id="how-it-works" class="bg-zinc-50 py-16 dark:bg-zinc-900/50 lg:py-24">
            <div class="mx-auto max-w-6xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">
                        How It Works
                    </h2>
                    <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                        Start saving money in three simple steps.
                    </p>
                </div>

                <div class="mt-16 grid gap-12 sm:grid-cols-3">
                    <div class="text-center">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-brand-500 text-2xl font-bold text-white">
                            1
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">Add a Product</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            Create a product and paste the URL from any online store. It takes seconds.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-brand-500 text-2xl font-bold text-white">
                            2
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">We Track Prices</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            Our system automatically checks prices and records every change over time.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-brand-500 text-2xl font-bold text-white">
                            3
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-zinc-900 dark:text-white">Get Notified</h3>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            When the price drops, you get an instant email notification. Buy at the perfect time.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Social Proof / Stats --}}
        <section class="py-16 lg:py-24">
            <div class="mx-auto max-w-6xl px-6">
                <div class="grid gap-8 text-center sm:grid-cols-3">
                    <div>
                        <div class="text-4xl font-bold text-brand-500">24/7</div>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Price Monitoring</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-brand-500">Any Store</div>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Works with any online retailer</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-brand-500">100%</div>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Free to use</p>
                    </div>
                </div>

                <div class="mt-16 rounded-2xl border border-brand-200 bg-brand-50 p-8 text-center dark:border-brand-800 dark:bg-brand-950/20 sm:p-12">
                    <x-app-logo-icon class="mx-auto size-10 rounded-md" />
                    <blockquote class="mt-4 text-xl font-medium text-zinc-800 dark:text-zinc-200 sm:text-2xl">
                        &ldquo;Stop paying full price. Let PriceDropSignal watch the prices so you don&rsquo;t have to.&rdquo;
                    </blockquote>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="bg-brand-600 py-16 dark:bg-brand-700 lg:py-24">
            <div class="mx-auto max-w-3xl px-6 text-center">
                <h2 class="text-3xl font-bold text-white sm:text-4xl">
                    Ready to Start Saving?
                </h2>
                <p class="mt-4 text-lg text-brand-100">
                    Join PriceDropSignal today and never overpay for anything online again.
                </p>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-white px-8 py-3 text-base font-semibold text-brand-700 shadow-sm transition-colors hover:bg-brand-50"
                           wire:navigate>
                            Create Free Account
                        </a>
                    @endif
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-white/30 px-8 py-3 text-base font-semibold text-white transition-colors hover:bg-white/10"
                           wire:navigate>
                            Go to Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </section>

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
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="transition-colors hover:text-zinc-900 dark:hover:text-white" wire:navigate>Log in</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="transition-colors hover:text-zinc-900 dark:hover:text-white" wire:navigate>Register</a>
                        @endif
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
