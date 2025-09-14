@extends('layouts.app')

@section('title', 'Trading Fees - P2PCoinSwap')

@section('content')
    <!-- Header -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Trading Fees</h1>
                <p class="text-lg text-slate-400">Transparent and competitive fee structure for all traders</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Fee Overview -->
        <section class="space-y-4">
            <div class="bg-gradient-to-r from-emerald-900/20 to-blue-900/20 border border-emerald-500/20 rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-3">Our Fee Philosophy</h2>
                <p class="text-sm text-slate-300 mb-4">
                    P2PCoinSwap maintains a transparent and competitive fee structure designed to provide the best value for our traders while ensuring platform security and sustainability.
                </p>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-400">1.0%</div>
                        <div class="text-xs text-slate-400">Base Trading Fee</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">0 Fee</div>
                        <div class="text-xs text-slate-400">Deposits & Withdrawals</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-400">Lower Fees</div>
                        <div class="text-xs text-slate-400">With KYC Verification</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- KYC Verification Impact on Fees -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">KYC Verification & Fee Discounts</h2>

            <div class="bg-slate-900/60 border border-slate-800 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-800">
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Verification Level</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Requirements</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Daily Limit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Trading Fee</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-slate-300">Level 1</span>
                                    <span class="block text-xs text-slate-500">Unverified</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">Email Only</td>
                                <td class="px-4 py-3 text-sm text-slate-400">$10,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-red-400">2.0%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-emerald-400">Level 2</span>
                                    <span class="block text-xs text-slate-500">Basic KYC</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">Phone + ID</td>
                                <td class="px-4 py-3 text-sm text-slate-400">$100,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-amber-400">1.5%</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-blue-400">Level 3</span>
                                    <span class="block text-xs text-slate-500">Fully Verified</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">Full ID + Address</td>
                                <td class="px-4 py-3 text-sm text-slate-400">$500,000</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-semibold text-emerald-400">1.0%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        <!-- Cryptocurrency Fee Structure -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Cryptocurrency Trading Fees</h2>

            <!-- Bitcoin -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-orange-500/20 rounded-full flex items-center justify-center">
                        <span class="text-orange-400 font-bold">₿</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Bitcoin (BTC)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-800">
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Trade Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Fee Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Fee Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Example</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-300">≥ 0.005 BTC</td>
                                <td class="px-4 py-2 text-sm text-slate-400">Percentage</td>
                                <td class="px-4 py-2 text-sm font-medium text-emerald-400">1%</td>
                                <td class="px-4 py-2 text-xs text-slate-500">0.1 BTC trade = 0.001 BTC fee</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-300">< 0.005 BTC</td>
                                <td class="px-4 py-2 text-sm text-slate-400">Flat Fee</td>
                                <td class="px-4 py-2 text-sm font-medium text-amber-400">0.00005 BTC</td>
                                <td class="px-4 py-2 text-xs text-slate-500">0.001 BTC trade = 0.00005 BTC fee</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ethereum -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <span class="text-blue-400 font-bold">Ξ</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Ethereum (ETH)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-800">
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Trade Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Fee Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Fee Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Example</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-300">≥ 0.1 ETH</td>
                                <td class="px-4 py-2 text-sm text-slate-400">Percentage</td>
                                <td class="px-4 py-2 text-sm font-medium text-emerald-400">1%</td>
                                <td class="px-4 py-2 text-xs text-slate-500">1 ETH trade = 0.01 ETH fee</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-300">< 0.1 ETH</td>
                                <td class="px-4 py-2 text-sm text-slate-400">Flat Fee</td>
                                <td class="px-4 py-2 text-sm font-medium text-amber-400">0.001 ETH</td>
                                <td class="px-4 py-2 text-xs text-slate-500">0.05 ETH trade = 0.001 ETH fee</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stablecoins -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                        <span class="text-emerald-400 font-bold">$</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Stablecoins (USDT/USDC)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-800">
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Network</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Fee Structure</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Network Fee</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase">Example (100 USDT)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="text-sm text-slate-300">TRC-20</span>
                                    <span class="block text-xs text-slate-500">TRON Network</span>
                                </td>
                                <td class="px-4 py-2 text-sm font-medium text-emerald-400">1% + 2 USDT</td>
                                <td class="px-4 py-2 text-sm text-blue-400">Low (~1 TRX)</td>
                                <td class="px-4 py-2 text-xs text-slate-500">Total: 3 USDT</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="text-sm text-slate-300">ERC-20</span>
                                    <span class="block text-xs text-slate-500">Ethereum Network</span>
                                </td>
                                <td class="px-4 py-2 text-sm font-medium text-amber-400">1% + 4 USD</td>
                                <td class="px-4 py-2 text-sm text-red-400">High (~$5-50)</td>
                                <td class="px-4 py-2 text-xs text-slate-500">Total: 5 USD</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="text-sm text-slate-300">BEP-20</span>
                                    <span class="block text-xs text-slate-500">BSC Network</span>
                                </td>
                                <td class="px-4 py-2 text-sm font-medium text-blue-400">1% + 2 USD</td>
                                <td class="px-4 py-2 text-sm text-emerald-400">Low (~$0.25)</td>
                                <td class="px-4 py-2 text-xs text-slate-500">Total: 3 USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-amber-900/20 border border-amber-500/20 rounded-lg">
                    <p class="text-xs text-amber-400">
                        <strong>Tip:</strong> Choose TRC-20 or BEP-20 for lower network fees and faster confirmations.
                    </p>
                </div>
            </div>
        </section>

        <!-- Fee Calculation Examples -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Fee Calculation Examples</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Buy Example -->
                <div class="bg-gradient-to-br from-emerald-900/20 to-emerald-900/10 border border-emerald-500/20 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-emerald-400 mb-4">Buying Crypto</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Purchase Amount:</span>
                            <span class="text-slate-300">100 USDT</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Network:</span>
                            <span class="text-slate-300">TRC-20</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Your KYC Level:</span>
                            <span class="text-emerald-400">Level 3 (1.0%)</span>
                        </div>
                        <div class="pt-2 border-t border-slate-800">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Platform Fee:</span>
                                <span class="text-slate-300">1 USDT (1%)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Network Fee:</span>
                                <span class="text-slate-300">2 USDT</span>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-800">
                            <div class="flex justify-between">
                                <span class="text-slate-300 font-medium">Total Fee:</span>
                                <span class="text-emerald-400 font-bold">3 USDT</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-300 font-medium">You Receive:</span>
                                <span class="text-white font-bold">97 USDT</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sell Example -->
                <div class="bg-gradient-to-br from-red-900/20 to-red-900/10 border border-red-500/20 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-400 mb-4">Selling Crypto</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Sell Amount:</span>
                            <span class="text-slate-300">0.01 BTC</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">BTC Price:</span>
                            <span class="text-slate-300">$50,000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Your KYC Level:</span>
                            <span class="text-amber-400">Level 2 (1.5%)</span>
                        </div>
                        <div class="pt-2 border-t border-slate-800">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Trade Value:</span>
                                <span class="text-slate-300">$500</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Platform Fee:</span>
                                <span class="text-slate-300">0.00015 BTC (1.5%)</span>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-800">
                            <div class="flex justify-between">
                                <span class="text-slate-300 font-medium">Total Fee:</span>
                                <span class="text-red-400 font-bold">$7.50</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-300 font-medium">You Receive:</span>
                                <span class="text-white font-bold">$492.50</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Fees -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white">Additional Fees & Charges</h2>

            <div class="bg-slate-900/60 border border-slate-800 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-800/50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Fee</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-300">Order Cancellation</td>
                            <td class="px-4 py-3 text-sm font-medium text-amber-400">-1 Reputation</td>
                            <td class="px-4 py-3 text-xs text-slate-500">After escrow received</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-300">Dispute Resolution</td>
                            <td class="px-4 py-3 text-sm font-medium text-emerald-400">FREE</td>
                            <td class="px-4 py-3 text-xs text-slate-500">Available 24/7</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-300">Merchant Account</td>
                            <td class="px-4 py-3 text-sm font-medium text-blue-400">$100/month</td>
                            <td class="px-4 py-3 text-xs text-slate-500">Optional for vendors</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-300">API Access</td>
                            <td class="px-4 py-3 text-sm font-medium text-purple-400">Contact Sales</td>
                            <td class="px-4 py-3 text-xs text-slate-500">Enterprise only</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Important Notes -->
        <section class="space-y-4">
            <div class="bg-gradient-to-r from-amber-900/20 to-red-900/20 border border-amber-500/20 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-amber-400 mb-4">Important Notes</h3>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-300">All fees are deducted automatically from your trade amount</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-300">Network fees are separate and paid to blockchain miners/validators</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-300">Fees may change at any time - please check the platform app for current rates</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-amber-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-300">Special promotions may temporarily reduce fees for specific trading pairs</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="text-center space-y-4">
            <h3 class="text-xl font-semibold text-white">Ready to Start Trading?</h3>
            <p class="text-sm text-slate-400">Complete your KYC verification to unlock lower fees and higher limits</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/user/profile#kyc" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors">
                    Complete KYC Verification
                </a>
                <a href="/contact-support" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    Contact Support
                </a>
            </div>
        </section>

        <!-- Last Updated -->
        <div class="text-center pt-8 border-t border-slate-800">
            <p class="text-xs text-slate-500">
                Last updated: January 2025 | Subject to change with notice
            </p>
        </div>
    </div>
@endsection