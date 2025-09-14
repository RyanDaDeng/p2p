@extends('layouts.app')

@section('title', 'Dispute Resolution - P2PCoinSwap')

@section('content')
    <!-- Header -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Dispute Resolution</h1>
                <p class="text-lg text-slate-400">Fair and transparent dispute resolution process</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Overview -->
        <div class="bg-gradient-to-r from-amber-900/20 to-red-900/20 border border-amber-500/20 rounded-xl p-6 mb-12">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white mb-2">Important Notice</h2>
                    <p class="text-sm text-slate-300">
                        Both sellers and buyers can raise disputes during active trades. Our arbitration system ensures fair resolution based on evidence collected through the platform's chat system.
                    </p>
                </div>
            </div>
        </div>

        <!-- Dispute Process Steps -->
        <div class="space-y-8">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Dispute Resolution Process</h2>

            <!-- Step 1: Raising a Dispute -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Raising a Dispute</h3>
                        <p class="text-sm text-slate-300 mb-4">
                            Either the seller or buyer can initiate a dispute during an active trade when issues arise that cannot be resolved directly between the parties.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">When to Raise a Dispute</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Payment not received after confirmation</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Crypto not sent to escrow</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Incorrect payment details provided</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Communication breakdown</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">How to Raise</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Click "Create Dispute" in trade chat</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Provide detailed reason</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Submit any available evidence</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Wait for arbitrator assignment</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Arbitrator Assignment -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Arbitrator Assignment</h3>
                        <p class="text-sm text-slate-300 mb-4">
                            Once a dispute is raised, it will be automatically assigned to an available arbitrator/administrator from our team.
                        </p>

                        <div class="bg-blue-900/20 border border-blue-500/20 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-400 mb-2">⏰ Timeline</h4>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-400 mb-2">Within 24 Hours</div>
                                <p class="text-sm text-slate-400">An arbitrator will join your trade chat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Evidence Collection -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Evidence Collection & Investigation</h3>
                        <p class="text-sm text-slate-300 mb-4">
                            The arbitrator will review the trade chat history and collect additional evidence from both parties.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Evidence Sources</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Platform chat history</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Transaction screenshots</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Bank/payment confirmations</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Blockchain transaction records</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Your Responsibilities</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Stay online and responsive</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Provide requested materials promptly</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Answer arbitrator questions honestly</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Keep all communication on-platform</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-red-900/20 border border-red-500/20 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-red-400 mb-2">⚠️ Critical Rule</h4>
                            <p class="text-sm text-slate-400">
                                <strong>Only communications within the platform chat will be considered as evidence.</strong>
                                External communications (WhatsApp, Telegram, Email, etc.) cannot be used in dispute resolution.
                                Always keep all trade-related discussions on P2PCoinSwap.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Resolution Decision -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Resolution Decision</h3>
                        <p class="text-sm text-slate-300 mb-4">
                            After thorough investigation, the arbitrator will make a decision based on the collected evidence and present it to both parties.
                        </p>

                        <div class="space-y-4">
                            <!-- Mutual Agreement -->
                            <div class="bg-emerald-900/20 border border-emerald-500/20 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-emerald-400 mb-2">✓ Best Case: Mutual Agreement</h4>
                                <p class="text-sm text-slate-400">
                                    When both parties agree with the arbitrator's decision, the resolution is implemented immediately.
                                    This includes releasing or returning funds as determined.
                                </p>
                            </div>

                            <!-- Party Refuses -->
                            <div class="bg-amber-900/20 border border-amber-500/20 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-amber-400 mb-2">⚠️ If One Party Refuses</h4>
                                <p class="text-sm text-slate-400 mb-2">
                                    When one party disagrees with the decision, P2PCoinSwap reserves the right to:
                                </p>
                                <ul class="space-y-1">
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Unilaterally enforce the decision based on evidence</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Hold funds until new evidence is provided</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Wait for mutual agreement if evidence is inconclusive</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention Tips -->
        <div class="mt-12 bg-gradient-to-r from-emerald-900/20 to-blue-900/20 border border-emerald-500/20 rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4 text-center">Dispute Prevention Tips</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-white mb-2">Clear Communication</h4>
                    <p class="text-xs text-slate-400">Always communicate clearly about payment methods, timing, and expectations in the platform chat.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-white mb-2">Follow Process</h4>
                    <p class="text-xs text-slate-400">Stick to the 8-step trading process and confirm each step before proceeding to the next.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-white mb-2">Document Everything</h4>
                    <p class="text-xs text-slate-400">Take screenshots of payments and transactions, share them in the trade chat for records.</p>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-8 text-center">
            <p class="text-sm text-slate-400 mb-4">Need help with the dispute process?</p>
            <a href="/contact-support" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                </svg>
                Contact Support
            </a>
        </div>
    </div>
@endsection