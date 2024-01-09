<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_TEST_HOST', '127.0.0.1'),
            'port' => env('DB_TEST_PORT', '3306'),
            'database' => env('DB_TEST_DATABASE', 'your_testing_database'),
            'username' => env('DB_TEST_USERNAME', 'your_testing_user'),
            'password' => env('DB_TEST_PASSWORD', 'your_testing_password')
        ],
    ],
];
