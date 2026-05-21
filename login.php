<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/graph.php';

$error = '';

// Process the login form only when the user submits credentials.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        // login_user verifies the password hash and starts the session.
        if (login_user($usuario, $password)) {
            header('Location: panel.php');
            exit;
        }

        $error = 'Usuario o contrasena incorrectos.';
    } catch (Throwable $exception) {
        $error = 'No se pudo abrir SQLite. Revisa permisos o ejecuta init-db.php.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="public/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <span class="eyebrow">Acceso seguro</span>
            <h1><?= e(APP_NAME) ?></h1>
            <?php if ($error !== ''): ?>
                <!-- Show a short error without exposing technical details. -->
                <p class="alert alert-error"><?= e($error) ?></p>
            <?php endif; ?>
            <form method="post" class="form-stack">
                <label>
                    Usuario
                    <input class="form-control" type="text" name="usuario" autocomplete="username" required>
                </label>
                <label>
                    Contrasena
                    <input class="form-control" type="password" name="password" autocomplete="current-password" required>
                </label>
                <button type="submit">Ingresar</button>
            </form>
            <p class="helper-text">Usuario inicial despues de inicializar: admin / admin123</p>
            <a class="text-link" href="registro.php">Crear una cuenta</a>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>
