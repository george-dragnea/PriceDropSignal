import { chromium } from 'playwright';

const url = process.argv[2];

if (!url) {
    process.stderr.write('Usage: node fetch-page.js <url>\n');
    process.exit(1);
}

function randomDelay(min, max) {
    return new Promise(resolve =>
        setTimeout(resolve, min + Math.random() * (max - min))
    );
}

// User agent pool — rotate per request to reduce fingerprinting.
// Last updated: 2026-02-28
const userAgents = [
    // Chrome on Windows
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
    // Chrome on Mac
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
    // Firefox on Windows
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:134.0) Gecko/20100101 Firefox/134.0',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0',
    // Edge on Windows
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0',
];

const selectedUserAgent = userAgents[Math.floor(Math.random() * userAgents.length)];
const isMac = selectedUserAgent.includes('Macintosh');

const browser = await chromium.launch({
    headless: true,
    args: [
        '--disable-blink-features=AutomationControlled',
        '--no-first-run',
        '--no-default-browser-check',
        '--disable-component-update',
    ],
});

const context = await browser.newContext({
    userAgent: selectedUserAgent,
    locale: 'en-US',
    viewport: { width: 1920, height: 1080 },
    timezoneId: isMac ? 'America/New_York' : 'Europe/Bucharest',
    colorScheme: 'light',
    screen: { width: 1920, height: 1080 },
});

await context.addInitScript(() => {
    // Fake window.chrome object (missing in headless Chromium)
    if (!window.chrome) {
        window.chrome = {
            runtime: {
                onMessage: { addListener() {}, removeListener() {} },
                sendMessage() {},
                connect() {},
            },
            loadTimes() { return {}; },
            csi() { return {}; },
        };
    }

    // Fake navigator.plugins (empty in headless, real Chrome has 3+)
    Object.defineProperty(navigator, 'plugins', {
        get: () => {
            const plugins = [
                { name: 'Chrome PDF Plugin', filename: 'internal-pdf-viewer', description: 'Portable Document Format', length: 1 },
                { name: 'Chrome PDF Viewer', filename: 'mhjfbmdgcfjbbpaeojofohoefgiehjai', description: '', length: 1 },
                { name: 'Native Client', filename: 'internal-nacl-plugin', description: '', length: 1 },
            ];
            plugins.refresh = () => {};
            plugins.item = (i) => plugins[i];
            plugins.namedItem = (name) => plugins.find(p => p.name === name);
            return plugins;
        },
    });

    // Consistent languages
    Object.defineProperty(navigator, 'languages', {
        get: () => ['en-US', 'en'],
    });

    // Realistic hardware
    Object.defineProperty(navigator, 'hardwareConcurrency', { get: () => 8 });
    Object.defineProperty(navigator, 'deviceMemory', { get: () => 8 });

    // Permissions API consistency fix
    const origQuery = navigator.permissions.query.bind(navigator.permissions);
    navigator.permissions.query = (params) => {
        if (params.name === 'notifications') {
            return Promise.resolve({ state: Notification.permission });
        }
        return origQuery(params);
    };
});

const page = await context.newPage();

try {
    await randomDelay(200, 800);
    await page.goto(url, { waitUntil: 'load', timeout: 30000 });

    // Wait for a price element to appear (non-blocking — proceed if not found)
    await page.waitForSelector(
        '[itemprop="price"], .product-new-price, .price, [class*="price"]',
        { timeout: 5000 }
    ).catch(() => {});

    await randomDelay(500, 1500);
    const html = await page.content();
    process.stdout.write(html);
} catch (err) {
    process.stderr.write(err.message + '\n');
    process.exit(2);
} finally {
    await context.close();
    await browser.close();
}
