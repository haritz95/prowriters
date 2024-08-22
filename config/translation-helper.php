<?php

return [
    /**
     * Directories to scan for missing translation keys.
     */
    'scan_directories' => [
        app_path(),
        resource_path('js/components'),
        resource_path('views'),
        database_path('seeds')
        //resource_path('assets'),
    ],

    /**
     * File extensions to scan from.
     */
    'file_extensions' => [
        'php',
        //'js',
        'vue',
    ],

    /**
     * Directory where your JSON translation files are located.
     */
    'output_directory' => lang_path(),

    /**
     * Translation helper methods to scan
     * for in your application's code.
     */
    'translation_methods' => [
        //'lang',
        '__',
        // 'trans'
    ],
];
