import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// import react from '@vitejs/plugin-react';
import vue from "@vitejs/plugin-vue";
import path from "path";

import { dynamicBase } from 'vite-plugin-dynamic-base';

export default defineConfig({
    //base: process.env.NODE_ENV === "production" ? "/__dynamic_base__/" : "/",
    // base: './',
    plugins: [
        dynamicBase({
            /* options */
            publicPath: 'window.__dynamic_base__',
        }),
        laravel({
            input: [
                // 'resources/sass/app.scss',
                "resources/js/app.js",

                // website
                "resources/sass/website/theme.scss",
                "resources/js/website/main.js",
            ],
            refresh: [
                {
                    //paths: ['resources/**'],
                    paths: ["resources/js/**", "resources/sass/**"],
                    config: { delay: 300 },
                },
            ],
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // '@': '/resources/js',
            ziggy: path.resolve("vendor/tightenco/ziggy/dist"),
            "ziggy-vue": path.resolve("vendor/tightenco/ziggy/src/js/vue"),
        },
    },
});
