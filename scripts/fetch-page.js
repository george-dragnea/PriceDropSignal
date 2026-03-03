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
        headless: true,
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
        headless: true,
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
