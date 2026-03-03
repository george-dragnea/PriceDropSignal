# Anti-Bot Stealth Improvements Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Reduce CAPTCHA/bot detection failure rate from 10-30% by integrating playwright-extra stealth plugin, persistent browser profiles, human-like behavior simulation, and UA/header consistency fixes.

**Architecture:** Replace the plain `playwright` import in `scripts/fetch-page.js` with `playwright-extra` + stealth plugin for comprehensive evasion. Add persistent browser context, human-like page interaction, and improved retry/logging in the PHP `PageFetcher`. All changes are in 2 main files + their tests.

**Tech Stack:** Node.js (ESM), playwright-extra, puppeteer-extra-plugin-stealth, PHP 8.3/Laravel 12, Pest 4

---

### Task 1: Install npm Dependencies

**Files:**
- Modify: `package.json`

**Step 1: Install playwright-extra and stealth plugin alongside existing playwright**

Run:
```bash
cd /Users/georgedragnea/work/pricedropsignal
npm install playwright-extra puppeteer-extra-plugin-stealth
```

Expected: Both packages added to `dependencies` in `package.json`.

**Step 2: Verify installation**

Run:
```bash
node -e "import('playwright-extra').then(m => console.log('playwright-extra OK')); import('puppeteer-extra-plugin-stealth').then(m => console.log('stealth OK'));"
```

Expected: Both print "OK" without errors.

**Step 3: Commit**

```bash
git add package.json package-lock.json
git commit -m "Add playwright-extra and stealth plugin dependencies"
```

---

### Task 2: Rewrite fetch-page.js with Stealth Plugin

**Files:**
- Modify: `scripts/fetch-page.js`

**Step 1: Rewrite the complete script**

Replace the entire contents of `scripts/fetch-page.js` with:

