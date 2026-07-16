# Landing + Dashboard PHP

## Requisitos
- PHP 8+
- MySQL
- Variables de entorno en Railway:
  - DB_HOST
  - DB_NAME
  - DB_USER
  - DB_PASS

## Instalación local
1. Crea una base de datos MySQL.
2. Ejecuta el contenido de [database.sql](database.sql).
3. Ajusta las variables de entorno si es necesario.
4. Sirve la carpeta con PHP.

## Usuarios iniciales
- Administrador: admin@fries.com / 123456
- Usuario demo: user@fries.com / 123456

> El sistema acepta contraseñas en texto plano para usuarios existentes y las convierte en hashes seguros en el primer inicio de sesión.

## Despliegue en Railway
1. Sube el proyecto.
2. Añade la base de datos MySQL.
3. Conecta las variables de entorno del servicio.
4. Despliega.
