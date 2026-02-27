<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Display Settings
    |--------------------------------------------------------------------------
    |
    | In production, it's recommended to set this to false
    | For cPanel debugging, you might want to set this to true temporarily
    |
    */
    'display_errors' => true,

    /*
    |--------------------------------------------------------------------------
    | Error Logging Settings
    |--------------------------------------------------------------------------
    |
    | Log all errors to storage/logs/laravel.log
    |
    */
    'log' => [
        'enabled' => true,
        'level' => 'debug',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Error Pages
    |--------------------------------------------------------------------------
    |
    | Define custom error pages for different HTTP status codes
    |
    */
    'custom_pages' => [
        '404' => 'errors.404',
        '500' => 'errors.500',
        '403' => 'errors.403',
    ],
];
