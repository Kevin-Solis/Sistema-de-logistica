# Sistema-de-logistica

Version sencilla para demostrar login con clave segura.

## Que demuestra

- `index.php`: formulario de login.
- `validarlogin.php`: recibe usuario y clave por POST.
- `conexion.php`: conecta con SQLite, crea la tabla `usuarios` y guarda la clave con `password_hash()`.
- `panel.php`: se muestra solo si el login fue exitoso.

## Usuario inicial

```text
admin / admin123
```

## Flujo

```text
index.php
  -> POST a validarlogin.php
  -> buscar usuario en SQLite
  -> password_verify(clave, hash_guardado)
  -> panel.php si es correcto
  -> index.php?error=1 si es incorrecto
```

## Ejecutar

```bash
npm install
npm run build
npm run serve
```

Luego abre:

```text
http://localhost:8000
```
