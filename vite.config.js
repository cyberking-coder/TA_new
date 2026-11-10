import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Remote font auto-resolution is disabled in this container (outbound DNS for
// fonts.bunny.net is denied by the agent proxy). Barlow and Source Sans 3 will
// be self-hosted per the PRD before launch; for Phase 1 we fall back to the
// system sans stack declared in resources/css/app.css.

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
