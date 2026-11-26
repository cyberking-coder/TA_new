import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Public site runs off resources/css/public.css + resources/js/public.js
// (the former field-guide.* files). The Filament admin panel takes its
// override layer from resources/css/admin.css. resources/css/app.css is
// the Tailwind utility bundle still used by the alerts / study-reset
// views.

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/public.css',
                'resources/js/public.js',
                'resources/css/admin.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
