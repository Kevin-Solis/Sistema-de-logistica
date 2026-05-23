<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/graph.php';

session_start();
preparar_base_datos();

if (($_GET['accion'] ?? '') === 'salir') {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

$usuarioActual = $_SESSION['usuario'] ?? null;
$errorLogin = ($_GET['error'] ?? '') === '1';
$ciudades = cities();
$origen = $_POST['origin'] ?? 'Guatemala';
$destino = $_POST['destination'] ?? 'Sacatepequez';
$resultado = null;
$aviso = '';

if ($usuarioActual !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = shortest_route($origen, $destino);

    if ($resultado === null) {
        $aviso = 'No se encontro una ruta para los departamentos seleccionados.';
    }
}
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
<?php if ($usuarioActual === null): ?>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <span class="eyebrow">Acceso seguro</span>
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
<?php else: ?>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <h1><?= e(APP_NAME) ?></h1>
            <nav>
                <span><?= e($usuarioActual['nombre']) ?></span>
                <a href="index.php?accion=salir">Salir</a>
            </nav>
        </div>
    </header>

    <main class="layout">
        <section class="panel-card route-card">
            <h2>Calcular Trayectoria</h2>
            <form method="post" class="route-form" data-route-form>
                <label>
                    Departamento de Origen
                    <select name="origin" class="form-select" required data-origin>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?= e($ciudad) ?>" <?= $origen === $ciudad ? 'selected' : '' ?>>
                                <?= e($ciudad) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    Departamento Destino
                    <select name="destination" class="form-select" required data-destination>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?= e($ciudad) ?>" <?= $destino === $ciudad ? 'selected' : '' ?>>
                                <?= e($ciudad) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <div class="route-actions">
                    <button type="button" class="swap-button" title="Intercambiar ciudades" data-swap-route>&harr;</button>
                    <button type="submit">Buscar Ruta Mas Corta</button>
                </div>
            </form>
        </section>

        <section class="panel-card result-card">
            <h2>Resultado del Analisis</h2>
            <div class="result-body">
                <?php if ($aviso !== ''): ?>
                    <p class="alert alert-error"><?= e($aviso) ?></p>
                <?php elseif ($resultado !== null): ?>
                    <p class="distance">Distancia Total: <?= e((string) $resultado['distance']) ?> km</p>
                    <hr>
                    <h3>Itinerario de viaje:</h3>
                    <ol class="route-path">
                        <?php foreach ($resultado['path'] as $indice => $ciudad): ?>
                            <li>
                                <span><?= e($ciudad) ?></span>
                                <?php if ($indice < count($resultado['path']) - 1): ?>
                                    <b aria-hidden="true">&rarr;</b>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <p class="empty-state">Selecciona origen y destino para calcular la ruta mas corta.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="public/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
<?php endif; ?>
</html>
