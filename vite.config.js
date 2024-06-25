import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        base: 'https://flasy-api-jrqtpa5u6q-et.a.run.app/', // Replace with your Cloud Run URL
      }
});
