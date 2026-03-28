# Guia de Instalacion - Gestor de Convenios FIO

## Requisitos previos

Instalar en la PC:
- XAMPP 8.x (incluye PHP 8.1+ y MySQL) -> https://www.apachefriends.org/
- Composer 2.x -> https://getcomposer.org/
- Node.js 18+ (incluye npm) -> https://nodejs.org/

## Pasos de instalacion

### 1. Copiar el proyecto
Copiar la carpeta gestor_convenios_fio dentro de C:\xampp\htdocs\

### 2. Configurar el archivo .env
Copiar .env.production como .env:
`
copy .env.production .env
`

### 3. Iniciar XAMPP
Abrir XAMPP Control Panel e iniciar Apache y MySQL.

### 4. Crear la base de datos
Abrir phpMyAdmin en http://localhost/phpmyadmin
Crear una base de datos llamada: gestor_convenios_fio
Cotejamiento: utf8mb4_unicode_ci

### 5. Abrir una terminal en la carpeta del proyecto
`
cd C:\xampp\htdocs\gestor_convenios_fio
`

### 6. Generar la clave de la aplicacion
`
php artisan key:generate
`

### 7. Ejecutar las migraciones
`
php artisan migrate
`

### 8. Cargar los datos iniciales del sistema
`
php artisan db:seed --class=ProductionSeeder
`

### 9. Compilar los assets del frontend (solo la primera vez)
`
npm install
npm run build
`

### 10. Acceder al sistema
Abrir en el navegador: http://localhost/gestor_convenios_fio/public

### Credenciales del administrador
- Email: admin@fio.uner.edu.ar
- Password: Admin1234!
(Cambiar la contrasena en el primer acceso)

## Para desarrollo (opcional)
Si se necesita modificar el codigo, usar estos comandos en terminales separadas:
`
npm run dev
php artisan serve
`
Y acceder por: http://localhost:8000
