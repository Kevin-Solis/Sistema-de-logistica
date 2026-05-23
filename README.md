# Sistema-de-logistica

Aplicacion web en PHP estructural para calcular la ruta mas corta entre departamentos de Guatemala usando teoria de grafos y el algoritmo de Dijkstra.

## Requisitos

- PHP 8.1 o superior con extension PDO SQLite
- Node.js 18 o superior

## Instalacion

```bash
npm install
npm run build
```

## Ejecutar

```bash
npm run serve
```

Luego abre:

```text
http://localhost:8000
```

Si ese puerto ya esta ocupado, puedes usar otro:

```bash
php -S localhost:8001
```

Usuario inicial:

```text
admin / admin123
```

La base de datos SQLite se prepara automaticamente al abrir `index.php`.
Tambien puedes crear nuevos usuarios desde el enlace `Crear una cuenta` en la pantalla de login.

## Estructura principal

El proyecto queda explicado con cuatro archivos PHP principales:

- `index.php`: muestra el login cuando no hay sesion y muestra el panel de rutas cuando el usuario ya ingreso.
- `validarlogin.php`: recibe formularios por POST, registra usuarios, valida usuario/clave y crea la sesion.
- `conexion.php`: abre la conexion PDO SQLite, crea la tabla `usuarios`, prepara el usuario inicial y guarda claves con hash.
- `graph.php`: contiene el grafo de departamentos, las distancias y el algoritmo de Dijkstra.

## Flujo

```text
index.php
  -> formulario POST a validarlogin.php
  -> validarlogin.php consulta conexion.php
  -> si el login es correcto vuelve a index.php
  -> index.php llama graph.php para calcular la ruta mas corta
```

## Seguridad

El sistema no guarda claves en texto plano. El usuario inicial y cualquier validacion usan:

```php
password_hash($clave, PASSWORD_DEFAULT)
password_verify($clave, $hashGuardado)
```

Tambien se usa `session_regenerate_id(true)` al iniciar sesion para reducir riesgo de fijacion de sesion.
