import { chromium } from 'playwright';

const url = process.argv[2];

if (!url) {
    process.stderr.write('Usage: node fetch-page.js <url>\n');
    process.exit(1);
}

process.stderr.write(`[fetch-page] Starting browser for: ${url}\n`);

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
    locale: 'en-US',
    viewport: { width: 1920, height: 1080 },
});

const page = await context.newPage();

try {
    const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });

    const status = response ? response.status() : 'no response';
    process.stderr.write(`[fetch-page] HTTP status: ${status}\n`);
    process.stderr.write(`[fetch-page] Final URL: ${page.url()}\n`);

    // Wait a bit for dynamic pricing to render
    await page.waitForTimeout(2000);

    const html = await page.content();
    process.stderr.write(`[fetch-page] HTML length: ${html.length}\n`);
    process.stderr.write(`[fetch-page] Title: ${await page.title()}\n`);

    process.stdout.write(html);
} catch (err) {
    process.stderr.write(`[fetch-page] Error: ${err.message}\n`);
    process.exit(2);
} finally {
    await browser.close();
}
