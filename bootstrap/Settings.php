<?php
$settings = [
        'settings' => [
            'displayErrorDetails' => true,
            'n'=>'30',
            'logger' => [
                'name' => 'slim-app',
                'level' => Monolog\Logger::DEBUG,
                'path' => __DIR__ . '/../logs/error.log',
            ],
            // Database connection settings
            "db" => [
                'driver' => 'mysql',
                'host' => 'localhost',
               // 'database' => 'slimdb',
		'database' => 'tempodb',
                'username' => 'root',
                'password' => 'mysql123',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => ''
                
            ],
        ],
    ];
