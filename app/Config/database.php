<?php

return [
    'driver' => getenv('DB_CONNECTION') ?: 'mysql',
    'path' => __DIR__ . '/../../storage/hansen_educacional.db',
    'cache' => true,

    // MySQL
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'hansen_educacional',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    'collation' => getenv('DB_COLLATION') ?: 'utf8mb4_unicode_ci',

    // Redis (usar quando .env for criado)
    'redis' => [
        'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
        'port' => getenv('REDIS_PORT') ?: '6379',
        'password' => getenv('REDIS_PASSWORD') ?: '',
        'database' => getenv('REDIS_DB') ?: '0',
        'persistent_id' => 'hansen_redis_pool'
    ]
];
