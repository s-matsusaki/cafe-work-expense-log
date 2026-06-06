import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/monthly-report.jsx',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        // docker環境で127.0.0.1を指定してもnodeコンテナ自身を指すので接続できない
        // Docker環境では外部から接続できるように全IPで待ち受ける
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,

        // ブラウザ側・HMR側では localhost を使わせる
        hmr: {
            host: 'localhost',
            port: 5173,
        },

        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
