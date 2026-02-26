<div
    x-data="{
        show: false,
        init() {
            const consent = localStorage.getItem('cookie_consent');
            if (consent === null) {
                this.show = true;
            } else if (consent === 'accepted') {
                this.loadAnalytics();
            }
        },
        accept() {
            localStorage.setItem('cookie_consent', 'accepted');
            this.show = false;
            this.loadAnalytics();
        },
        decline() {
            localStorage.setItem('cookie_consent', 'declined');
            this.show = false;
        },
        loadAnalytics() {
            if (document.getElementById('gtag-script')) return;
            const script = document.createElement('script');
            script.id = 'gtag-script';
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=G-3SYC9TS8ZY';
            document.head.appendChild(script);
            window.dataLayer = window.dataLayer || [];
            function gtag(){ dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', 'G-3SYC9TS8ZY');
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    x-cloak
    class="fixed inset-x-0 bottom-0 z-[9999] p-4 sm:p-6"
>
    <div class="mx-auto max-w-4xl rounded-xl border border-zinc-200 bg-white/95 p-4 shadow-lg backdrop-blur-sm dark:border-zinc-700 dark:bg-zinc-900/95 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-zinc-600 dark:text-zinc-300">
                <p>
                    We use cookies to keep the site running smoothly and to help us improve your experience.
                    <a href="{{ route('legal.cookies') }}" class="font-medium text-brand-600 underline hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300" wire:navigate>
                        Learn more
                    </a>
                </p>
            </div>
            <div class="flex shrink-0 gap-3">
                <button
                    x-on:click="decline()"
                    class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800"
                >
                    Decline
                </button>
                <button
                    x-on:click="accept()"
                    class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-700"
                >
                    Accept
                </button>
            </div>
        </div>
    </div>
</div>
