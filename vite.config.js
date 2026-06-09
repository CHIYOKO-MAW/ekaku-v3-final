import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build',
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],
                    chart: ['chart.js'],
                },
            },
        },
        cssCodeSplit: true,
        minify: 'esbuild',
        sourcemap: false,
    },
    esbuild: {
        drop: ['console', 'debugger'],
    },
});