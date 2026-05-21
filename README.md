# Sistema-de-logistica

Aplicacion web en PHP estructural para calcular la ruta mas corta entre departamentos de Guatemala usando teoria de grafos. Incluye acceso seguro con `password_hash` / `password_verify`, base de datos SQLite y estilos escritos en SCSS.

## Requisitos

- PHP 8.1 o superior con extension PDO SQLite
- Node.js 18 o superior

## Instalacion

```bash
npm install
npm run build
```

Inicializa la base de datos SQLite, la tabla de usuarios y el usuario de prueba abriendo:

```text
http://localhost:8000/init-db.php
```

Usuario inicial:

```text
admin / admin123
```

## Ejecutar

```bash
npm run serve
```

Luego abre:

```text
http://localhost:8000
```

## Hashing de contrasenas

El sistema no guarda contrasenas en texto plano. Cuando un usuario se registra, `auth.php` usa:

```php
password_hash($password, PASSWORD_DEFAULT)
```

Para validar el login usa:

```php
password_verify($password, $user['password_hash'])
```

Tambien se incluye un archivo documentado para generar hashes manualmente desde terminal:

```bash
php hash_password.php "admin123"
```

El resultado se puede guardar en la columna `usuarios.password_hash`.

## Estructura

- `panel.php`: formulario y resultado de la ruta mas corta.
- `graph.php`: grafo local de departamentos y algoritmo de Dijkstra. No usa API.
- `auth.php`: login, registro, cierre de sesion y hashing.
- `hash_password.php`: generador documentado de hashes seguros.
- `database.php`: conexion PDO SQLite y preparacion de usuarios.
- `database/schema.sql`: estructura de la tabla `usuarios` para SQLite.
- `assets/scss/main.scss`: estilos fuente.
- `public/js/app.js`: interacciones del formulario.
- `public/css/main.css`: CSS compilado para usar sin compilar en produccion.
- `scripts/copy-assets.js`: copia Bootstrap desde `node_modules` a `public/vendor`.
