import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { registerSW } from 'virtual:pwa-register';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Register PWA Service Worker
const updateSW = registerSW({
    onNeedRefresh() {
        if (confirm('New content available. Reload?')) {
            updateSW(true);
        }
    },
    onOfflineReady() {
        console.log('PWA is ready for offline use');
    },
});

// Dynamic theme-color for PWA status bar
function updateThemeColor() {
    const isDark = document.documentElement.classList.contains('dark');
    const themeColorMeta = document.querySelector('meta[name="theme-color"]:not([media])');
    const lightMeta = document.querySelector('meta[name="theme-color"][media="(prefers-color-scheme: light)"]');
    const darkMeta = document.querySelector('meta[name="theme-color"][media="(prefers-color-scheme: dark)"]');

    // Remove existing theme-color meta tag without media query
    if (themeColorMeta) {
        themeColorMeta.remove();
    }

    // Add new theme-color meta tag based on current theme
    const newThemeColor = document.createElement('meta');
    newThemeColor.name = 'theme-color';
    newThemeColor.content = isDark ? '#0f172a' : '#ffffff';
    document.head.appendChild(newThemeColor);

    // Also update Apple status bar style
    const statusBarMeta = document.querySelector('meta[name="apple-mobile-web-app-status-bar-style"]');
    if (statusBarMeta) {
        statusBarMeta.content = isDark ? 'black-translucent' : 'default';
    }
}

// Watch for theme changes
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            updateThemeColor();
        }
    });
});

// Start observing the document element for class changes
observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
});

// Initial theme color update
updateThemeColor();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
