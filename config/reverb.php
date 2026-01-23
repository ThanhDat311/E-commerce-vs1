<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Reverb Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration array contains the settings needed to run Laravel
    | Reverb. By default, Laravel will detect the appropriate database
    | connection settings based on your application configuration.
    |
    */

    'apps' => [
        [
            'key' => env('REVERB_APP_KEY', 'your-app-key'),
            'secret' => env('REVERB_APP_SECRET', 'your-app-secret'),
            'app_id' => env('REVERB_APP_ID', '1'),
            'options' => [
                'cluster' => env('REVERB_CLUSTER', 'mt1'),
                'host' => env('REVERB_HOST', 'localhost'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
            ],
            'capacity' => null,
            'encryption' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Host & Port
    |--------------------------------------------------------------------------
    |
    | This package comes with a WebSocket server that you can use to serve
    | your application. To change the host or port that it listens on you
    | may set the options here.
    |
    */

    'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
    'port' => env('REVERB_SERVER_PORT', 8080),

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | Optionally, you may wish to use a different database connection for
    | Reverb connections. You may specify the connection name here. By
    | default, this uses your default database connection.
    |
    */

    'database' => env('REVERB_DB_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | SSL Certificate & Key
    |--------------------------------------------------------------------------
    |
    | If you wish to secure your WebSocket connection using SSL, you may
    | specify the path to your SSL certificate and key here. These paths
    | should be relative to the application root.
    |
    */

    'ssl_cert' => env('REVERB_SSL_CERT', null),
    'ssl_key' => env('REVERB_SSL_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Pulse
    |--------------------------------------------------------------------------
    |
    | You may configure your Reverb integration with Laravel Pulse by setting
    | the Pulse option to true. This will automatically publish your metrics
    | to the Pulse integration.
    |
    */

    'pulse' => env('REVERB_PULSE', true),

];
