<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/graph.php';

$message = '';
$messageType = 'error';

// Register a new user and store the password as a secure hash.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        [$ok, $message] = register_user($nombre, $usuario, $password);
        $messageType = $ok ? 'success' : 'error';
    } catch (Throwable $exception) {
        $message = 'No se pudo registrar. Revisa que SQLite este disponible.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="public/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <span class="eyebrow">Nuevo usuario</span>
            <h1>Crear cuenta</h1>
            <?php if ($message !== ''): ?>
                <!-- The same block is used for success and error messages. -->
                <p class="alert alert-<?= e($messageType) ?>"><?= e($message) ?></p>
            <?php endif; ?>
            <form method="post" class="form-stack">
                <label>
                    Nombre
                    <input class="form-control" type="text" name="nombre" required>
                </label>
                <label>
                    Usuario
                    <input class="form-control" type="text" name="usuario" autocomplete="username" required>
                </label>
                <label>
                    Contrasena
                    <input class="form-control" type="password" name="password" autocomplete="new-password" required>
                </label>
                <button type="submit">Registrar</button>
            </form>
            <a class="text-link" href="login.php">Volver al login</a>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>
