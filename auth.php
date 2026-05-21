<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

// Starts the PHP session only when it has not been started yet.
function start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

// Returns the logged user stored in the session, or null if nobody is logged in.
function current_user(): ?array
{
    start_session();
    return $_SESSION['usuario'] ?? null;
}

// Protects private pages from users that are not authenticated.
function require_login(): void
{
    if (current_user() === null) {
        header('Location: login.php');
        exit;
    }
}

// Checks a username and password against the hashed password in SQLite.
function login_user(string $usuario, string $password): bool
{
    ensure_users_table();

    $stmt = db()->prepare('SELECT id, nombre, usuario, password_hash FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    // password_verify compares the plain password with the stored hash safely.
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    // Regenerate the session ID after login to reduce session fixation risk.
    start_session();
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id' => (int) $user['id'],
        'nombre' => $user['nombre'],
        'usuario' => $user['usuario'],
    ];

    return true;
}

// Creates a new user and stores only the hashed password.
function register_user(string $nombre, string $usuario, string $password): array
{
    ensure_users_table();

    if (strlen($password) < 6) {
        return [false, 'La contrasena debe tener al menos 6 caracteres.'];
    }

    // PASSWORD_DEFAULT lets PHP choose the recommended hashing algorithm.
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = db()->prepare('INSERT INTO usuarios (nombre, usuario, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $usuario, $hash]);
    } catch (PDOException $exception) {
        // SQLite uses code 23000 for unique constraint errors.
        if ($exception->getCode() === '23000') {
            return [false, 'Ese usuario ya existe.'];
        }

        throw $exception;
    }

    return [true, 'Usuario registrado correctamente.'];
}

// Clears the session when the user logs out.
function logout_user(): void
{
    start_session();
    $_SESSION = [];
    session_destroy();
}
