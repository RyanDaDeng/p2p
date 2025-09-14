import { ref, computed, onMounted } from 'vue';

const isPWA = ref(false);
const isStandalone = ref(false);
const isInstallable = ref(false);
const deferredPrompt = ref(null);

export function usePWA() {
    onMounted(() => {
        // Check if app is running as PWA
        checkPWAStatus();

        // Listen for install prompt
        window.addEventListener('beforeinstallprompt', handleInstallPrompt);

        // Listen for app installed event
        window.addEventListener('appinstalled', handleAppInstalled);
    });

    const checkPWAStatus = () => {
        // Method 1: Check display mode
        const isStandaloneMode = window.matchMedia('(display-mode: standalone)').matches;

        // Method 2: Check if launched from home screen (iOS)
        const isStandaloneIOS = window.navigator.standalone === true;

        // Method 3: Check URL parameters (some browsers add this)
        const isFromHomeScreen = window.location.search.includes('mode=standalone');

        // Method 4: Check referrer (Android)
        const isAndroidPWA = document.referrer.includes('android-app://');

        // Method 5: Check if running in minimal-ui mode
        const isMinimalUI = window.matchMedia('(display-mode: minimal-ui)').matches;

        // Method 6: Check if running in fullscreen mode
        const isFullscreen = window.matchMedia('(display-mode: fullscreen)').matches;

        // Determine if running as PWA
        isPWA.value = isStandaloneMode || isStandaloneIOS || isFromHomeScreen || isAndroidPWA || isMinimalUI || isFullscreen;
        isStandalone.value = isStandaloneMode || isStandaloneIOS;
    };

    const handleInstallPrompt = (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();

        // Stash the event so it can be triggered later
        deferredPrompt.value = e;

        // Update installable status
        isInstallable.value = true;
    };

    const handleAppInstalled = () => {
        // Clear the deferred prompt
        deferredPrompt.value = null;
        isInstallable.value = false;

        // Recheck PWA status
        checkPWAStatus();
    };

    const promptInstall = async () => {
        if (!deferredPrompt.value) {
            return false;
        }

        // Show the install prompt
        deferredPrompt.value.prompt();

        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.value.userChoice;

        // Clear the deferred prompt
        deferredPrompt.value = null;
        isInstallable.value = false;

        return outcome === 'accepted';
    };

    const shouldShowAppButton = computed(() => {
        // Show app button only if:
        // 1. Not running as PWA
        // 2. Not on iOS (iOS doesn't support PWA install prompts)
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        return !isPWA.value && !isIOS;
    });

    const canInstallPWA = computed(() => {
        // Can install if:
        // 1. Not already running as PWA
        // 2. Browser supports installation (has beforeinstallprompt)
        // 3. Not on iOS (iOS has different installation method)
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        return !isPWA.value && isInstallable.value && !isIOS;
    });

    return {
        isPWA,
        isStandalone,
        isInstallable,
        shouldShowAppButton,
        canInstallPWA,
        promptInstall,
        checkPWAStatus
    };
}