```js
import { chromium } from 'playwright-extra';
import StealthPlugin from 'puppeteer-extra-plugin-stealth';
import { resolve } from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

chromium.use(StealthPlugin());

const url = process.argv[2];
const waitUntil = process.argv[3] || 'load';

if (!url) {
    process.stderr.write('Usage: node fetch-page.js <url> [load|networkidle]\n');
    process.exit(1);
}

function randomDelay(min, max) {
    return new Promise(resolve =>
        setTimeout(resolve, min + Math.random() * (max - min))
    );
}

function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Chrome-only user agents (must match Chromium engine).
// Last updated: 2026-03-03
const profiles = [
    // Chrome on Windows
    { ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', platform: 'Windows', tz: 'America/New_York', chVersion: '"Chromium";v="131", "Google Chrome";v="131", "Not_A Brand";v="24"', chPlatform: '"Windows"' },
    { ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', platform: 'Windows', tz: 'Europe/London', chVersion: '"Chromium";v="132", "Google Chrome";v="132", "Not_A Brand";v="24"', chPlatform: '"Windows"' },
    { ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', platform: 'Windows', tz: 'America/Chicago', chVersion: '"Chromium";v="133", "Google Chrome";v="133", "Not_A Brand";v="24"', chPlatform: '"Windows"' },
    // Chrome on Mac
    { ua: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', platform: 'Mac', tz: 'America/Los_Angeles', chVersion: '"Chromium";v="131", "Google Chrome";v="131", "Not_A Brand";v="24"', chPlatform: '"macOS"' },
    { ua: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', platform: 'Mac', tz: 'America/New_York', chVersion: '"Chromium";v="132", "Google Chrome";v="132", "Not_A Brand";v="24"', chPlatform: '"macOS"' },
    { ua: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', platform: 'Mac', tz: 'America/New_York', chVersion: '"Chromium";v="133", "Google Chrome";v="133", "Not_A Brand";v="24"', chPlatform: '"macOS"' },
    // Chrome on Linux
    { ua: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', platform: 'Linux', tz: 'America/Chicago', chVersion: '"Chromium";v="131", "Google Chrome";v="131", "Not_A Brand";v="24"', chPlatform: '"Linux"' },
    { ua: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', platform: 'Linux', tz: 'Europe/Berlin', chVersion: '"Chromium";v="132", "Google Chrome";v="132", "Not_A Brand";v="24"', chPlatform: '"Linux"' },
    { ua: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', platform: 'Linux', tz: 'Europe/Berlin', chVersion: '"Chromium";v="133", "Google Chrome";v="133", "Not_A Brand";v="24"', chPlatform: '"Linux"' },
];

const profile = profiles[Math.floor(Math.random() * profiles.length)];

const profileDir = resolve(__dirname, '..', 'storage', 'app', 'private', 'browser-profile');

let context;
try {
    context = await chromium.launchPersistentContext(profileDir, {
        headless: 'new',
        args: [
            '--disable-blink-features=AutomationControlled',
            '--no-first-run',
            '--no-default-browser-check',
            '--disable-component-update',
            '--enable-webgl',
            '--use-gl=swiftshader',
            '--enable-accelerated-2d-canvas',
            '--disable-dev-shm-usage',
        ],
        userAgent: profile.ua,
        locale: 'en-US',
        viewport: { width: 1920, height: 1080 },
        screen: { width: 1920, height: 1080 },
        timezoneId: profile.tz,
        colorScheme: 'light',
        extraHTTPHeaders: {
            'Sec-CH-UA': profile.chVersion,
            'Sec-CH-UA-Mobile': '?0',
            'Sec-CH-UA-Platform': profile.chPlatform,
        },
    });
} catch {
    // Fallback: if launchPersistentContext fails with playwright-extra,
    // use standard launch + newContext.
    const browser = await chromium.launch({
        headless: 'new',
        args: [
            '--disable-blink-features=AutomationControlled',
            '--no-first-run',
            '--no-default-browser-check',
            '--disable-component-update',
            '--enable-webgl',
            '--use-gl=swiftshader',
            '--enable-accelerated-2d-canvas',
            '--disable-dev-shm-usage',
        ],
    });
    context = await browser.newContext({
        userAgent: profile.ua,
        locale: 'en-US',
        viewport: { width: 1920, height: 1080 },
        screen: { width: 1920, height: 1080 },
        timezoneId: profile.tz,
        colorScheme: 'light',
        extraHTTPHeaders: {
            'Sec-CH-UA': profile.chVersion,
            'Sec-CH-UA-Mobile': '?0',
            'Sec-CH-UA-Platform': profile.chPlatform,
        },
    });
    context._browser = browser;
}

const page = context.pages()[0] || await context.newPage();

try {
    // Pre-navigation delay (simulate user thinking/typing URL)
    await randomDelay(300, 900);

    await page.goto(url, { waitUntil, timeout: 30000 });

    // --- Human-like behavior simulation ---

    // 1. Random mouse movement (simulate looking at page)
    await page.mouse.move(randomInt(100, 800), randomInt(100, 400));
    await randomDelay(300, 700);

    // 2. Scroll down 30-70% of the page
    const scrollPercent = 0.3 + Math.random() * 0.4;
    await page.evaluate((pct) => {
        window.scrollBy({ top: document.body.scrollHeight * pct, behavior: 'smooth' });
    }, scrollPercent);
    await randomDelay(400, 900);

    // 3. Another mouse movement
    await page.mouse.move(randomInt(200, 1200), randomInt(200, 600));
    await randomDelay(200, 500);

    // Wait for a price element to appear (non-blocking)
    await page.waitForSelector(
        '[itemprop="price"], .product-new-price, .price, [class*="price"]',
        { timeout: 5000 }
    ).catch(() => {});

    await randomDelay(300, 800);
    const html = await page.content();
    process.stdout.write(html);
} catch (err) {
    process.stderr.write(err.message + '\n');
    process.exit(2);
} finally {
    await context.close();
    if (context._browser) {
        await context._browser.close();
    }
}
```

**Step 2: Smoke-test the script manually**

Run:
```bash
cd /Users/georgedragnea/work/pricedropsignal
node scripts/fetch-page.js "https://bot.sannysoft.com" | head -c 500
```

Expected: HTML output (not an error). The page should load successfully.

**Step 3: Commit**

```bash
git add scripts/fetch-page.js
git commit -m "Rewrite fetch-page.js with stealth plugin, persistent profiles, and human-like behavior"
```

