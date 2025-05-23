const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-activitylog',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-activitylog',
            input: [
                __dirname + '/Resources/assets/sass/app.scss',
                __dirname + '/Resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
