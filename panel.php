<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';

session_start();

if (($_GET['accion'] ?? '') === 'salir') {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuarioActual = $_SESSION['usuario'];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="public/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1>Login exitoso</h1>
            <p class="alert alert-success">
                Bienvenido, <?= e($usuarioActual['nombre']) ?>. El usuario y la clave fueron validados correctamente.
            </p>
            <p class="helper-text">
                La clave no se compara como texto plano. El sistema usa <strong>password_verify()</strong>
                contra el hash guardado en SQLite.
            </p>
            <a class="link-button" href="panel.php?accion=salir">Cerrar sesion</a>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
