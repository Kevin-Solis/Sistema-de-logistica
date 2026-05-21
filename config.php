<?php
declare(strict_types=1);

const APP_NAME = 'Sistema de Logistica';
const DB_PATH = __DIR__ . '/database/sistema_logistica.sqlite';

// Reads an environment variable and falls back to the project default value.
function env_value(string $key, string $default = ''): string
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

// Keeps the database settings in one place so the rest of the app stays simple.
function db_config(): array
{
    return [
        'path' => env_value('DB_PATH', DB_PATH),
    ];
}
