<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/graph.php';

// This page prepares SQLite and creates the default admin user.
try {
    seed_admin_user();
    $message = 'Base de datos SQLite lista. Usuario inicial: admin / admin123';
    $type = 'success';
} catch (Throwable $exception) {
    $message = 'No se pudo preparar la base de datos: ' . $exception->getMessage();
    $type = 'error';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicializar DB</title>
    <link rel="stylesheet" href="public/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1>Inicializacion SQLite</h1>
            <p class="alert alert-<?= e($type) ?>"><?= e($message) ?></p>
            <a class="link-button" href="login.php">Ir al login</a>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
