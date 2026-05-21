<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/graph.php';

// The panel is private, so users must login before seeing it.
require_login();

// Default values keep the form useful on the first page load.
$cities = cities();
$origin = $_POST['origin'] ?? 'Guatemala';
$destination = $_POST['destination'] ?? 'Sacatepequez';
$result = null;
$notice = '';

// When the form is submitted, calculate the shortest route with Dijkstra.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = shortest_route($origin, $destination);

    if ($result === null) {
        $notice = 'No se encontro una ruta para los departamentos seleccionados.';
    }
}
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
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <h1><?= e(APP_NAME) ?></h1>
            <nav>
                <span><?= e(current_user()['nombre']) ?></span>
                <a href="logout.php">Salir</a>
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
                        <!-- Department options are generated from the graph array. -->
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= e($city) ?>" <?= $origin === $city ? 'selected' : '' ?>>
                                <?= e($city) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    Departamento Destino
                    <select name="destination" class="form-select" required data-destination>
                        <!-- The selected value is kept after submitting the form. -->
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= e($city) ?>" <?= $destination === $city ? 'selected' : '' ?>>
                                <?= e($city) ?>
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
                <?php if ($notice !== ''): ?>
                    <p class="alert alert-error"><?= e($notice) ?></p>
                <?php elseif ($result !== null): ?>
                    <p class="distance">Distancia Total: <?= e((string) $result['distance']) ?> km</p>
                    <hr>
                    <h3>Itinerario de viaje:</h3>
                    <ol class="route-path">
                        <?php foreach ($result['path'] as $index => $city): ?>
                            <li>
                                <span><?= e($city) ?></span>
                                <?php if ($index < count($result['path']) - 1): ?>
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
</html>
