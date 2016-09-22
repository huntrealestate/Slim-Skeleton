<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        
        // OAuth Credentials
        'oAuthCreds' => [
            'google' => [
                'client_id' => 'your_client_id',
                'client_secret' => 'your_client_secret',
                'application_name' => 'Google PHP Slim-Skeleton Framework',
            ]
        ],
        
        'db' => [
            'huntbiddb' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'huntbid',
                'username' => 'huntbid_rw',
                'password' => '8jU&5eNGk4s8Z!xB',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],
        ],
    ],
];
