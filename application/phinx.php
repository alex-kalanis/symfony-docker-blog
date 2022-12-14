<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/migrations/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_SOURCE'] ?? 'k-symfony-blog-mariadb',
            'name' => $_ENV['DB_NAME'] ?? 'dummysymfony',
            'user' => $_ENV['DB_USER'] ?? 'kalasymfony',
            'pass' => $_ENV['DB_PASS'] ?? 'kalasymfony654',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_SOURCE'] ?? 'k-symfony-blog-mariadb',
            'name' => $_ENV['DB_NAME'] ?? 'dummysymfony',
            'user' => $_ENV['DB_USER'] ?? 'kalasymfony',
            'pass' => $_ENV['DB_PASS'] ?? 'kalasymfony654',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_SOURCE'] ?? 'k-symfony-blog-mariadb',
            'name' => 'testsymfony',
            'user' => $_ENV['DB_USER'] ?? 'kalasymfony',
            'pass' => $_ENV['DB_PASS'] ?? 'kalasymfony654',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
