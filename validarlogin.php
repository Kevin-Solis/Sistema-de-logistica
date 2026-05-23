<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$clave = $_POST['clave'] ?? '';
$usuarioEncontrado = buscar_usuario($usuario);

if ($usuarioEncontrado === null || !password_verify($clave, $usuarioEncontrado['password_hash'])) {
    header('Location: index.php?error=1');
    exit;
}

session_regenerate_id(true);
$_SESSION['usuario'] = [
    'id' => (int) $usuarioEncontrado['id'],
    'nombre' => $usuarioEncontrado['nombre'],
    'usuario' => $usuarioEncontrado['usuario'],
];

header('Location: index.php');
exit;
