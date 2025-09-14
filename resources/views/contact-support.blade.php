@extends('layouts.app')

@section('title', 'Contact Support - P2PCoinSwap')

@section('content')
    <!-- Header -->
    <div class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Contact Support</h1>
                <p class="text-lg text-slate-400">Get help with your P2P trading questions and issues</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Telegram Support -->
        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-8 text-center">
            <div class="mb-6">
                <!-- Telegram Icon -->
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Telegram Support</h2>
                <p class="text-slate-400 mb-6">Join our official support channel for real-time assistance</p>
            </div>

            <a href="https://t.me/+C56TwooPC5A5ZDU1" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                </svg>
                Join Telegram Support
            </a>

            <div class="mt-6 text-sm text-slate-500">
                <p>Available 24/7 for all your trading support needs</p>
            </div>
        </div>

        <!-- Support Information -->
        <div class="mt-12 grid md:grid-cols-2 gap-8">
            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <div class="w-1 h-4 bg-emerald-500 rounded"></div>
                    Common Issues
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Trade disputes and resolution</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Account verification problems</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Payment method issues</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Technical platform problems</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Escrow and wallet questions</span>
                    </li>
                </ul>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <div class="w-1 h-4 bg-blue-500 rounded"></div>
                    Before Contacting Support
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2">
                        <span class="text-blue-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Check the <a href="/trade-flow-guide" class="text-blue-400 hover:text-blue-300">Trade Flow Guide</a></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-blue-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Have your order number ready</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-blue-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Prepare screenshots if applicable</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-blue-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Review <a href="/terms" class="text-blue-400 hover:text-blue-300">Terms of Service</a></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-blue-400 flex-shrink-0">•</span>
                        <span class="text-sm text-slate-400">Check your account settings</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
@endsection