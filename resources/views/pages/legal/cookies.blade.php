<x-layouts.legal :title="'Cookies Policy'" :description="'Understand how PriceDropSignal uses cookies. Learn about the types of cookies we use and how to manage your cookie preferences.'">

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

        <h2>3. Strictly Necessary Cookies</h2>
        <p>
            The following cookies are essential for the Service to function. They do not require your consent under the
            ePrivacy Directive because they are strictly necessary for providing the Service you have requested.
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

        <h2>4. Analytics Cookies (Consent Required)</h2>
        <p>
            We use <strong>Google Analytics</strong> to understand how visitors interact with the Service, which pages
            are most popular, and how the Service can be improved. These cookies are <strong>only set after you give
            explicit consent</strong> via our cookie consent banner. If you decline, no analytics cookies are set and
            no data is sent to Google.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Cookie Name</th>
                    <th>Purpose</th>
                    <th>Provider</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>_ga</code></td>
                    <td>Distinguishes unique visitors by assigning a randomly generated identifier. Used to calculate visitor, session, and campaign data for analytics reports.</td>
                    <td>Google Analytics</td>
                    <td>2 years</td>
                </tr>
                <tr>
                    <td><code>_ga_G-3SYC9TS8ZY</code></td>
                    <td>Maintains session state for Google Analytics 4. Records page views and interactions during your browsing session.</td>
                    <td>Google Analytics</td>
                    <td>2 years</td>
                </tr>
            </tbody>
        </table>

        <p>
            Google Analytics collects data such as pages visited, time spent on pages, browser type, and general
            geographic region (country/city level). IP anonymization is enabled, so your full IP address is not stored
            by Google. For more information, see
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Google's Privacy Policy</a>.
            You can also install the
            <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">Google Analytics Opt-out Browser Add-on</a>
            to prevent Google Analytics from collecting your data across all websites.
        </p>

        <h2>5. Cookies We Do Not Use</h2>
        <p>For transparency, we confirm that we do <strong>not</strong> use:</p>
        <ul>
            <li><strong>Advertising or remarketing cookies</strong> (e.g., Google Ads, Facebook Pixel);</li>
            <li><strong>Social media cookies</strong> (e.g., Facebook, Twitter, LinkedIn widgets);</li>
            <li><strong>Third-party tracking cookies</strong> beyond the analytics cookies described above.</li>
        </ul>

        <h2>6. Third-Party Services</h2>
        <p>
            We use <strong>Bunny Fonts</strong> (bunny.net) to serve web fonts. Bunny Fonts is an EU-based,
            GDPR-compliant font delivery service that is specifically designed as a privacy-friendly alternative. It does
            not set cookies and does not track or log visitors.
        </p>

        <h2>7. Legal Basis</h2>
        <p>
            Under the ePrivacy Directive (Directive 2002/58/EC as amended by Directive 2009/136/EC) and its
            implementation in Romanian law (Law No. 506/2004):
        </p>
        <ul>
            <li>
                <strong>Strictly necessary cookies</strong> (section 3) do not require user consent because they are
                essential for the Service to function and are explicitly requested by you (e.g., logging in, submitting
                forms).
            </li>
            <li>
                <strong>Analytics cookies</strong> (section 4) require your explicit consent under both the ePrivacy
                Directive and the GDPR (Art. 6(1)(a)). They are only loaded after you click "Accept" on our cookie
                consent banner. You may withdraw your consent at any time.
            </li>
        </ul>

        <h2>8. How to Manage Cookies</h2>
        <h3>Cookie Consent Banner</h3>
        <p>
            When you first visit the Service, a cookie consent banner appears at the bottom of the page. You can choose
            to <strong>Accept</strong> (enables analytics cookies) or <strong>Decline</strong> (no analytics cookies are
            set). Your choice is saved in your browser's local storage and the banner will not appear again.
        </p>
        <p>
            To change your preference, clear your browser's local storage for this site (or clear all site data in your
            browser settings), and the consent banner will appear again on your next visit.
        </p>

        <h3>Browser Settings</h3>
        <p>
            You can also control and manage cookies through your browser settings. Most browsers allow you to:
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

        <h2>9. Changes to This Policy</h2>
        <p>
            We may update this Cookies Policy from time to time, for example if we introduce new features that require
            additional cookies. Any changes will be reflected on this page with an updated "Last updated" date.
        </p>

        <h2>10. Contact</h2>
        <p>If you have any questions about our use of cookies, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
            <li>Address: GD CLOUD COMPANY S.R.L., Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
        </ul>

    </div>

</x-layouts.legal>
