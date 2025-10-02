import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// GANTI DENGAN IP ANDA YANG DITEMUKAN DARI ipconfig
const host = '192.168.137.1'; 

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: host, // Menggunakan IP spesifik
        port: 5173,
        hmr: {
            host: host, // Memaksa HMR juga menggunakan IP ini
        }
    },
});