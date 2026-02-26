<x-layouts.legal :title="'Privacy Policy'">

    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Privacy Policy</h1>
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Last updated: {{ date('F j, Y') }}</p>

    <div class="legal-prose mt-10">

        <h2>1. Introduction</h2>
        <p>
            This Privacy Policy explains how <strong>GD CLOUD COMPANY S.R.L.</strong> ("Company", "we", "us", or "our")
            collects, uses, stores, and protects your personal data when you use the PriceDropSignal service ("Service").
        </p>
        <p>
            We are committed to protecting your privacy in accordance with the General Data Protection Regulation (GDPR)
            — Regulation (EU) 2016/679 — and applicable Romanian data protection legislation.
        </p>
        <p><strong>Data Controller:</strong></p>
        <ul>
            <li>GD CLOUD COMPANY S.R.L.</li>
            <li>Fiscal Code: 42798790</li>
            <li>Trade Register Number: J2020001873355</li>
            <li>Address: Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
        </ul>

        <h2>2. What Personal Data We Collect</h2>

        <h3>2.1 Data You Provide Directly</h3>
        <ul>
            <li><strong>Account information:</strong> name, email address, and password (stored in hashed form) when you register;</li>
            <li><strong>Product data:</strong> product names and URLs from online stores that you submit for price tracking;</li>
            <li><strong>Two-factor authentication data:</strong> TOTP secret and recovery codes, if you enable two-factor authentication (stored encrypted).</li>
        </ul>

        <h3>2.2 Data Collected Automatically</h3>
        <ul>
            <li><strong>Session data:</strong> IP address, browser user-agent string, and last activity timestamp, stored when you log in;</li>
            <li><strong>Price check data:</strong> prices fetched from the URLs you submit, timestamps of each check, and any error messages from failed checks;</li>
            <li><strong>Email verification status:</strong> whether and when your email address was verified.</li>
        </ul>

        <h3>2.3 Data We Do Not Collect</h3>
        <p>
            We do not collect payment information, location data (beyond IP address), social media profiles, or any
            special categories of personal data (e.g., health, biometric, or political data). We do not use analytics
            or advertising trackers.
        </p>

        <h2>3. How We Use Your Data</h2>
        <p>We process your personal data for the following purposes:</p>

        <table>
            <thead>
                <tr>
                    <th>Purpose</th>
                    <th>Data Used</th>
                    <th>Legal Basis (GDPR Art. 6)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Account creation and authentication</td>
                    <td>Name, email, password</td>
                    <td>Performance of contract (Art. 6(1)(b))</td>
                </tr>
                <tr>
                    <td>Price tracking and notifications</td>
                    <td>Product names, URLs, email</td>
                    <td>Performance of contract (Art. 6(1)(b))</td>
                </tr>
                <tr>
                    <td>Session management and security</td>
                    <td>IP address, user-agent, session tokens</td>
                    <td>Legitimate interest (Art. 6(1)(f))</td>
                </tr>
                <tr>
                    <td>Email verification</td>
                    <td>Email address</td>
                    <td>Performance of contract (Art. 6(1)(b))</td>
                </tr>
                <tr>
                    <td>Two-factor authentication</td>
                    <td>TOTP secret, recovery codes</td>
                    <td>Consent (Art. 6(1)(a))</td>
                </tr>
                <tr>
                    <td>Service improvement and debugging</td>
                    <td>Error logs from price checks</td>
                    <td>Legitimate interest (Art. 6(1)(f))</td>
                </tr>
            </tbody>
        </table>

        <h2>4. Data Sharing and Third Parties</h2>
        <p>We do not sell, rent, or trade your personal data. We may share data with the following categories of third parties, solely to operate the Service:</p>
        <ul>
            <li>
                <strong>Email delivery provider:</strong> your email address and notification content are transmitted to
                our email service provider to deliver price drop notifications and transactional emails (e.g., email
                verification, password reset).
            </li>
            <li>
                <strong>Hosting provider:</strong> all data is stored on servers operated by our infrastructure provider.
                Data remains within the European Union or in jurisdictions with an adequate level of data protection as
                determined by the European Commission.
            </li>
            <li>
                <strong>Font provider:</strong> we use Bunny Fonts (bunny.net, operated by BunnyWay d.o.o., an EU-based
                company) to serve web fonts. Bunny Fonts is designed to be GDPR-compliant and does not log or track visitors.
            </li>
        </ul>
        <p>
            When fetching prices, our system accesses the publicly available web pages at the URLs you provide. No
            personal data about you is transmitted to those third-party websites during this process.
        </p>

        <h2>5. Data Retention</h2>
        <ul>
            <li>
                <strong>Account data:</strong> retained for as long as your account is active. When you delete your
                account, all personal data including products, URLs, and price history is permanently deleted.
            </li>
            <li>
                <strong>Product and price data:</strong> retained for as long as the product exists in your account. When
                you delete a product, all associated URLs and price check history are permanently deleted (cascading
                deletion).
            </li>
            <li>
                <strong>Session data:</strong> session records expire after 120 minutes of inactivity and are
                periodically purged from the database.
            </li>
        </ul>

        <h2>6. Your Rights Under GDPR</h2>
        <p>As a data subject, you have the following rights:</p>
        <ul>
            <li><strong>Right of access (Art. 15):</strong> request a copy of the personal data we hold about you;</li>
            <li><strong>Right to rectification (Art. 16):</strong> request correction of inaccurate or incomplete data;</li>
            <li><strong>Right to erasure (Art. 17):</strong> request deletion of your personal data ("right to be forgotten");</li>
            <li><strong>Right to restriction (Art. 18):</strong> request that we limit the processing of your data;</li>
            <li><strong>Right to data portability (Art. 20):</strong> request your data in a structured, commonly used, machine-readable format;</li>
            <li><strong>Right to object (Art. 21):</strong> object to processing based on legitimate interest;</li>
            <li><strong>Right to withdraw consent (Art. 7(3)):</strong> withdraw consent at any time where processing is based on consent (e.g., two-factor authentication).</li>
        </ul>
        <p>
            To exercise any of these rights, contact us at
            <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a>. We will respond within 30
            days. If you are unsatisfied with our response, you have the right to lodge a complaint with the Romanian
            National Supervisory Authority for Personal Data Processing (ANSPDCP) or the data protection authority in
            your country of residence.
        </p>

        <h2>7. Data Security</h2>
        <p>We implement appropriate technical and organizational measures to protect your data, including:</p>
        <ul>
            <li>Passwords are stored using one-way cryptographic hashing (bcrypt);</li>
            <li>Two-factor authentication secrets are stored encrypted;</li>
            <li>Session cookies are HTTP-only and use the SameSite attribute;</li>
            <li>CSRF protection is enabled on all forms;</li>
            <li>All communication with the Service is encrypted via HTTPS.</li>
        </ul>
        <p>
            No method of transmission or storage is 100% secure. While we strive to use commercially acceptable means
            to protect your personal data, we cannot guarantee its absolute security.
        </p>

        <h2>8. International Data Transfers</h2>
        <p>
            Your data is processed and stored within the European Union. If a transfer outside the EU/EEA is required
            (e.g., by an email delivery provider), we ensure appropriate safeguards are in place, such as Standard
            Contractual Clauses (SCCs) approved by the European Commission or an adequacy decision.
        </p>

        <h2>9. Children's Privacy</h2>
        <p>
            The Service is not intended for children under the age of 16. We do not knowingly collect personal data from
            children under 16. If you believe a child has provided us with personal data, please contact us and we will
            promptly delete it.
        </p>

        <h2>10. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. If we make material changes, we will notify you by
            email or by posting a prominent notice on the Service. The "Last updated" date at the top of this page
            indicates the most recent revision.
        </p>

        <h2>11. Contact</h2>
        <p>For any questions or requests regarding your personal data, contact us:</p>
        <ul>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
            <li>Address: GD CLOUD COMPANY S.R.L., Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
        </ul>

    </div>

</x-layouts.legal>
