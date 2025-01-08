import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    input: ['resources/css/filament/admin/theme.css'],
    plugins: [
        react(),
        laravel({
            input: [
                'resources/css/filament/admin/theme.css',
                'resources/css/app.css',
                'resources/js/app.jsx',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
