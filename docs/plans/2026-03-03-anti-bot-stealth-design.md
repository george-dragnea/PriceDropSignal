# Anti-Bot Stealth Improvements Design

**Date:** 2026-03-03
**Status:** Approved
**Approach:** A — Quick Stealth Fixes (free, self-hosted)

## Context

PriceDropSignal monitors <50 product URLs 1-2x daily across Romanian and international e-commerce sites. The current `fetch-page.js` script has basic stealth (UA rotation, some JS injections) but suffers a 10-30% failure rate due to CAPTCHA/bot detection.

## Current Gaps

1. `navigator.webdriver` is never deleted (biggest detection signal)
2. Firefox/Edge UAs used with Chromium engine (UA/engine mismatch)
3. Persistent browser profile directory exists but isn't used
4. No `playwright-extra` stealth plugin (manual injections are incomplete)
5. No human-like mouse/scroll behavior
6. Classic `headless: true` (easily detectable)
7. No WebGL/Canvas fingerprint randomization
8. No session/cookie persistence between runs
9. No `Sec-CH-UA` client hints header matching

## Design

### 1. Stealth Plugin Integration

Replace `playwright` with `playwright-extra` + `puppeteer-extra-plugin-stealth`.

The stealth plugin handles 13+ evasion modules automatically:
- `navigator.webdriver` deletion
- `chrome.app`, `chrome.csi`, `chrome.loadTimes`, `chrome.runtime`
- `iframe.contentWindow`
- `media.codecs`
- `navigator.hardwareConcurrency`, `languages`, `permissions`, `plugins`
- `sourceurl`
- `webgl.vendor`
- `window.outerdimensions`

Remove all manual `addInitScript` injections — the plugin is more comprehensive and maintained.

### 2. User-Agent Consistency

Remove Firefox and Edge UAs. Keep Chrome-only UAs matching the Chromium engine:
- Chrome 131-133 on Windows (3 UAs)
- Chrome 131-133 on Mac (3 UAs)
- Chrome 131-133 on Linux (3 UAs)

Match timezone to claimed OS:
- Windows → `America/New_York` or `Europe/London`
- Mac → `America/Los_Angeles` or `America/New_York`
- Linux → `America/Chicago` or `Europe/Berlin`

### 3. Persistent Browser Profile

Use `launchPersistentContext(profileDir, options)` instead of `launch()` + `newContext()`:
- Profile directory: `storage/app/private/browser-profile`
- Preserves cookies, localStorage, sessionStorage, browsing history
- Makes the browser look like a returning user

API change: `launchPersistentContext` returns a context directly.

### 4. Human-Like Behavior Simulation

Add realistic behavior between page load and content extraction:

```
navigate → wait for load → random delay (500-1500ms)
→ mouse move to random position → small pause (200-500ms)
→ scroll down 30-70% of page → small pause (200-500ms)
→ mouse move again → wait for price selector
→ grab HTML
```

Adds ~2-3 seconds per fetch, well within the 45-second timeout.

### 5. New Headless Mode + Launch Args

- `headless: 'new'` — Chromium's new headless (full Chrome, no window)
- `--enable-webgl` — enables WebGL
- `--use-gl=swiftshader` — software GPU rendering
- `--enable-accelerated-2d-canvas` — matches real Chrome
- `--disable-dev-shm-usage` — prevents crashes in constrained environments

### 6. Sec-CH-UA Header Matching

Set `extraHTTPHeaders` on the browser context to match the claimed Chrome version:
- `Sec-CH-UA`: matches Chrome version from UA string
- `Sec-CH-UA-Mobile`: `?0`
- `Sec-CH-UA-Platform`: matches OS from UA string

### 7. PHP-Side Improvements (PageFetcher)

**Better retry:** Add a 3-5 second random delay before retry attempt. Simulates a real user who hit a block, waited, then retried.

**Specific CAPTCHA provider logging:** Map detection markers to provider names (DataDome, reCAPTCHA, hCaptcha, Cloudflare, PerimeterX, Arkose) and log the specific provider.

### 8. Test Updates

Update `PageFetcherTest.php` to cover:
- New retry delay behavior
- CAPTCHA provider-specific logging

## Files Changed

| File | Change |
|------|--------|
| `package.json` | Replace `playwright` with `playwright-extra`, add stealth plugin |
| `scripts/fetch-page.js` | Full rewrite with stealth plugin, persistent profile, behavior simulation |
| `app/Services/PageFetcher.php` | Better retry + CAPTCHA provider logging |
| `tests/Feature/Services/PageFetcherTest.php` | Update tests for new behavior |

## Research Sources

- [ScrapeOps: Make Playwright Undetectable](https://scrapeops.io/playwright-web-scraping-playbook/nodejs-playwright-make-playwright-undetectable/)
- [Bright Data: Avoid Bot Detection with Playwright Stealth](https://brightdata.com/blog/how-tos/avoid-bot-detection-with-playwright-stealth)
- [ZenRows: Playwright Fingerprinting](https://www.zenrows.com/blog/playwright-fingerprint)
- [Oxylabs: Bypass CAPTCHA with Playwright](https://oxylabs.io/blog/playwright-bypass-captcha)
- [ScrapingAnt: Proxy Strategy 2025](https://scrapingant.com/blog/proxy-strategy-in-2025-beating-anti-bot-systems-without)
