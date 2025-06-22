import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx', // Aponta para app.jsx
            ],
            refresh: true,
        }),
        react(), // Necess\u00e1rio para processar arquivos .jsx
    ],
});
