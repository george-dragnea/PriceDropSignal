<?php

namespace App\Services;

class PriceExtractor
{
    /**
     * Extract a price from the given HTML content.
     * Returns price in cents, or null if extraction fails.
     */
    public function extract(string $html): ?int
    {
        // Strategy 1: JSON-LD structured data (most reliable)
        $price = $this->extractFromJsonLd($html);
        if ($price !== null) {
            return $price;
        }

        // Strategy 2: Open Graph / meta tags
        $price = $this->extractFromMetaTags($html);
        if ($price !== null) {
            return $price;
        }

        // Strategy 3: Common HTML price patterns
        return $this->extractFromHtmlPatterns($html);
    }

    protected function extractFromJsonLd(string $html): ?int
    {
        if (! preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $json) {
            $data = json_decode(trim($json), true);
            if (! is_array($data)) {
                continue;
            }

            $price = $this->findPriceInJsonLd($data);
            if ($price !== null) {
                return $price;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function findPriceInJsonLd(array $data): ?int
    {
        // Handle top-level arrays (e.g. [{"@type": "ProductGroup", ...}])
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as $item) {
                if (is_array($item)) {
                    $price = $this->findPriceInJsonLd($item);
                    if ($price !== null) {
                        return $price;
                    }
                }
            }

            return null;
        }

        // Handle @graph arrays
        if (isset($data['@graph']) && is_array($data['@graph'])) {
            foreach ($data['@graph'] as $item) {
                if (is_array($item)) {
                    $price = $this->findPriceInJsonLd($item);
                    if ($price !== null) {
                        return $price;
                    }
                }
            }
        }

        $type = $data['@type'] ?? '';

        // Direct Product type with offers
        if ($type === 'Product' && isset($data['offers'])) {
            return $this->extractPriceFromOffers($data['offers']);
        }

        // ProductGroup with hasVariant (e.g. epantofi.ro)
        if ($type === 'ProductGroup' && isset($data['hasVariant']) && is_array($data['hasVariant'])) {
            foreach ($data['hasVariant'] as $variant) {
                if (is_array($variant)) {
                    $price = $this->findPriceInJsonLd($variant);
                    if ($price !== null) {
                        return $price;
                    }
                }
            }
        }

        // Direct Offer type
        if (in_array($type, ['Offer', 'AggregateOffer'])) {
            return $this->parsePriceToCents((string) ($data['price'] ?? $data['lowPrice'] ?? ''));
        }

        return null;
    }

    /**
     * @param  array<string, mixed>|list<array<string, mixed>>  $offers
     */
    protected function extractPriceFromOffers(array $offers): ?int
    {
        // Single offer object
        if (isset($offers['price']) || isset($offers['lowPrice'])) {
            return $this->parsePriceToCents((string) ($offers['price'] ?? $offers['lowPrice']));
        }

        // Array of offers — take the first valid price
        foreach ($offers as $offer) {
            if (! is_array($offer)) {
                continue;
            }

            // Handle nested array of offers (e.g. [[{offer1}, {offer2}]])
            if (isset($offer[0]) && is_array($offer[0])) {
                return $this->extractPriceFromOffers($offer);
            }

            if (isset($offer['price']) || isset($offer['lowPrice'])) {
                $price = $this->parsePriceToCents((string) ($offer['price'] ?? $offer['lowPrice']));
                if ($price !== null) {
                    return $price;
                }
            }
        }

        return null;
    }

    protected function extractFromMetaTags(string $html): ?int
    {
        $patterns = [
            '/property=["\']og:price:amount["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/content=["\']([^"\']+)["\'][^>]*property=["\']og:price:amount["\']/i',
            '/property=["\']product:price:amount["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/content=["\']([^"\']+)["\'][^>]*property=["\']product:price:amount["\']/i',
            '/itemprop=["\']price["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/content=["\']([^"\']+)["\'][^>]*itemprop=["\']price["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $price = $this->parsePriceToCents($match[1]);
                if ($price !== null) {
                    return $price;
                }
            }
        }

        return null;
    }

    protected function extractFromHtmlPatterns(string $html): ?int
    {
        // Look for price-like patterns near common price indicators
        $patterns = [
            '/itemprop=["\']price["\'][^>]*>([^<]+)/i',
            '/class=["\'][^"\']*price[^"\']*["\'][^>]*>([^<]*[\d.,]+[^<]*)/i',
            '/id=["\'][^"\']*price[^"\']*["\'][^>]*>([^<]*[\d.,]+[^<]*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $price = $this->parsePriceToCents(trim($match[1]));
                if ($price !== null) {
                    return $price;
                }
            }
        }

        return null;
    }

    public function parsePriceToCents(string $priceString): ?int
    {
        // Strip currency symbols and whitespace
        $cleaned = preg_replace('/[^\d.,]/', '', trim($priceString));

        if ($cleaned === '' || $cleaned === null) {
            return null;
        }

        // Handle formats like "1,299.99" or "1.299,99"
        // Determine if comma or period is the decimal separator
        $lastComma = strrpos($cleaned, ',');
        $lastDot = strrpos($cleaned, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                // European format: 1.299,99
                $cleaned = str_replace('.', '', $cleaned);
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                // US format: 1,299.99
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif ($lastComma !== false) {
            // Could be "1,299" (thousands) or "29,99" (decimal)
            $afterComma = substr($cleaned, $lastComma + 1);
            if (strlen($afterComma) === 2) {
                // Likely decimal: 29,99
                $cleaned = str_replace(',', '.', $cleaned);
            } else {
                // Likely thousands: 1,299
                $cleaned = str_replace(',', '', $cleaned);
            }
        }

        if (! is_numeric($cleaned)) {
            return null;
        }

        $dollars = (float) $cleaned;

        if ($dollars <= 0) {
            return null;
        }

        return (int) round($dollars * 100);
    }
}
