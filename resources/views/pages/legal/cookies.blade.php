<x-layouts.legal :title="'Cookies Policy'">

    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Cookies Policy</h1>
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Last updated: {{ date('F j, Y') }}</p>

    <div class="legal-prose mt-10">

        <h2>1. Introduction</h2>
        <p>
            This Cookies Policy explains what cookies and similar technologies are used by the PriceDropSignal service
            ("Service") operated by <strong>GD CLOUD COMPANY S.R.L.</strong> ("Company", "we", "us", or "our"). This
            policy should be read alongside our <a href="{{ route('legal.privacy') }}">Privacy Policy</a>.
        </p>

        <h2>2. What Are Cookies?</h2>
        <p>
            Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you visit a
            website. They are widely used to make websites work efficiently, provide a better user experience, and give
            information to the site operator.
        </p>

        <h2>3. Cookies We Use</h2>
        <p>
            PriceDropSignal uses only <strong>strictly necessary cookies</strong> that are essential for the Service to
            function. We do <strong>not</strong> use any analytics, advertising, or third-party tracking cookies.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Cookie Name</th>
                    <th>Purpose</th>
                    <th>Type</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>pricedropsignal_session</code></td>
                    <td>Maintains your authenticated session so you can navigate the Service without logging in on each page. The session data (session ID, IP address, user-agent) is stored on our server; only a session identifier is stored in this cookie.</td>
                    <td>Strictly necessary</td>
                    <td>2 hours (or until browser close)</td>
                </tr>
                <tr>
                    <td><code>XSRF-TOKEN</code></td>
                    <td>Protects against Cross-Site Request Forgery (CSRF) attacks. This is a security measure that ensures form submissions originate from our Service and not from a malicious third party.</td>
                    <td>Strictly necessary</td>
                    <td>2 hours</td>
                </tr>
                <tr>
                    <td><code>remember_web_*</code></td>
                    <td>Keeps you logged in between browser sessions if you select "Remember me" during login. Only set with your explicit action.</td>
                    <td>Strictly necessary (functional)</td>
                    <td>5 years (or until you log out)</td>
                </tr>
            </tbody>
        </table>

        <h2>4. Cookies We Do Not Use</h2>
        <p>For transparency, we confirm that we do <strong>not</strong> use:</p>
        <ul>
            <li><strong>Analytics cookies</strong> (e.g., Google Analytics, Matomo, Plausible);</li>
            <li><strong>Advertising or remarketing cookies</strong> (e.g., Google Ads, Facebook Pixel);</li>
            <li><strong>Social media cookies</strong> (e.g., Facebook, Twitter, LinkedIn widgets);</li>
            <li><strong>Third-party tracking cookies</strong> of any kind.</li>
        </ul>

        <h2>5. Third-Party Services</h2>
        <p>
            We use <strong>Bunny Fonts</strong> (bunny.net) to serve web fonts. Bunny Fonts is an EU-based,
            GDPR-compliant font delivery service that is specifically designed as a privacy-friendly alternative. It does
            not set cookies and does not track or log visitors.
        </p>

        <h2>6. Legal Basis</h2>
        <p>
            Under the ePrivacy Directive (Directive 2002/58/EC as amended by Directive 2009/136/EC) and its
            implementation in Romanian law (Law No. 506/2004), strictly necessary cookies do not require user consent.
            All cookies used by PriceDropSignal fall within this exemption because they are essential for the Service to
            function and are explicitly requested by you (e.g., logging in, submitting forms).
        </p>

        <h2>7. How to Manage Cookies</h2>
        <p>
            You can control and manage cookies through your browser settings. Most browsers allow you to:
        </p>
        <ul>
            <li>View which cookies are stored on your device;</li>
            <li>Delete individual or all cookies;</li>
            <li>Block cookies from specific or all websites;</li>
            <li>Set preferences for first-party vs. third-party cookies.</li>
        </ul>
        <p>
            Please note that disabling or blocking our strictly necessary cookies will prevent the Service from
            functioning correctly — you will not be able to log in or use any authenticated features.
        </p>
        <p>Instructions for managing cookies in common browsers:</p>
        <ul>
            <li><strong>Chrome:</strong> Settings &rarr; Privacy and security &rarr; Cookies and other site data</li>
            <li><strong>Firefox:</strong> Settings &rarr; Privacy &amp; Security &rarr; Cookies and Site Data</li>
            <li><strong>Safari:</strong> Preferences &rarr; Privacy &rarr; Manage Website Data</li>
            <li><strong>Edge:</strong> Settings &rarr; Cookies and site permissions &rarr; Manage and delete cookies</li>
        </ul>

        <h2>8. Changes to This Policy</h2>
        <p>
            We may update this Cookies Policy from time to time, for example if we introduce new features that require
            additional cookies. Any changes will be reflected on this page with an updated "Last updated" date. If we
            begin using non-essential cookies in the future, we will obtain your consent before setting them.
        </p>

        <h2>9. Contact</h2>
        <p>If you have any questions about our use of cookies, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
            <li>Address: GD CLOUD COMPANY S.R.L., Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
        </ul>

    </div>

</x-layouts.legal>
