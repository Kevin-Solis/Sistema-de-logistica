<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';

session_start();
preparar_base_datos();

if (isset($_SESSION['usuario'])) {
    header('Location: panel.php');
    exit;
}

$errorLogin = ($_GET['error'] ?? '') === '1';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="public/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1><?= e(APP_NAME) ?></h1>
            <?php if ($errorLogin): ?>
                <p class="alert alert-error">Usuario o clave incorrectos.</p>
            <?php endif; ?>
            <form method="post" action="validarlogin.php" class="form-stack">
                <label>
                    Usuario
                    <input class="form-control" type="text" name="usuario" autocomplete="username" required>
                </label>
                <label>
                    Clave
                    <input class="form-control" type="password" name="clave" autocomplete="current-password" required>
                </label>
                <button type="submit">Ingresar</button>
            </form>
            <p class="helper-text">Usuario inicial: admin / admin123</p>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
