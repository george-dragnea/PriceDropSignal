@php
    $appName = config('app.name', 'PriceDropSignal');
    $pageTitle = filled($title ?? null)
        ? $title . ' - ' . $appName
        : $appName . ' - Free Price Tracker & Drop Alerts';
    $pageDescription = $description ?? 'Track prices across any online store and get instant email alerts when prices drop. Free price monitoring tool — never overpay again.';
    $pageUrl = $canonical ?? url()->current();
    $pageImage = $ogImage ?? asset('android-chrome-512x512.png');
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}" />
<link rel="canonical" href="{{ $pageUrl }}" />

@if (!empty($robots))
<meta name="robots" content="{{ $robots }}" />
@endif

{{-- Open Graph --}}
<meta property="og:title" content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDescription }}" />
<meta property="og:url" content="{{ $pageUrl }}" />
<meta property="og:type" content="{{ $ogType ?? 'website' }}" />
<meta property="og:site_name" content="{{ $appName }}" />
<meta property="og:image" content="{{ $pageImage }}" />
<meta property="og:locale" content="en_US" />

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDescription }}" />
<meta name="twitter:image" content="{{ $pageImage }}" />

{{-- Favicons --}}
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
