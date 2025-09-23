import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    /* Optional: Kalau lo punya PostCSS plugins tambahan (e.g., autoprefixer), tambah di sini—v4 handle Tailwind otomatis */
    css: {
        postcss: {
            plugins: [
                // require('autoprefixer'), // Uncomment kalau butuh
            ],
        },
    },
});