---

### Task 3: Update PageFetcher PHP Service

**Files:**
- Modify: `app/Services/PageFetcher.php`

**Step 1: Write the failing test for CAPTCHA provider logging**

Add this test to `tests/Feature/Services/PageFetcherTest.php`:

```php
test('logs specific captcha provider when detected', function () {
    Log::spy();
    $captchaHtml = '<html><body><script src="https://ct.captcha-delivery.com/i.js"></script></body></html>';

    Process::fake([
        '*' => Process::result(output: $captchaHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
        return $message === 'PageFetcher blocked by captcha'
            && $context['provider'] === 'DataDome';
    })->atLeast()->once();
});

test('logs cloudflare as captcha provider', function () {
    Log::spy();
    $captchaHtml = '<html><body><div class="cf-turnstile"></div></body></html>';

    Process::fake([
        '*' => Process::result(output: $captchaHtml),
    ]);

    $fetcher = new PageFetcher;
    $result = $fetcher->fetch('https://example.com/product');

    Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
        return $message === 'PageFetcher blocked by captcha'
            && $context['provider'] === 'Cloudflare';
    })->atLeast()->once();
});
```

**Step 2: Run tests to verify they fail**

Run:
```bash
php artisan test --compact --filter="logs specific captcha provider"
php artisan test --compact --filter="logs cloudflare as captcha provider"
```

