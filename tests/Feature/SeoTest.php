<?php

use function Pest\Laravel\get;

it('has meta description on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('<meta name="description"', false);
});

it('has open graph tags on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false)
        ->assertSee('<meta property="og:url"', false)
        ->assertSee('<meta property="og:type"', false)
        ->assertSee('<meta property="og:site_name"', false)
        ->assertSee('<meta property="og:image"', false);
});

it('has twitter card tags on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('<meta name="twitter:card"', false)
        ->assertSee('<meta name="twitter:title"', false)
        ->assertSee('<meta name="twitter:description"', false);
});

it('has canonical url on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('<link rel="canonical"', false);
});

it('has json-ld structured data on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('application/ld+json', false)
        ->assertSee('"@type": "WebSite"', false)
        ->assertSee('"@type": "Organization"', false)
        ->assertSee('"@type": "SoftwareApplication"', false)
        ->assertSee('"@type": "FAQPage"', false);
});

it('has seo-friendly title on the homepage', function () {
    get('/')
        ->assertOk()
        ->assertSee('<title>PriceDropSignal - Free Price Tracker &amp; Drop Alerts</title>', false);
});

it('has meta description on legal pages', function () {
    get('/terms')->assertOk()->assertSee('<meta name="description"', false);
    get('/privacy')->assertOk()->assertSee('<meta name="description"', false);
    get('/cookies')->assertOk()->assertSee('<meta name="description"', false);
});

it('returns valid sitemap xml', function () {
    get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false)
        ->assertSee('<loc>', false);
});
