<?php
// L9it Backend Configuration
return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'name' => $_ENV['DB_DATABASE'] ?? 'l9it',
        'user' => $_ENV['DB_USERNAME'] ?? 'l9it',
        'password' => $_ENV['DB_PASSWORD'] ?? 'l9it',
        'charset' => 'utf8mb4',
    ],
    'jwt' => [
        'secret_key' => $_ENV['JWT_SECRET_KEY'] ?? 'change-me',
        'ttl' => 3600,
    ],
    'app' => [
        'env' => $_ENV['APP_ENV'] ?? 'dev',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],
    'upload' => [
        'max_size' => (int)($_ENV['MAX_IMAGE_SIZE'] ?? 5242880),
        'allowed_types' => explode(',', $_ENV['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,webp'),
    ],
];
