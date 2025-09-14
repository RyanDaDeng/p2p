import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite'; // Import the Tailwind CSS plugin
import { VitePWA } from 'vite-plugin-pwa';


export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(), // Add the Tailwind CSS plugin
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            workbox: {
                runtimeCaching: [
                    {
                        urlPattern: ({ request }) => true,
                        handler: 'NetworkOnly',
                        options: {
                            cacheName: 'no-cache',
                        },
                    },
                ],
                skipWaiting: true,
                clientsClaim: true,
            },
            manifest: {
                name: 'P2PCoinSwap.com',
                short_name: 'P2PCoinSwap',
                description: 'P2P Cryptocurrency Trading Platform',
                theme_color: '#10b981',
                background_color: '#ffffff',
                display: 'standalone',
                orientation: 'portrait',
                start_url: '/market',
                scope: '/',
                icons: [
                    {
                        src: '/logo.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/logo.png',
                        sizes: '512x512',
                        type: 'image/png'
                    },
                    {
                        src: '/logo.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable'
                    }
                ]
            }
        })
    ],
});
