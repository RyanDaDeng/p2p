@extends('layouts.app')

@section('title', 'Terms of Service - P2PCoinSwap')

@section('content')
    <!-- Header with Last Updated -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white">Terms of Service</h1>
                <div class="text-xs text-slate-500">
                    Last Updated: <span class="text-slate-400">Jan 2025</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto space-y-12">
            <!-- Title -->
            <div>
                <h1 class="text-4xl font-bold text-white mb-4">Terms of Service</h1>
                <p class="text-lg text-slate-400">
                    Please read these Terms of Service carefully before using our peer-to-peer cryptocurrency trading platform.
                </p>
            </div>

            <!-- Section 1: Introduction -->
            <section id="introduction" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">1. Introduction</h2>

                <p class="text-sm text-slate-300">
                    Welcome to P2PCoinSwap ("we," "us," "our," or the "Company"),
                    a peer-to-peer cryptocurrency trading platform that facilitates direct trades between users.
                </p>

                <p class="text-sm text-slate-300">
                    These Terms of Service ("Terms") govern your use of our website, mobile applications, and services
                    (collectively, the "Platform"). By accessing or using our Platform, you agree to be bound by these Terms.
                </p>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4 mt-4">
                    <h3 class="text-sm font-semibold text-white mb-2">Platform Description</h3>
                    <p class="text-sm text-slate-400">
                        Our Platform provides a marketplace where users can buy and sell cryptocurrencies directly with each other.
                        We provide escrow services to secure transactions but do not take custody of user funds beyond the escrow period.
                    </p>
                </div>
            </section>

            <!-- Section 2: Acceptance of Terms -->
            <section id="acceptance" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">2. Acceptance and Modification of Terms</h2>

                <div class="space-y-3">
                    <p class="text-sm text-slate-300">
                        By creating an account, accessing, or using our Platform, you:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-4">
                        <li>Accept and agree to be bound by these Terms</li>
                        <li>Represent that you have the authority to enter into these Terms</li>
                        <li>Warrant that you are at least 18 years of age</li>
                        <li>Agree to comply with all applicable laws and regulations</li>
                    </ul>
                </div>

                <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-400 mb-2">Right to Modify</h3>
                    <p class="text-sm text-slate-300">
                        We reserve the right to modify these Terms at any time. We will notify users of material changes
                        via email or Platform notification. Continued use after changes constitutes acceptance.
                    </p>
                </div>
            </section>

            <!-- Section 3: Eligibility -->
            <section id="eligibility" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">3. Eligibility Requirements</h2>

                <div class="space-y-3">
                    <p class="text-sm text-slate-300">
                        To use our Platform, you must:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-4">
                        <li>Be at least 18 years old or of legal age in your jurisdiction</li>
                        <li>Have the legal capacity to enter into binding contracts</li>
                        <li>Not be barred from using the Platform under applicable laws</li>
                        <li>Not be located in a prohibited jurisdiction</li>
                        <li>Provide accurate and complete registration information</li>
                    </ul>
                </div>

                <div class="bg-red-900/20 border border-red-800 rounded-lg p-4 mt-4">
                    <h3 class="text-sm font-semibold text-red-400 mb-2">Prohibited Jurisdictions</h3>
                    <p class="text-sm text-slate-300 mb-3">
                        Users from the following jurisdictions are prohibited from using our Platform due to local regulations:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                        <li>China (Mainland)</li>
                        <li>Algeria</li>
                        <li>Bangladesh</li>
                        <li>Egypt</li>
                        <li>Iraq</li>
                        <li>Morocco</li>
                        <li>Nepal</li>
                        <li>Qatar</li>
                        <li>Tunisia</li>
                        <li>Afghanistan</li>
                    </ul>
                    <p class="text-xs text-slate-500 mt-3">
                        Note: This list may be updated based on regulatory changes.
                    </p>
                </div>
            </section>

            <!-- Section 4: Account Registration -->
            <section id="registration" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">4. Account Registration</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">4.1 Account Creation</h3>
                        <p class="text-sm text-slate-300">
                            To access certain features, you must create an account by providing:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Valid email address</li>
                            <li>Secure password</li>
                            <li>Username (unique identifier)</li>
                            <li>Other required information</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">4.2 Account Security</h3>
                        <p class="text-sm text-slate-300">
                            You are responsible for:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Maintaining the confidentiality of your account credentials</li>
                            <li>All activities that occur under your account</li>
                            <li>Immediately notifying us of any unauthorized use</li>
                            <li>Enabling two-factor authentication (2FA) when available</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 5: KYC Verification Levels -->
            <section id="verification" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">5. KYC Verification Levels</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        Our platform implements a tiered KYC (Know Your Customer) verification system. Different verification levels have different trading limits and fee structures.
                    </p>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-slate-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Level</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Requirements</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Daily Limit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Fees</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-medium text-slate-300">Level 1</span>
                                        <span class="block text-xs text-slate-500">Unverified</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-400">Email verification only</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">$10,000</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">2.0%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-medium text-emerald-400">Level 2</span>
                                        <span class="block text-xs text-slate-500">Basic</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-400">Phone + Basic ID</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">$100,000</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">1.5%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-medium text-blue-400">Level 3</span>
                                        <span class="block text-xs text-slate-500">Verified</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-400">Full ID + Address Proof</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">$500,000</td>
                                    <td class="px-4 py-3 text-sm text-slate-400">1.0%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-amber-900/20 border border-amber-800 rounded-lg p-4">
                        <p class="text-xs text-amber-400">
                            <strong>Important:</strong> Trading limits, fees, and verification requirements may change dynamically based on regulatory requirements and platform policies. Please refer to the in-app notifications and your account dashboard for the most current information.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">5.1 Verification Process</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4">
                            <li>Submit required documents through our secure portal</li>
                            <li>Verification typically completes within 24-48 hours</li>
                            <li>You will be notified via email once verification is complete</li>
                            <li>Additional documentation may be requested for compliance</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">5.2 Ongoing Monitoring</h3>
                        <p class="text-sm text-slate-300">
                            We may periodically request updated information to maintain compliance with regulatory requirements.
                            Failure to provide requested information may result in account limitations.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 6: Trading Rules -->
            <section id="trading" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">6. Trading Rules</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        All users must comply with the following trading rules:
                    </p>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">6.1 General Trading Rules</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4">
                            <li>Complete trades within the specified time limits</li>
                            <li>Provide accurate payment information</li>
                            <li>Communicate respectfully with trading partners</li>
                            <li>Follow the platform's dispute resolution process</li>
                            <li>Do not attempt to circumvent the escrow system</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">6.2 Payment Methods</h3>
                        <p class="text-sm text-slate-300">
                            Only use payment methods that are:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Registered in your own name</li>
                            <li>Verified through our platform</li>
                            <li>Legal in your jurisdiction</li>
                            <li>Traceable and reversible (for buyer protection)</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 7: Escrow Service -->
            <section id="escrow" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">7. Escrow Service</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">7.1 How Escrow Works</h3>
                        <p class="text-sm text-slate-300">
                            Our escrow service secures cryptocurrency during trades:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Seller's crypto is locked in escrow when trade begins</li>
                            <li>Buyer sends payment directly to seller</li>
                            <li>Seller confirms payment receipt</li>
                            <li>Crypto is released from escrow to buyer</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">7.2 Escrow Terms</h3>
                        <p class="text-sm text-slate-300">
                            By using our escrow service, you agree that:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Escrow release is final and irreversible</li>
                            <li>Disputes must be raised before escrow release</li>
                            <li>We may hold funds during dispute investigation</li>
                            <li>We act as a neutral third party</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 8: Dispute Resolution -->
            <section id="disputes" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">8. Dispute Resolution</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        In case of trade disputes:
                    </p>

                    <ol class="list-decimal list-inside space-y-2 text-sm text-slate-400 ml-4">
                        <li>Attempt to resolve with your trading partner first</li>
                        <li>If unresolved, open a dispute ticket</li>
                        <li>Provide all relevant evidence (screenshots, receipts, etc.)</li>
                        <li>Our support team will review and mediate</li>
                        <li>Decision will be made within 24-72 hours</li>
                        <li>Dispute decisions are final</li>
                    </ol>
                </div>
            </section>

            <!-- Section 9: Fees -->
            <section id="fees" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">9. Fees</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        Our fee structure is transparent and competitive:
                    </p>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between">
                                <span class="text-slate-400">Trading Fee:</span>
                                <span class="text-slate-300">1.0% - 2.0% (based on KYC level)</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-slate-400">Dispute Fee:</span>
                                <span class="text-slate-300">Free (if justified)</span>
                            </li>
                        </ul>
                    </div>

                    <p class="text-xs text-slate-500">
                        * Fees are subject to change. Current fees are displayed in-app before each transaction.
                    </p>
                </div>
            </section>

            <!-- Section 10: Prohibited Activities -->
            <section id="prohibited" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">10. Prohibited Activities</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        The following activities are strictly prohibited:
                    </p>

                    <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4">
                        <li>Money laundering or terrorist financing</li>
                        <li>Fraudulent or deceptive practices</li>
                        <li>Trading with stolen funds</li>
                        <li>Creating multiple accounts</li>
                        <li>Market manipulation</li>
                        <li>Harassment or abusive behavior</li>
                        <li>Sharing account credentials</li>
                        <li>Using the platform for illegal activities</li>
                        <li>Attempting to hack or compromise the platform</li>
                        <li>Circumventing KYC/AML procedures</li>
                    </ul>

                    <div class="bg-red-900/20 border border-red-800 rounded-lg p-4 mt-4">
                        <p class="text-sm text-red-400">
                            Violation of these rules will result in immediate account suspension and may be reported to authorities.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 11: Limitation of Liability -->
            <section id="liability" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">11. Limitation of Liability</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        To the maximum extent permitted by law:
                    </p>

                    <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-4">
                        <li>We are not liable for losses from market volatility</li>
                        <li>We are not responsible for user-to-user transactions</li>
                        <li>We do not guarantee continuous platform availability</li>
                        <li>We are not liable for third-party payment failures</li>
                        <li>Our liability is limited to the fees paid to us</li>
                    </ul>

                    <p class="text-sm text-slate-300 mt-4">
                        You acknowledge that cryptocurrency trading carries inherent risks, including total loss of value.
                    </p>
                </div>
            </section>

            <!-- Section 12: Termination -->
            <section id="termination" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">12. Termination</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">12.1 User Termination</h3>
                        <p class="text-sm text-slate-300">
                            You may close your account at any time by contacting support. Upon closure:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Complete all pending trades</li>
                            <li>Account data will be retained per legal requirements</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2">12.2 Platform Termination</h3>
                        <p class="text-sm text-slate-300">
                            We may suspend or terminate accounts that:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4 mt-2">
                            <li>Violate these Terms</li>
                            <li>Engage in prohibited activities</li>
                            <li>Pose security or legal risks</li>
                            <li>Are inactive for extended periods</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 13: Governing Law -->
            <section id="governing" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">13. Governing Law</h2>

                <div class="space-y-4">
                    <p class="text-sm text-slate-300">
                        These Terms are governed by the laws of the jurisdiction in which P2PCoinSwap is registered,
                        without regard to conflict of law principles.
                    </p>

                    <p class="text-sm text-slate-300">
                        Any disputes shall be resolved through binding arbitration in accordance with the rules
                        of the relevant arbitration association.
                    </p>
                </div>
            </section>

            <!-- Section 14: Contact Information -->
            <section id="contact" class="space-y-4">
                <h2 class="text-2xl font-bold text-white">14. Contact Information</h2>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <p class="text-sm text-slate-300 mb-4">
                        For questions about these Terms, please contact us:
                    </p>
                    <div class="text-center">
                        <a href="/contact-support" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                            </svg>
                            Contact Support
                        </a>
                        <p class="text-xs text-slate-500 mt-2">Available 24/7 via Telegram</p>
                    </div>
                </div>
            </section>

            <!-- Signature Block -->
            <div class="mt-12 pt-8 border-t border-slate-800">
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <p class="text-sm text-slate-400">
                        By using our Platform, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection