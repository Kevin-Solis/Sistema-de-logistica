<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Opens one shared SQLite connection for the whole request.
function db(): PDO
{
    static $pdo = null;

    // Reuse the same connection instead of opening SQLite more than once.
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = db_config();
    $directory = dirname($config['path']);

    // Create the database folder automatically on a fresh computer.
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    // SQLite stores the complete database in one local file.
    $pdo = new PDO('sqlite:' . $config['path'], null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Foreign keys are disabled by default in SQLite, so we enable them.
    $pdo->exec('PRAGMA foreign_keys = ON');

    return $pdo;
}

// Creates the users table if the SQLite file is empty.
function ensure_users_table(): void
{
    db()->exec(
        "CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre VARCHAR(100) NOT NULL,
            usuario VARCHAR(60) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );
}

// Inserts the first test user only once.
function seed_admin_user(): void
{
    ensure_users_table();

    $stmt = db()->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute(['admin']);

    if ($stmt->fetch()) {
        return;
    }

    // The default password is stored as a hash, never as plain text.
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = db()->prepare('INSERT INTO usuarios (nombre, usuario, password_hash) VALUES (?, ?, ?)');
    $stmt->execute(['Administrador', 'admin', $hash]);
}
