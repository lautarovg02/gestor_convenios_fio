## 1. Requisitos del Servidor
- **PHP** 8.1 o superior
- **Base de Datos** MySQL o MariaDB
- **Servidor Web** Apache o Nginx
- **Composer** (Manejador de dependencias de PHP)
- **Node.js y npm** (Para compilar los assets del frontend)

## 2. Descarga y Permisos
1. Clonar el repositorio en el directorio del servidor web (ej. `/var/www/html/gestor_convenios_fio` o `C:\xampp\htdocs\gestor_convenios_fio`).
2. **IMPORTANTE:** Dar permisos de escritura a las carpetas de caché y almacenamiento de Laravel:
   - `storage/` y todos sus subdirectorios.
   - `bootstrap/cache/`

## 3. Configuración del Entorno (.env)
1. Copiar el archivo de ejemplo para crear el archivo de entorno definitivo:
   ```bash
   cp .env.example .env
   ```
2. Editar el archivo `.env` configurando **obligatoriamente** los siguientes parámetros para proteger el servidor:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://tu-dominio-o-ip.com
   ```
3. Configurar las credenciales de la base de datos de producción:
   ```env
   DB_DATABASE=nombre_de_la_bd
   DB_USERNAME=usuario_bd
   DB_PASSWORD=contraseña_bd
   ```

## 4. Instalación de Dependencias
Ejecutar en la terminal de la raíz del proyecto:

**Backend (PHP):**
```bash
composer install --optimize-autoloader --no-dev
```

**Frontend (Assets):**
```bash
npm install
npm run build
```

## 5. Preparación de la Base de Datos
Generar la clave de la aplicación y montar la estructura y datos iniciales de la base de datos:
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force
```
*(Nota: La flag `--force` es necesaria porque Laravel detecta que estás en entorno de producción y pide confirmación).*

## 6. Optimización y Caché (Paso Final)
Para que el sistema cargue mucho más rápido en producción, ejecutar los comandos de caché:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---
**Nota sobre acceso:** El seeder de producción (`ProductionSeeder`) generará el usuario administrador por defecto. Por favor revisen la documentación o al desarrollador para obtener las credenciales por defecto.