Expected: FAIL (the current code doesn't log `provider`).

**Step 3: Update PageFetcher.php**

Replace the full contents of `app/Services/PageFetcher.php` with:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class PageFetcher
{
    /** @var array<string, string> */
    private const CAPTCHA_MARKERS = [
        'captcha-delivery.com' => 'DataDome',
        'g-recaptcha' => 'reCAPTCHA',
        'hcaptcha.com' => 'hCaptcha',
        'challenges.cloudflare.com' => 'Cloudflare',
        'cf-turnstile' => 'Cloudflare',
        'arkoselabs.com' => 'Arkose',
        'funcaptcha' => 'Arkose',
        'perimeterx.net' => 'PerimeterX',
        'px-captcha' => 'PerimeterX',
    ];

    /**
     * Fetch the HTML content of a URL using a headless browser.
     *
     * @return array{html: string|null, error: string|null}
     */
    public function fetch(string $url): array
    {
        $result = $this->attemptFetch($url);

        if ($result['error'] === 'Captcha bot protection' || $result['error'] === 'Bot detection (page too small)') {
            Log::info('PageFetcher retrying with networkidle', ['url' => $url, 'reason' => $result['error']]);

            // Random delay before retry — simulates a user who hit a block, waited, then retried.
            usleep(random_int(3_000_000, 5_000_000));

            $result = $this->attemptFetch($url, 'networkidle');
        }

        return $result;
    }

    /**
     * @return array{html: string|null, error: string|null}
     */
    private function attemptFetch(string $url, string $waitUntil = 'load'): array
    {
        $script = base_path('scripts/fetch-page.js');

        $result = Process::timeout(45)->run(['node', $script, $url, $waitUntil]);

        if ($result->successful()) {
            $html = $result->output();

            $captchaProvider = $this->detectCaptchaProvider($html);
            if ($captchaProvider !== null) {
                Log::warning('PageFetcher blocked by captcha', [
                    'url' => $url,
                    'wait_until' => $waitUntil,
                    'provider' => $captchaProvider,
                ]);

                return ['html' => null, 'error' => 'Captcha bot protection'];
            }

            if ($this->isBotBlockPage($html)) {
                Log::warning('PageFetcher blocked by bot detection', ['url' => $url, 'html_length' => strlen($html)]);

                return ['html' => null, 'error' => 'Bot detection (page too small)'];
            }

            return ['html' => $html, 'error' => null];
        }

        $stderr = trim($result->errorOutput());
        $lines = array_values(array_filter(
            explode("\n", $stderr),
            fn ($line) => ! preg_match('/^Node\.js v[\d.]+/', trim($line)),
        ));
        $errorLine = end($lines) ?: 'Browser fetch failed';

        Log::warning('PageFetcher failed', ['url' => $url, 'error' => $errorLine]);

        return ['html' => null, 'error' => substr($errorLine, 0, 255)];
    }

    private function isBotBlockPage(string $html): bool
    {
        return strlen($html) < 10000;
    }

    /**
     * Detect the CAPTCHA provider from HTML content.
     * Returns the provider name if detected, null otherwise.
     */
    private function detectCaptchaProvider(string $html): ?string
    {
        // Real product pages are typically 50KB+; CAPTCHA block pages are tiny
        if (strlen($html) > 50000) {
            return null;
        }

        foreach (self::CAPTCHA_MARKERS as $marker => $provider) {
            if (stripos($html, $marker) !== false) {
                return $provider;
            }
        }

        return null;
    }
}
```

**Step 4: Run all PageFetcher tests**

Run:
```bash
php artisan test --compact tests/Feature/Services/PageFetcherTest.php
```

Expected: Some existing tests may fail because log assertions now expect `provider` in context. Fix the existing test for "detects captcha and retries with networkidle" — update its Log assertion to check for `provider`:

Update the test at line 30-33 in `PageFetcherTest.php`:

```php
Log::shouldHaveReceived('warning')->withArgs(function ($message, $context) {
    return $message === 'PageFetcher blocked by captcha'
        && $context['url'] === 'https://example.com/product'
        && $context['provider'] === 'DataDome';
})->twice();
```

Also update the "retries with networkidle on captcha and succeeds" test — the first attempt will log a warning with provider, so the test should still pass since we're not asserting on Log for that test.

**Step 5: Run all tests again**

Run:
```bash
php artisan test --compact tests/Feature/Services/PageFetcherTest.php
```

Expected: All PASS.

**Step 6: Run Pint**

Run:
```bash
vendor/bin/pint --dirty --format agent
```

Expected: Any formatting issues auto-fixed.

**Step 7: Commit**

```bash
git add app/Services/PageFetcher.php tests/Feature/Services/PageFetcherTest.php
git commit -m "Add CAPTCHA provider logging and retry delay to PageFetcher"
```

---

### Task 4: Verify End-to-End Against Bot Detection Test Sites

**Files:** None (manual testing)

**Step 1: Test against bot detection analysis site**

Run:
```bash
cd /Users/georgedragnea/work/pricedropsignal
node scripts/fetch-page.js "https://bot.sannysoft.com" > /tmp/bot-test.html && echo "SUCCESS: $(wc -c < /tmp/bot-test.html) bytes"
```

Expected: SUCCESS with a reasonably sized HTML file (50KB+).

**Step 2: Test against a real product URL**

Run with one of the user's actual product URLs (or a known e-commerce page):
```bash
node scripts/fetch-page.js "https://www.emag.ro" > /tmp/emag-test.html && echo "SUCCESS: $(wc -c < /tmp/emag-test.html) bytes"
```

Expected: SUCCESS with a large HTML file (not a CAPTCHA page).

**Step 3: Run the full PHP test suite**

Run:
```bash
php artisan test --compact
```

Expected: All tests PASS.

**Step 4: Final commit if any adjustments were needed**

```bash
git add -A
git commit -m "Final adjustments after end-to-end stealth testing"
```

---

## Notes for Implementer

- **ESM imports:** The project uses `"type": "module"` in package.json, so all imports use ESM syntax. `playwright-extra` supports ESM: `import { chromium } from 'playwright-extra'`.
- **launchPersistentContext fallback:** The script includes a try/catch fallback to `launch()` + `newContext()` in case `playwright-extra` doesn't support `launchPersistentContext`. If the fallback triggers, the persistent profile won't be used, but everything else still works.
- **Browser profile path:** `storage/app/private/browser-profile` already exists with a `Default/` subdirectory. The `launchPersistentContext` call will use this directory.
- **Test timing:** The `usleep()` in PageFetcher's retry logic will add 3-5 seconds to tests that trigger retries. Since we use `Process::fake()`, the actual browser isn't launched, but the sleep still runs. If this slows tests too much, we can mock `usleep` or extract the delay into a method.
- **Pint:** Run `vendor/bin/pint --dirty --format agent` after any PHP changes.
