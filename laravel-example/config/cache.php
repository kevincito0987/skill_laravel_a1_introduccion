<?php

use Illuminate\Support\Str;

return [

    // Esta es la conexión predeterminada. Laravel usará lo que definas en tu .env (por ejemplo, 'mysql')
    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        // Tu conexión para MySQL dentro de Docker
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL_MYSQL'),
            'host' => env('DB_HOST_MYSQL', 'db'),
            'port' => env('DB_PORT_MYSQL', '3306'),
            'database' => env('DB_DATABASE_MYSQL', 'laravel'),
            'username' => env('DB_USERNAME_MYSQL', 'laravel'),
            'password' => env('DB_PASSWORD_MYSQL', 'admin'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Tu conexión para PostgreSQL dentro de Docker
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL_PGSQL'),
            'host' => env('DB_HOST_PGSQL', 'pgsql'),
            'port' => env('DB_PORT_PGSQL', '5432'),
            'database' => env('DB_DATABASE_PGSQL', 'laravel'),
            'username' => env('DB_USERNAME_PGSQL', 'laravel'),
            'password' => env('DB_PASSWORD_PGSQL', 'admin'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        // Esta es la conexión para Supabase
        'supabase' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL_SUPABASE'),
            'host' => env('DB_HOST_SUPABASE'),
            'port' => env('DB_PORT_SUPABASE'),
            'database' => env('DB_DATABASE_SUPABASE'),
            'username' => env('DB_USERNAME_SUPABASE'),
            'password' => env('DB_PASSWORD_SUPABASE'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
        
        // Esta es la conexión para SQL Server
        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];