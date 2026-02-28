import { chromium } from 'playwright';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';
import { mkdir } from 'fs/promises';

const __dirname = dirname(fileURLToPath(import.meta.url));

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

const userDataDir = join(__dirname, '..', 'storage', 'app', 'private', 'browser-profile');
await mkdir(userDataDir, { recursive: true });

const context = await chromium.launchPersistentContext(userDataDir, {
    headless: true,
    args: [
        '--disable-blink-features=AutomationControlled',
        '--no-first-run',
        '--no-default-browser-check',
        '--disable-component-update',
    ],
    userAgent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
    locale: 'en-US',
    viewport: { width: 1920, height: 1080 },
    timezoneId: 'America/New_York',
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

    // Realistic hardware for a Mac
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

const page = context.pages()[0] || await context.newPage();

try {
    await randomDelay(200, 800);
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
    // Wait for dynamic pricing to render (randomized to appear more natural)
    await randomDelay(1500, 3500);
    const html = await page.content();
    process.stdout.write(html);
} catch (err) {
    process.stderr.write(err.message + '\n');
    process.exit(2);
} finally {
    await context.close();
}
