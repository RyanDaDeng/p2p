@extends('layouts.app')

@section('title', 'Privacy Policy - P2PCoinSwap')


@section('content')
    <!-- Header with Last Updated -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white">Privacy Policy</h1>
                <div class="text-xs text-slate-500">
                    Effective Date: <span class="text-slate-400">Jan 2025</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto space-y-12">
                <!-- Title -->
                <div>
                    <h1 class="text-4xl font-bold text-white mb-4">Privacy Policy</h1>
                    <p class="text-lg text-slate-400">
                        Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our P2P cryptocurrency trading platform.
                    </p>
                </div>

                <!-- Section 1: Overview -->
                <section id="overview" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">1. Overview</h2>

                    <p class="text-sm text-slate-300">
                        P2PCoinSwap operates the P2PCoinSwap platform
                        (the "Service"). This page informs you of our policies regarding the collection, use, and disclosure
                        of personal data when you use our Service.
                    </p>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-white mb-2">Key Principles</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                            <li>We collect only necessary information for platform operation</li>
                            <li>Your data is encrypted and securely stored</li>
                            <li>We never sell your personal information</li>
                            <li>You have control over your data</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 2: Information We Collect -->
                <section id="information-collect" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">2. Information We Collect</h2>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-3">2.1 Information You Provide</h3>
                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-emerald-400 mb-2">Account Registration</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                                    <li>Email address</li>
                                    <li>Username</li>
                                    <li>Password (encrypted)</li>
                                    <li>Phone number (optional for 2FA)</li>
                                </ul>
                            </div>

                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4 mt-3">
                                <h4 class="text-sm font-medium text-blue-400 mb-2">KYC Verification</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                                    <li>Full legal name</li>
                                    <li>Date of birth</li>
                                    <li>Address</li>
                                    <li>Government-issued ID</li>
                                    <li>Proof of address documents</li>
                                </ul>
                            </div>

                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4 mt-3">
                                <h4 class="text-sm font-medium text-amber-400 mb-2">Trading Information</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                                    <li>Payment method details</li>
                                    <li>Transaction history</li>
                                    <li>Trade preferences</li>
                                    <li>Chat messages with trading partners</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-white mb-3">2.2 Information Automatically Collected</h3>
                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                                <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-2">
                                    <li>IP address and location data</li>
                                    <li>Device information (type, OS, browser)</li>
                                    <li>Usage data (pages visited, features used)</li>
                                    <li>Cookies and similar tracking technologies</li>
                                    <li>Log data (access times, error logs)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 3: How We Use Information -->
                <section id="how-we-use" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">3. How We Use Your Information</h2>

                    <div class="space-y-4">
                        <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3">Primary Uses</h3>
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-2">
                                <li><span class="text-slate-300">Account Management:</span> Create and manage your account</li>
                                <li><span class="text-slate-300">Service Delivery:</span> Facilitate P2P trades and escrow services</li>
                                <li><span class="text-slate-300">Security:</span> Prevent fraud and unauthorized access</li>
                                <li><span class="text-slate-300">Communication:</span> Send transaction updates and support messages</li>
                                <li><span class="text-slate-300">Compliance:</span> Meet legal and regulatory requirements</li>
                                <li><span class="text-slate-300">Improvement:</span> Analyze usage to improve our services</li>
                            </ul>
                        </div>

                    </div>
                </section>

                <!-- Section 4: Information Sharing -->
                <section id="sharing" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">4. Information Sharing and Disclosure</h2>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-300">
                            We do not sell, trade, or rent your personal information. We may share your information in the following situations:
                        </p>

                        <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3">When We Share Information</h3>
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-2">
                                <li><span class="text-slate-300">With Trading Partners:</span> Limited information necessary for trades</li>
                                <li><span class="text-slate-300">Service Providers:</span> Third parties that help operate our platform</li>
                                <li><span class="text-slate-300">Legal Requirements:</span> When required by law or court order</li>
                                <li><span class="text-slate-300">Business Transfers:</span> In case of merger or acquisition</li>
                                <li><span class="text-slate-300">With Consent:</span> When you explicitly agree to sharing</li>
                            </ul>
                        </div>

                        <div class="bg-amber-900/20 border border-amber-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-amber-400 mb-2">⚠️ Important Note</h3>
                            <p class="text-sm text-slate-300">
                                Your trading partners can see your username, rating, trade count, and payment method details
                                during active trades. Your real name and contact details are never shared unless you choose to provide them.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Section 5: Data Security -->
                <section id="data-security" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">5. Data Security</h2>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-300">
                            We implement industry-standard security measures to protect your personal information:
                        </p>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-emerald-900/20 border border-emerald-800 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-emerald-400 mb-2">Technical Measures</h3>
                                <ul class="list-disc list-inside space-y-1 text-xs text-slate-400 ml-2">
                                    <li>SSL/TLS encryption</li>
                                    <li>Encrypted database storage</li>
                                    <li>Regular security audits</li>
                                    <li>DDoS protection</li>
                                </ul>
                            </div>

                            <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-blue-400 mb-2">Access Controls</h3>
                                <ul class="list-disc list-inside space-y-1 text-xs text-slate-400 ml-2">
                                    <li>Two-factor authentication</li>
                                    <li>Role-based access</li>
                                    <li>Regular password updates</li>
                                    <li>Session management</li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-sm text-slate-400">
                            While we strive to protect your information, no method of transmission over the Internet is 100% secure.
                        </p>
                    </div>
                </section>

                <!-- Section 6: Data Retention -->
                <section id="retention" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">6. Data Retention</h2>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-300">
                            We retain your personal information for as long as necessary to provide our services and comply with legal obligations.
                        </p>

                        <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3">Retention Periods</h3>
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-2">
                                <li><span class="text-slate-300">Account Data:</span> Duration of account plus 30 days</li>
                                <li><span class="text-slate-300">Transaction Records:</span> 7 years for compliance</li>
                                <li><span class="text-slate-300">KYC Documents:</span> 5 years after account closure</li>
                                <li><span class="text-slate-300">Chat Messages:</span> 90 days after trade completion</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Section 7: Cookies -->
                <section id="cookies" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">7. Cookies and Tracking Technologies</h2>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-300">
                            We use cookies and similar tracking technologies to improve your experience on our platform.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-white mb-2">Essential Cookies</h3>
                                <p class="text-xs text-slate-400">
                                    Required for platform functionality, authentication, and security.
                                </p>
                            </div>

                            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-white mb-2">Analytics Cookies</h3>
                                <p class="text-xs text-slate-400">
                                    Help us understand usage patterns and improve our services.
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-400 mt-4">
                            You can control cookies through your browser settings, but disabling essential cookies may affect platform functionality.
                        </p>
                    </div>
                </section>

                <!-- Section 8: Third-Party Services -->
                <section id="third-party" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">8. Third-Party Services</h2>

                    <p class="text-sm text-slate-300 mb-3">
                        We may use third-party services that collect, monitor, and analyze data:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4">
                        <li>Google Analytics (usage analytics)</li>
                        <li>Cloudflare (security and performance)</li>
                        <li>Email service providers</li>
                        <li>Identity verification services</li>
                    </ul>
                </section>

                <!-- Section 9: User Rights -->
                <section id="user-rights" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">9. Your Rights</h2>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-300">
                            You have the following rights regarding your personal information:
                        </p>

                        <div class="bg-emerald-900/20 border border-emerald-800 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-emerald-400 mb-3">Your Data Rights</h3>
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-400 ml-2">
                                <li><span class="text-slate-300">Access:</span> Request a copy of your personal data</li>
                                <li><span class="text-slate-300">Correction:</span> Update or correct inaccurate information</li>
                                <li><span class="text-slate-300">Deletion:</span> Request deletion of your account and data</li>
                                <li><span class="text-slate-300">Portability:</span> Receive your data in a portable format</li>
                                <li><span class="text-slate-300">Restriction:</span> Limit how we process your data</li>
                                <li><span class="text-slate-300">Objection:</span> Object to certain data processing</li>
                            </ul>
                        </div>

                        <p class="text-sm text-slate-400">
                            To exercise these rights, please contact us via our <a href="/contact-support" class="text-blue-400 hover:text-blue-300">Support Channel</a>.
                        </p>
                    </div>
                </section>


                <!-- Section 10: Children's Privacy -->
                <section id="children" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">10. Children's Privacy</h2>

                    <div class="bg-red-900/20 border border-red-800 rounded-lg p-4">
                        <p class="text-sm text-slate-300">
                            Our Service is not intended for individuals under 18 years of age. We do not knowingly collect
                            personal information from children. If you become aware that a child has provided us with personal
                            information, please contact us immediately.
                        </p>
                    </div>
                </section>

                <!-- Section 11: Changes to Policy -->
                <section id="changes" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">11. Changes to This Privacy Policy</h2>

                    <div class="space-y-3">
                        <p class="text-sm text-slate-300">
                            We may update our Privacy Policy from time to time. We will notify you of any changes by:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-400 ml-4">
                            <li>Posting the new Privacy Policy on this page</li>
                            <li>Updating the "Effective Date" at the top</li>
                            <li>Sending an email notification for material changes</li>
                        </ul>
                        <p class="text-sm text-slate-400 mt-3">
                            We encourage you to review this Privacy Policy periodically for any changes.
                        </p>
                    </div>
                </section>

                <!-- Section 12: Contact Information -->
                <section id="contact" class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">12. Contact Information</h2>

                    <p class="text-sm text-slate-300 mb-4">
                        If you have any questions about this Privacy Policy, please contact us:
                    </p>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4 text-center">
                        <a href="/contact-support" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                            </svg>
                            Contact Support
                        </a>
                        <p class="text-xs text-slate-500 mt-2">Available 24/7 via Telegram</p>
                    </div>
                </section>

                <!-- Acknowledgment Box -->
                <div class="mt-12 pt-8 border-t border-slate-800">
                    <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-white mb-3">Acknowledgment</h3>
                        <p class="text-sm text-slate-400 mb-4">
                            By using our Service, you acknowledge that you have read and understood this Privacy Policy
                            and agree to the collection and use of information in accordance with this policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection