<?php
declare(strict_types=1);

const APP_NAME = 'Sistema de Logistica';
const DB_PATH = __DIR__ . '/database/sistema_logistica.sqlite';

function conexion(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $directorio = dirname(DB_PATH);

    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function preparar_base_datos(): void
{
    conexion()->exec(
        "CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre VARCHAR(100) NOT NULL,
            usuario VARCHAR(60) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $stmt = conexion()->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute(['admin']);

    if ($stmt->fetch()) {
        return;
    }

    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = conexion()->prepare('INSERT INTO usuarios (nombre, usuario, password_hash) VALUES (?, ?, ?)');
    $stmt->execute(['Administrador', 'admin', $hash]);
}

function buscar_usuario(string $usuario): ?array
{
    preparar_base_datos();

    $stmt = conexion()->prepare('SELECT id, nombre, usuario, password_hash FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch();

    return $fila === false ? null : $fila;
}
