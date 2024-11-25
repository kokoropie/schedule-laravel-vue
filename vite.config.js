import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

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
    ],
    build: {
        // manifest: true,
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[hash:16].js',
                entryFileNames: 'js/[hash:16].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[hash:16][extname]';
                    }

                    return 'images/[hash:16][extname]';
                },
                // manualChunks(id) {
                //     if (id.includes('flowbite')) {
                //         return 'flowbite';
                //     }
                //     if (id.includes('axios')) {
                //         return 'axios';
                //     }
                //     if (id.includes('iconify')) {
                //         return 'iconify';
                //     }
                //     if (id.includes('apexcharts')) {
                //         return 'apexcharts';
                //     }
                //     if (id.includes('vue-') || id.includes('vue3-')) {
                //         return 'vue3';
                //     }
                //     if (id.includes('node_modules')) {
                //         return 'vendor';
                //     }
                // }
            }
        }
    }
});
