<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'backup' => [
            'driver' => 'mysql',
            'url' => env('BK_DATABASE_URL'),
            'host' => env('BK_DB_HOST', '127.0.0.1'),
            'port' => env('BK_DB_PORT', '3306'),
            'database' => env('BK_DB_DATABASE', 'forge'),
            'username' => env('BK_DB_USERNAME', 'forge'),
            'password' => env('BK_DB_PASSWORD', ''),
            'unix_socket' => env('BK_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('BK_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'reports' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('RP_DB_DATABASE', 'forge'),
            'username' => env('RP_DB_USERNAME', 'forge'),
            'password' => env('RP_DB_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metatable1' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_1_DATABASE', 'forge'),
            'username' => env('DBVM_1_USERNAME', 'forge'),
            'password' => env('DBVM_1_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metatable2' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_2_DATABASE', 'forge'),
            'username' => env('DBVM_2_USERNAME', 'forge'),
            'password' => env('DBVM_2_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metatable3' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_3_DATABASE', 'forge'),
            'username' => env('DBVM_3_USERNAME', 'forge'),
            'password' => env('DBVM_3_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metatabler' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_R_DATABASE', 'forge'),
            'username' => env('DBVM_R_USERNAME', 'forge'),
            'password' => env('DBVM_R_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metatable17' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_17_DATABASE', 'forge'),
            'username' => env('DBVM_17_USERNAME', 'forge'),
            'password' => env('DBVM_17_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metaanalisis' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_ANALISIS_DATABASE', 'forge'),
            'username' => env('DBVM_ANALISIS_USERNAME', 'forge'),
            'password' => env('DBVM_ANALISIS_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metasituacion' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_SITUACION_DATABASE', 'forge'),
            'username' => env('DBVM_SITUACION_USERNAME', 'forge'),
            'password' => env('DBVM_SITUACION_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'metacobranzas' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_COBRANZAS_DATABASE', 'forge'),
            'username' => env('DBVM_COBRANZAS_USERNAME', 'forge'),
            'password' => env('DBVM_COBRANZAS_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'bandejas' => [
            'driver' => 'mysql',
            'url' => env('RP_DATABASE_URL'),
            'host' => env('RP_DB_HOST', '127.0.0.1'),
            'port' => env('RP_DB_PORT', '3306'),
            'database' => env('DBVM_BANDEJAS_DATABASE', 'forge'),
            'username' => env('DBVM_BANDEJAS_USERNAME', 'forge'),
            'password' => env('DBVM_BANDEJAS_PASSWORD', ''),
            'unix_socket' => env('RP_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('RP_MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', ''),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', ''),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
