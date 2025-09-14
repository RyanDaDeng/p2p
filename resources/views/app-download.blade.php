@extends('layouts.app')

@section('title', 'Install APP - P2PCoinSwap')

@section('content')
    <div class="min-h-screen px-4 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <img src="/logo.png" alt="P2PCoinSwap" class="h-14 w-14 sm:h-16 sm:w-16 mx-auto mb-3">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">P2PCoinSwap APP Installation</h1>
                <p class="text-sm text-gray-600 dark:text-slate-400">Install our app for the best trading experience</p>
            </div>

            <!-- Installation Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-w-3xl mx-auto">
                <!-- APP Installation (Desktop/Android) -->
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg p-4 sm:p-5 border border-gray-200 dark:border-slate-800">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-green-100 dark:bg-green-500/10 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18c0 .55.45 1 1 1h1v3.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V19h2v3.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V19h1c.55 0 1-.45 1-1V8H6v10z"/>
                                <path d="M3.5 8C2.67 8 2 8.67 2 9.5v7c0 .83.67 1.5 1.5 1.5S5 17.33 5 16.5v-7C5 8.67 4.33 8 3.5 8zm17 0c-.83 0-1.5.67-1.5 1.5v7c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5v-7c0-.83-.67-1.5-1.5-1.5z"/>
                                <path d="M15.53 2.16l1.3-1.3c.2-.2.2-.51 0-.71s-.51-.2-.71 0l-1.48 1.48C13.85 1.23 12.95 1 12 1s-1.85.23-2.64.63L7.88.15c-.2-.2-.51-.2-.71 0s-.2.51 0 .71l1.3 1.3C6.97 3.26 6 5.01 6 7h12c0-1.99-.97-3.74-2.47-4.84zM10 5H9V4h1v1zm5 0h-1V4h1v1z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Android & Desktop</h2>
                    </div>

                    <div class="space-y-2">
                        <!-- Chrome/Edge Installation -->
                        <div class="border-l-4 border-blue-500 pl-3">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">Chrome / Edge Browser</h3>
                            <ol class="text-sm text-gray-600 dark:text-slate-400 space-y-1">
                                <li>1. Click the <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-slate-800 text-xs">⋮</span> menu in the address bar</li>
                                <li>2. Select "Install P2PCoinSwap"</li>
                                <li>3. Click "Install" to confirm</li>
                            </ol>
                        </div>

                        <!-- Firefox Installation -->
                        <div class="border-l-4 border-orange-500 pl-3">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">Firefox Browser</h3>
                            <ol class="text-sm text-gray-600 dark:text-slate-400 space-y-1">
                                <li>1. Click the <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-slate-800 text-xs">+</span> icon in the address bar</li>
                                <li>2. Select "Install"</li>
                            </ol>
                        </div>

                        <!-- Mobile Chrome -->
                        <div class="border-l-4 border-green-500 pl-3">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">Mobile Chrome</h3>
                            <ol class="text-sm text-gray-600 dark:text-slate-400 space-y-1">
                                <li>1. Tap the <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-slate-800 text-xs">⋮</span> menu in the top right</li>
                                <li>2. Select "Add to Home screen"</li>
                                <li>3. Enter a name and tap "Add"</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Install Button (for browsers that support it) -->
                    <button id="installBtn" class="mt-3 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors hidden">
                        Install Now
                    </button>
                </div>

                <!-- iOS Installation -->
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg p-4 sm:p-5 border border-gray-200 dark:border-slate-800">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-gray-100 dark:bg-slate-800 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700 dark:text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-base sm:text-lg font-semibold text-gray-900 dark:text-white">iOS Safari</h2>
                    </div>

                    <ol class="text-sm text-gray-600 dark:text-slate-400 space-y-2">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 font-bold text-emerald-600 dark:text-emerald-400 mr-2">1.</span>
                            <span>Open this website in Safari browser</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 font-bold text-emerald-600 dark:text-emerald-400 mr-2">2.</span>
                            <span>Tap the share button
                                <svg class="inline w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 5l-1.42 1.42-1.59-1.59V16h-1.98V4.83L9.42 6.42 8 5l4-4 4 4zm4 5v11c0 1.1-.9 2-2 2H6a2 2 0 01-2-2V10c0-1.11.89-2 2-2h3v2H6v11h12V10h-3V8h3a2 2 0 012 2z"/>
                                </svg>
                                at the bottom
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 font-bold text-emerald-600 dark:text-emerald-400 mr-2">3.</span>
                            <span>Scroll down and select "Add to Home Screen"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 font-bold text-emerald-600 dark:text-emerald-400 mr-2">4.</span>
                            <span>Tap "Add" in the top right corner</span>
                        </li>
                    </ol>

                    <div class="mt-3 p-2.5 bg-amber-50 dark:bg-amber-500/10 rounded-lg">
                        <p class="text-xs text-amber-800 dark:text-amber-400">
                            <strong>Note:</strong> iOS requires Safari browser to add to home screen
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // APP Install Handler
        let deferredPrompt;
        const installBtn = document.getElementById('installBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show install button
            if (installBtn) {
                installBtn.classList.remove('hidden');
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;

                    if (outcome === 'accepted') {
                        console.log('APP installed');
                        installBtn.classList.add('hidden');
                    }

                    deferredPrompt = null;
                }
            });
        }

        // Check if already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            // Already installed, redirect to main app
            window.location.href = '/market';
        }
    </script>
@endpush