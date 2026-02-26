const { chromium } = require('playwright');

const url = process.argv[2];

if (!url) {
    process.stderr.write('Usage: node fetch-page.js <url>\n');
    process.exit(1);
}

(async () => {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
        locale: 'en-US',
        viewport: { width: 1920, height: 1080 },
    });

    const page = await context.newPage();

    try {
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
        // Wait a bit for dynamic pricing to render
        await page.waitForTimeout(2000);
        const html = await page.content();
        process.stdout.write(html);
    } catch (err) {
        process.stderr.write(err.message + '\n');
        process.exit(2);
    } finally {
        await browser.close();
    }
})();
