<?php

use App\Services\PriceExtractor;

beforeEach(function () {
    $this->extractor = new PriceExtractor;
});

test('extracts price from JSON-LD structured data', function () {
    $html = '<html><head><script type="application/ld+json">{"@type":"Product","name":"Test","offers":{"@type":"Offer","price":"29.99","priceCurrency":"USD"}}</script></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(2999);
});

test('extracts price from JSON-LD with lowPrice', function () {
    $html = '<html><head><script type="application/ld+json">{"@type":"Product","offers":{"@type":"AggregateOffer","lowPrice":"19.50"}}</script></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(1950);
});

test('extracts price from JSON-LD @graph', function () {
    $html = '<html><head><script type="application/ld+json">{"@graph":[{"@type":"Product","offers":{"price":"49.99"}}]}</script></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(4999);
});

test('extracts price from JSON-LD array of offers', function () {
    $html = '<html><head><script type="application/ld+json">{"@type":"Product","offers":[{"@type":"Offer","price":"15.00"},{"@type":"Offer","price":"20.00"}]}</script></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(1500);
});

test('extracts price from Open Graph meta tags', function () {
    $html = '<html><head><meta property="og:price:amount" content="39.99"></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(3999);
});

test('extracts price from product meta tags', function () {
    $html = '<html><head><meta property="product:price:amount" content="59.99"></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(5999);
});

test('extracts price from itemprop meta tag', function () {
    $html = '<html><head><meta itemprop="price" content="24.99"></head><body></body></html>';

    expect($this->extractor->extract($html))->toBe(2499);
});

test('extracts price from HTML itemprop element', function () {
    $html = '<html><body><span itemprop="price">99.99</span></body></html>';

    expect($this->extractor->extract($html))->toBe(9999);
});

test('extracts price from HTML class pattern', function () {
    $html = '<html><body><span class="product-price">$149.99</span></body></html>';

    expect($this->extractor->extract($html))->toBe(14999);
});

test('returns null when no price found', function () {
    $html = '<html><body><p>No pricing information here</p></body></html>';

    expect($this->extractor->extract($html))->toBeNull();
});

test('returns null for empty HTML', function () {
    expect($this->extractor->extract(''))->toBeNull();
});

test('correctly converts price with commas to cents', function () {
    expect($this->extractor->parsePriceToCents('1,299.99'))->toBe(129999);
});

test('correctly converts European format price', function () {
    expect($this->extractor->parsePriceToCents('1.299,99'))->toBe(129999);
});

test('correctly converts simple price', function () {
    expect($this->extractor->parsePriceToCents('29.99'))->toBe(2999);
});

test('strips currency symbols', function () {
    expect($this->extractor->parsePriceToCents('$29.99'))->toBe(2999);
    expect($this->extractor->parsePriceToCents('€29.99'))->toBe(2999);
    expect($this->extractor->parsePriceToCents('£29.99'))->toBe(2999);
});

test('handles price without decimals', function () {
    expect($this->extractor->parsePriceToCents('50'))->toBe(5000);
});

test('returns null for empty price string', function () {
    expect($this->extractor->parsePriceToCents(''))->toBeNull();
});

test('returns null for non-numeric string', function () {
    expect($this->extractor->parsePriceToCents('free'))->toBeNull();
});
