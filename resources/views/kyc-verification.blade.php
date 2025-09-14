@extends('layouts.app')

@section('title', 'KYC Verification - P2PCoinSwap')

@section('content')
    <!-- Header -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">KYC Verification</h1>
                <p class="text-lg text-slate-400">Unlock lower fees and higher trading limits</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- KYC Overview -->
        <section class="space-y-4">
            <div class="bg-gradient-to-r from-blue-900/20 to-emerald-900/20 border border-blue-500/20 rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-3">KYC on P2PCoinSwap</h2>
                <div class="space-y-3">
                    <p class="text-sm text-slate-300">
                        <strong>KYC verification is NOT mandatory</strong> to use P2PCoinSwap. You can trade cryptocurrencies without completing any KYC process.
                    </p>
                    <p class="text-sm text-slate-300">
                        However, completing KYC verification provides significant benefits:
                    </p>
                    <ul class="space-y-2 ml-4">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-400 flex-shrink-0">✓</span>
                            <span class="text-sm text-slate-300">Lower trading fees</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-400 flex-shrink-0">✓</span>
                            <span class="text-sm text-slate-300">Higher daily trading limits</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-400 flex-shrink-0">✓</span>
                            <span class="text-sm text-slate-300">Access to more merchants and trading pairs</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-400 flex-shrink-0">✓</span>
                            <span class="text-sm text-slate-300">Build trust with other traders</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-amber-900/20 border border-amber-500/20 rounded-lg p-4">
                <p class="text-sm text-amber-400">
                    <strong>Note:</strong> Some merchants may require you to have a certain KYC level before trading with them. This is their individual preference, not a platform requirement.
                </p>
            </div>
        </section>

        <!-- KYC Levels -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Verification Levels & Benefits</h2>

            <div class="bg-slate-900/60 border border-slate-800 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-800">
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Level</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Requirements</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Daily Limit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Trading Fee</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Processing Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-slate-500 rounded-full"></div>
                                        <div>
                                            <span class="text-sm font-medium text-slate-300">Level 1</span>
                                            <span class="block text-xs text-slate-500">Basic</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <ul class="text-sm text-slate-400 space-y-1">
                                        <li>• Email verification</li>
                                        <li>• Username setup</li>
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">$10,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-red-400">2.0%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">Instant</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                        <div>
                                            <span class="text-sm font-medium text-emerald-400">Level 2</span>
                                            <span class="block text-xs text-slate-500">Verified</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <ul class="text-sm text-slate-400 space-y-1">
                                        <li>• Phone verification</li>
                                        <li>• Government ID (Passport/Driver's License)</li>
                                        <li>• Selfie verification</li>
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">$100,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-amber-400">1.5%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">24-48 hours</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        <div>
                                            <span class="text-sm font-medium text-blue-400">Level 3</span>
                                            <span class="block text-xs text-slate-500">Fully Verified</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <ul class="text-sm text-slate-400 space-y-1">
                                        <li>• All Level 2 requirements</li>
                                        <li>• Proof of address (Utility bill/Bank statement)</li>
                                        <li>• Source of funds declaration</li>
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">$500,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-emerald-400">1.0%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">3-5 business days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Verification Process -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Verification Process</h2>

            <div class="space-y-4">
                <!-- Step 1 -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold">1</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">Choose Your Level</h3>
                            <p class="text-sm text-slate-300 mb-3">
                                Decide which verification level suits your trading needs. You can always upgrade later.
                            </p>
                            <div class="bg-slate-800/50 rounded-lg p-3">
                                <p class="text-xs text-slate-400">
                                    <strong>Tip:</strong> Start with Level 2 if you plan to trade regularly. The lower fees quickly offset the verification effort.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold">2</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">Submit Documents</h3>
                            <p class="text-sm text-slate-300 mb-3">
                                Upload clear photos of your required documents through our secure portal.
                            </p>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span class="text-sm text-slate-400">Accepted formats: JPG, PNG, PDF</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span class="text-sm text-slate-400">Maximum file size: 10MB per document</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span class="text-sm text-slate-400">Ensure all text is clearly readable</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold">3</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">Verification Review</h3>
                            <p class="text-sm text-slate-300 mb-3">
                                Our compliance team will review your submission. You'll receive updates via email.
                            </p>
                            <div class="grid md:grid-cols-3 gap-3">
                                <div class="bg-slate-800/50 rounded-lg p-3 text-center">
                                    <div class="text-emerald-400 font-bold">Level 1</div>
                                    <div class="text-xs text-slate-500">Instant approval</div>
                                </div>
                                <div class="bg-slate-800/50 rounded-lg p-3 text-center">
                                    <div class="text-amber-400 font-bold">Level 2</div>
                                    <div class="text-xs text-slate-500">24-48 hours</div>
                                </div>
                                <div class="bg-slate-800/50 rounded-lg p-3 text-center">
                                    <div class="text-blue-400 font-bold">Level 3</div>
                                    <div class="text-xs text-slate-500">3-5 business days</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">Start Trading</h3>
                            <p class="text-sm text-slate-300 mb-3">
                                Once approved, your new limits and fee rates are immediately active.
                            </p>
                            <div class="bg-emerald-900/20 border border-emerald-500/20 rounded-lg p-3">
                                <p class="text-sm text-emerald-400">
                                    Your verification badge will be visible to other traders, building trust in your transactions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Document Requirements -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Document Requirements</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Accepted Documents -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-emerald-400 mb-4">✓ Accepted Documents</h3>

                    <div class="space-y-3">
                        <div>
                            <h4 class="text-sm font-medium text-white mb-2">Government ID</h4>
                            <ul class="space-y-1">
                                <li class="text-sm text-slate-400">• Passport (preferred)</li>
                                <li class="text-sm text-slate-400">• National ID card</li>
                                <li class="text-sm text-slate-400">• Driver's license</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-white mb-2">Proof of Address</h4>
                            <ul class="space-y-1">
                                <li class="text-sm text-slate-400">• Utility bill (< 3 months)</li>
                                <li class="text-sm text-slate-400">• Bank statement</li>
                                <li class="text-sm text-slate-400">• Government letter</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Not Accepted -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-400 mb-4">✗ Not Accepted</h3>

                    <div class="space-y-3">
                        <div>
                            <h4 class="text-sm font-medium text-white mb-2">Invalid Documents</h4>
                            <ul class="space-y-1">
                                <li class="text-sm text-slate-400">• Expired documents</li>
                                <li class="text-sm text-slate-400">• Photos of screens</li>
                                <li class="text-sm text-slate-400">• Edited or altered documents</li>
                                <li class="text-sm text-slate-400">• Blurry or unreadable images</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-white mb-2">Invalid Proof of Address</h4>
                            <ul class="space-y-1">
                                <li class="text-sm text-slate-400">• Mobile phone bills</li>
                                <li class="text-sm text-slate-400">• Insurance documents</li>
                                <li class="text-sm text-slate-400">• Documents older than 3 months</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy & Security -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Privacy & Security</h2>

            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Data Encryption</h3>
                            <p class="text-sm text-slate-400">All documents are encrypted with AES-256 encryption during transmission and storage.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Compliance Standards</h3>
                            <p class="text-sm text-slate-400">We follow international AML/CFT guidelines and data protection regulations.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Data Retention</h3>
                            <p class="text-sm text-slate-400">Documents are retained securely for regulatory compliance and deleted after the required period.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">No Third-Party Sharing</h3>
                            <p class="text-sm text-slate-400">Your documents are never shared with third parties without your explicit consent or legal requirement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Frequently Asked Questions</h2>

            <div class="space-y-3">
                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-white mb-2">Can I trade without KYC?</h3>
                    <p class="text-sm text-slate-400">
                        Yes, you can trade on P2PCoinSwap without any KYC verification. However, you'll have higher fees (2%) and lower daily limits ($10,000).
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-white mb-2">Why do some merchants require KYC?</h3>
                    <p class="text-sm text-slate-400">
                        Individual merchants may set their own requirements for trading partners. This helps them manage risk and comply with their local regulations.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-white mb-2">Can I upgrade my KYC level later?</h3>
                    <p class="text-sm text-slate-400">
                        Yes, you can upgrade your verification level at any time. Simply submit the additional required documents through your profile settings.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-white mb-2">What if my documents are rejected?</h3>
                    <p class="text-sm text-slate-400">
                        You'll receive an email explaining why your documents were rejected and what you need to resubmit. Common reasons include unclear photos or expired documents.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-white mb-2">Is my personal information safe?</h3>
                    <p class="text-sm text-slate-400">
                        We use industry-standard encryption and security measures to protect your documents. Your information is only accessed by authorized compliance personnel.
                    </p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="text-center space-y-4">
            <h3 class="text-xl font-semibold text-white">Ready to Get Verified?</h3>
            <p class="text-sm text-slate-400">Unlock lower fees and higher trading limits today</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/user/profile#kyc" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors">
                    Start Verification
                </a>
                <a href="/trading-fees" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    View Fee Structure
                </a>
            </div>
        </section>
    </div>
@endsection