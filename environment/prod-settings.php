<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
date_default_timezone_set('UTC');

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
                'client_id' => '454454942361-umnh6drfmmc5sjonpivlm536os20tokb.apps.googleusercontent.com',
                'client_secret' => 'AVrWcKSuJOj9ko-piec6UIvR',
                'application_name' => 'Hunt BIDS',
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

        'hybridauth' => [
			"base_url" => "http://ian.dev-huntbid.huntcorp.co/hybrid/",
			"providers" => [
				"Google" => [
					"enabled" => true,
					"keys" => ["id" => "454454942361-umnh6drfmmc5sjonpivlm536os20tokb.apps.googleusercontent.com", "secret" => "AVrWcKSuJOj9ko-piec6UIvR"],
				],
			],
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages]
			"debug_mode" => true,
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			"debug_file" => __DIR__ . '/../logs/hybridauth.log',
        ],
    ],
];
