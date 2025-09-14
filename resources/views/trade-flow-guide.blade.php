@extends('layouts.app')

@section('title', 'Trade Flow Guide - P2PCoinSwap')

@section('content')
    <!-- Header -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Trade Flow Guide</h1>
                    <p class="text-sm text-slate-400 mt-2">Understand our secure 8-step P2P trading process</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                        <span class="text-xs text-emerald-400">Escrow Protected</span>
                    </div>
                    <div class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                        <span class="text-xs text-blue-400">2FA Secured</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Important Role Clarification -->
        <div class="bg-gradient-to-r from-blue-900/20 to-purple-900/20 border border-blue-500/20 rounded-xl p-6 mb-12">
            <h2 class="text-lg font-bold text-white mb-4">Understanding Roles in P2P Trading</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-blue-400 mb-3">Vendor (Merchant)</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-blue-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">Can be either <strong class="text-white">Buyer</strong> or <strong class="text-white">Seller</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">When selling crypto → Vendor is the <strong class="text-emerald-400">Seller</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">When buying crypto → Vendor is the <strong class="text-blue-400">Buyer</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">Creates advertisements and confirms orders</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-purple-400 mb-3">Client (Regular User)</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-purple-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">Can be either <strong class="text-white">Buyer</strong> or <strong class="text-white">Seller</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">When buying from vendor → Client is the <strong class="text-blue-400">Buyer</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">When selling to vendor → Client is the <strong class="text-emerald-400">Seller</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-purple-400 flex-shrink-0">•</span>
                            <span class="text-sm text-slate-300">Responds to vendor advertisements</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 p-3 bg-amber-900/20 border border-amber-500/20 rounded-lg">
                <p class="text-sm text-amber-400">
                    <strong>Key Point:</strong> In Step 1, it's always the <strong>Vendor</strong> (merchant) who needs to confirm the order first, regardless of whether they are buying or selling crypto. After vendor confirmation, the <strong>Seller</strong> sends crypto to escrow.
                </p>
            </div>
        </div>

        <!-- Live Demo Section -->
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 text-center">Live Trade Demo</h2>

            <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                <!-- Left: Trade Flow Progress -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Trade Progress</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500">Order #P2P-2024-0812</span>
                        </div>
                    </div>

                    <!-- Trade Info -->
                    <div class="bg-slate-800/50 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="text-xs text-slate-500">You're buying</span>
                                <div class="text-lg font-semibold text-white">100 USDT</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-500">Rate</span>
                                <div class="text-lg font-semibold text-emerald-400">$1.00/USDT</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-700">
                            <span class="text-sm text-slate-400">Total</span>
                            <span class="text-lg font-bold text-white">$100.00</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-slate-400">Payment</span>
                            <span class="text-sm text-slate-300">Bank Transfer</span>
                        </div>
                    </div>

                    <!-- Progress Steps -->
                    <div class="space-y-3">
                        <!-- Step 1 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">1</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-emerald-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Order Created</h4>
                                    <span class="text-xs text-slate-500">10:30 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Waiting for vendor confirmation</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">2</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-emerald-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Vendor Confirmed</h4>
                                    <span class="text-xs text-slate-500">10:31 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Seller transferring crypto to escrow</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">3</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-emerald-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Crypto Sent to Escrow</h4>
                                    <span class="text-xs text-slate-500">10:33 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Blockchain transaction confirmed</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">4</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-blue-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Escrow Confirmed</h4>
                                    <span class="text-xs text-slate-500">10:34 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Funds secured in escrow</p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">5</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-blue-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Buyer Confirms Escrow</h4>
                                    <span class="text-xs text-slate-500">10:35 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Ready to send payment</p>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">6</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-amber-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Payment Sent</h4>
                                    <span class="text-xs text-slate-500">10:36 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Buyer sent payment</p>
                            </div>
                        </div>

                        <!-- Step 7 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">7</span>
                                </div>
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-emerald-500"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-white">Payment Received</h4>
                                    <span class="text-xs text-slate-500">10:38 AM</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Seller confirmed receipt</p>
                            </div>
                        </div>

                        <!-- Step 8 -->
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">8</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-emerald-400">Trade Completed</h4>
                                    <span class="text-xs text-emerald-400">10:39 AM</span>
                                </div>
                                <p class="text-xs text-emerald-400 mt-1">Crypto released to buyer ✓</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Chat Window -->
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl flex flex-col h-full">
                    <!-- Chat Header -->
                    <div class="px-6 py-4 border-b border-slate-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">JD</span>
                                    </div>
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-slate-900"></div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-white">JohnDoe88</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-emerald-400">Online</span>
                                        <span class="text-xs text-slate-500">• 2,341 trades</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 p-6 overflow-y-auto space-y-4">
                        <!-- System Message -->
                        <div class="flex justify-center">
                            <div class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-full">
                                <span class="text-xs text-blue-400">🔒 Trade started - Order #P2P-2024-0812</span>
                            </div>
                        </div>

                        <!-- Seller Message -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">JD</span>
                            </div>
                            <div class="flex-1">
                                <div class="bg-slate-800 rounded-lg p-3 inline-block">
                                    <p class="text-sm text-slate-300">Hi! I'm confirming your order now.</p>
                                </div>
                            </div>
                        </div>

                        <!-- System Alert -->
                        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs text-emerald-400">Vendor confirmed • Escrow received • 3 confirmations</span>
                            </div>
                        </div>

                        <!-- Buyer Message -->
                        <div class="flex items-start gap-3 justify-end">
                            <div class="flex-1">
                                <div class="bg-emerald-600 rounded-lg p-3 inline-block float-right">
                                    <p class="text-sm text-white">Perfect! I see the escrow. Sending payment now.</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">You</span>
                            </div>
                        </div>

                        <!-- System Alert -->
                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs text-blue-400">Buyer marked payment as sent</span>
                            </div>
                        </div>

                        <!-- Buyer Message -->
                        <div class="flex items-start gap-3 justify-end">
                            <div class="flex-1">
                                <div class="bg-emerald-600 rounded-lg p-3 inline-block float-right">
                                    <p class="text-sm text-white">Payment sent via Bank Transfer. Reference: #TRX892341</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">You</span>
                            </div>
                        </div>

                        <!-- Seller Message -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">JD</span>
                            </div>
                            <div class="flex-1">
                                <div class="bg-slate-800 rounded-lg p-3 inline-block">
                                    <p class="text-sm text-slate-300">Payment received! Confirming now...</p>
                                </div>
                            </div>
                        </div>

                        <!-- System Success Message -->
                        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs text-emerald-400">✓ Trade completed successfully • Crypto released</span>
                            </div>
                        </div>

                        <!-- Seller Message -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">JD</span>
                            </div>
                            <div class="flex-1">
                                <div class="bg-slate-800 rounded-lg p-3 inline-block">
                                    <p class="text-sm text-slate-300">Thanks for the smooth trade! 5 stars ⭐</p>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer Message -->
                        <div class="flex items-start gap-3 justify-end">
                            <div class="flex-1">
                                <div class="bg-emerald-600 rounded-lg p-3 inline-block float-right">
                                    <p class="text-sm text-white">Great trade! Thank you! ⭐⭐⭐⭐⭐</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-medium">You</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="px-6 py-4 border-t border-slate-800">
                        <div class="flex items-center gap-3">
                            <input type="text" placeholder="Type a message..."
                                class="flex-1 bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                            <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-colors">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Steps Guide -->
        <div class="space-y-12">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Complete Trading Process</h2>

            <!-- Step 1 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Order Initiated</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 14%</span>
                            <span class="px-2 py-1 bg-amber-500/10 border border-amber-500/20 rounded text-xs text-amber-400">Waiting for vendor confirmation</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            A client creates an order by responding to a vendor's advertisement. The order details including amount, price, and payment method are locked in. The <strong>vendor</strong> (merchant) receives a notification and must confirm whether to proceed with this trade.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Vendor: Confirm order</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Cancel without penalty</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Key Points</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">No funds are locked yet</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Both parties can cancel freely</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Vendor has limited time to respond</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Vendor Confirmed</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 28%</span>
                            <span class="px-2 py-1 bg-blue-500/10 border border-blue-500/20 rounded text-xs text-blue-400">Seller transferring crypto</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The vendor confirms they can fulfill the order. They must now transfer the cryptocurrency to the platform's escrow system. This ensures the buyer that the crypto is available and secured.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Seller: Send crypto (with TxHash)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Cancel without penalty</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Key Points</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Seller must provide transaction hash</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Still can cancel without penalty</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Trade is now active</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Seller Paid (Crypto Sent)</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 42%</span>
                            <span class="px-2 py-1 bg-blue-500/10 border border-blue-500/20 rounded text-xs text-blue-400">Blockchain confirmation</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The seller has initiated the crypto transfer to escrow. The system now monitors the blockchain for transaction confirmation. This automatic verification ensures the funds are actually received before proceeding.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">System: Auto-monitoring blockchain</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Cancel (⚠️ -1 reputation)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-amber-900/20 border border-amber-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-amber-400 mb-2">⚠️ Important Notice</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Both parties can still cancel</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Cancellation costs 1 reputation point</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-amber-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Seller's funds are in transit</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Escrow Received</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 50%</span>
                            <span class="px-2 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded text-xs text-emerald-400">Funds secured</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The blockchain transaction is confirmed and crypto is now securely held in escrow.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Buyer: Confirm escrow receipt</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Buyer: Cancel (⚠️ -1 reputation)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-emerald-900/20 border border-emerald-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-emerald-400 mb-2">✓ Escrow Protection Active</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Funds are 100% secured</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Cannot be withdrawn by seller</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Buyer verification required</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">5</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Buyer Confirms Escrow</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 60%</span>
                            <span class="px-2 py-1 bg-blue-500/10 border border-blue-500/20 rounded text-xs text-blue-400">Ready for payment</span>
                            <span class="px-2 py-1 bg-purple-500/10 border border-purple-500/20 rounded text-xs text-purple-400">2FA Required</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The buyer has confirmed seeing the funds in escrow and is now ready to send the fiat payment. This step requires 2FA authentication for security before the buyer can mark payment as sent.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Buyer: Mark payment sent (2FA)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Buyer: Cancel (⚠️ -1 reputation)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-purple-900/20 border border-purple-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-purple-400 mb-2">🔐 Security Check</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-purple-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">2FA verification required</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-purple-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Prevents accidental confirmation</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-purple-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Buyer should send payment now</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">6</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Buyer Paid</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 75%</span>
                            <span class="px-2 py-1 bg-amber-500/10 border border-amber-500/20 rounded text-xs text-amber-400">Awaiting confirmation</span>
                            <span class="px-2 py-1 bg-red-500/10 border border-red-500/20 rounded text-xs text-red-400">No cancellation</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The buyer has sent the fiat payment and marked it as complete. The seller must now check their payment account and confirm receipt. This step also requires 2FA for security. Neither party can cancel at this critical stage.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Seller: Confirm receipt (2FA)</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">No cancellation allowed</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-red-900/20 border border-red-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-red-400 mb-2">⛔ Critical Stage</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Cannot cancel trade</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Payment is in transit</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-red-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Dispute if issues arise</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 7 -->
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">7</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Seller Received Payment</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-slate-800 rounded text-xs text-slate-400">Progress: 85%</span>
                            <span class="px-2 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded text-xs text-emerald-400">Releasing escrow</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The seller has confirmed receiving the fiat payment. The system now automatically triggers the escrow release process. The cryptocurrency will be transferred to the buyer's wallet.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Available Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">System: Auto-releasing escrow</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">No cancellation possible</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Both: Create dispute (urgent)</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-emerald-900/20 border border-emerald-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-emerald-400 mb-2">🚀 Automatic Process</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Escrow releases automatically</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">No manual intervention needed</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Blockchain transaction initiated</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 8 -->
            <div class="bg-slate-900/60 border border-emerald-500/20 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">Trade Completed</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 bg-emerald-500/20 rounded text-xs text-emerald-400">Progress: 100%</span>
                            <span class="px-2 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded text-xs text-emerald-400">Success</span>
                        </div>

                        <p class="text-sm text-slate-300 mb-4">
                            The trade is complete! The cryptocurrency has been successfully transferred to the buyer's wallet. Both parties can now leave feedback and ratings for each other to help build trust in the community.
                        </p>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-slate-800/50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-white mb-2">Post-Trade Actions</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Leave feedback</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">View transaction details</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        <span class="text-sm text-slate-400">Download receipt</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-emerald-900/20 border border-emerald-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-emerald-400 mb-2">✅ Trade Summary</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Funds successfully transferred</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Trade history updated</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400 flex-shrink-0">•</span>
                                        <span class="text-sm text-slate-400">Reputation points earned</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Features Summary -->
            <div class="bg-gradient-to-r from-emerald-900/20 to-blue-900/20 border border-emerald-500/20 rounded-xl p-6 mt-12">
                <h3 class="text-xl font-bold text-white mb-4">Security Features Throughout the Trade</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-emerald-400 mb-3">Escrow Protection</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Funds locked until trade completes</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Automatic blockchain verification</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Cannot be withdrawn by seller</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-400 mb-3">2FA Requirements</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="text-blue-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Step 5: Buyer payment confirmation</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-blue-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Step 6: Seller receipt confirmation</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-blue-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Prevents accidental actions</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-amber-400 mb-3">Reputation System</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="text-amber-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">-1 point for late cancellation</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-amber-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Post-trade ratings</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-amber-400 flex-shrink-0">•</span>
                                <span class="text-sm text-slate-400">Trade history tracking</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection