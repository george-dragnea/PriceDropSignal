<x-layouts.legal :title="'Terms and Conditions'">

    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Terms and Conditions</h1>
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Last updated: {{ date('F j, Y') }}</p>

    <div class="legal-prose mt-10">

        <h2>1. Introduction</h2>
        <p>
            Welcome to PriceDropSignal. These Terms and Conditions ("Terms") govern your access to and use of the
            PriceDropSignal website and service ("Service") operated by <strong>GD CLOUD COMPANY S.R.L.</strong>
            ("Company", "we", "us", or "our"), a company incorporated in Romania.
        </p>
        <p><strong>Company details:</strong></p>
        <ul>
            <li>Company Name: GD CLOUD COMPANY S.R.L.</li>
            <li>Fiscal Code: 42798790</li>
            <li>Trade Register Number: J2020001873355</li>
            <li>Registered Address: Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
        </ul>
        <p>
            By creating an account or using the Service, you agree to be bound by these Terms. If you do not agree to
            these Terms, you must not use the Service.
        </p>

        <h2>2. Description of the Service</h2>
        <p>
            PriceDropSignal is a free price tracking tool that allows registered users to:
        </p>
        <ul>
            <li>Create products and associate them with URLs from online stores;</li>
            <li>Automatically monitor prices on those URLs at regular intervals;</li>
            <li>Receive email notifications when a tracked price drops;</li>
            <li>View price history and trends for tracked products.</li>
        </ul>
        <p>
            The Service fetches publicly available pricing information from third-party websites. We do not sell
            products, process payments for purchases, or act as an intermediary between you and any retailer.
        </p>

        <h2>3. Account Registration</h2>
        <p>To use the Service, you must:</p>
        <ul>
            <li>Be at least 16 years of age;</li>
            <li>Provide accurate and complete registration information (name and email address);</li>
            <li>Verify your email address before accessing the Service;</li>
            <li>Keep your login credentials secure and confidential.</li>
        </ul>
        <p>
            You are responsible for all activity that occurs under your account. You must notify us immediately at
            <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a> if you suspect unauthorized
            access to your account.
        </p>

        <h2>4. Acceptable Use</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use the Service for any unlawful purpose or in violation of any applicable laws or regulations;</li>
            <li>Submit URLs that point to illegal, harmful, or objectionable content;</li>
            <li>Attempt to interfere with, disrupt, or overload the Service or its infrastructure;</li>
            <li>Use automated scripts or bots to interact with the Service beyond normal use;</li>
            <li>Attempt to access other users' accounts or data;</li>
            <li>Resell, redistribute, or commercially exploit the Service without our prior written consent.</li>
        </ul>
        <p>
            We reserve the right to suspend or terminate your account if we reasonably believe you have violated these Terms.
        </p>

        <h2>5. Price Data Accuracy</h2>
        <p>
            The Service relies on automated extraction of pricing information from third-party websites. While we strive
            for accuracy, we <strong>cannot guarantee</strong> that:
        </p>
        <ul>
            <li>Prices displayed are current, complete, or accurate at any given time;</li>
            <li>Price data will always be successfully extracted from every URL;</li>
            <li>Notifications will be delivered instantaneously or without delay;</li>
            <li>Third-party websites will not change their structure, which may affect data extraction.</li>
        </ul>
        <p>
            The Service is provided for informational purposes only. You should always verify the price directly on the
            retailer's website before making a purchase decision. We are not liable for any losses arising from
            inaccurate price data.
        </p>

        <h2>6. Intellectual Property</h2>
        <p>
            All content, design, code, and branding of the Service are the property of GD CLOUD COMPANY S.R.L. or its
            licensors and are protected by applicable intellectual property laws. You may not copy, modify, distribute,
            or create derivative works from any part of the Service without our prior written consent.
        </p>
        <p>
            You retain ownership of any data you submit to the Service (product names, URLs). By submitting data, you
            grant us a limited license to process it solely for the purpose of providing the Service to you.
        </p>

        <h2>7. Service Availability</h2>
        <p>
            We aim to provide the Service on a continuous basis but do not guarantee uninterrupted availability. The
            Service may be temporarily unavailable due to maintenance, updates, or circumstances beyond our control.
            We reserve the right to modify, suspend, or discontinue the Service (or any part of it) at any time, with
            or without notice.
        </p>

        <h2>8. Limitation of Liability</h2>
        <p>
            To the maximum extent permitted by applicable law:
        </p>
        <ul>
            <li>
                The Service is provided <strong>"as is"</strong> and <strong>"as available"</strong>, without warranties
                of any kind, whether express or implied, including but not limited to warranties of merchantability,
                fitness for a particular purpose, and non-infringement.
            </li>
            <li>
                We shall not be liable for any indirect, incidental, special, consequential, or punitive damages,
                including loss of profits, data, or goodwill, arising from your use of or inability to use the Service.
            </li>
            <li>
                Our total aggregate liability to you for any claims arising from or related to the Service shall not
                exceed the amount you have paid us in the twelve (12) months preceding the claim (which, for the free
                Service, is zero).
            </li>
        </ul>
        <p>
            Nothing in these Terms shall exclude or limit liability that cannot be excluded or limited under applicable
            law, including liability for fraud or gross negligence.
        </p>

        <h2>9. Termination</h2>
        <p>
            You may terminate your account at any time by contacting us at
            <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a>. Upon termination, we will
            delete your account and all associated data in accordance with our
            <a href="{{ route('legal.privacy') }}">Privacy Policy</a>.
        </p>
        <p>
            We may terminate or suspend your account at our discretion if you breach these Terms, with or without prior notice.
        </p>

        <h2>10. Changes to These Terms</h2>
        <p>
            We may update these Terms from time to time. If we make material changes, we will notify you by email or by
            posting a notice on the Service. Your continued use of the Service after such changes constitutes your
            acceptance of the updated Terms.
        </p>

        <h2>11. Governing Law and Jurisdiction</h2>
        <p>
            These Terms are governed by and construed in accordance with the laws of Romania and applicable European
            Union regulations. Any disputes arising from these Terms or the use of the Service shall be submitted to
            the competent courts of Timisoara, Romania, without prejudice to any mandatory consumer protection
            provisions of your country of residence that may apply.
        </p>

        <h2>12. Contact</h2>
        <p>
            If you have any questions about these Terms, please contact us:
        </p>
        <ul>
            <li>Email: <a href="mailto:contact@pricedropsignal.com">contact@pricedropsignal.com</a></li>
            <li>Address: GD CLOUD COMPANY S.R.L., Bld. Take Ionescu 46B, Timisoara, Timis, 300124, Romania</li>
        </ul>

    </div>

</x-layouts.legal>
