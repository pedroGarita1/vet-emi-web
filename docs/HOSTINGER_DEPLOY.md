# Despliegue en Hostinger

Esta aplicacion se puede desplegar en Hostinger sin colas residentes. La recomendacion para hosting compartido es usar `QUEUE_CONNECTION=sync` y ejecutar el scheduler por cron.

Esta guia esta pensada para tu caso: sin SSH y subiendo solo un ZIP.

## Requisitos

- PHP 8.2 o superior
- MySQL disponible en hPanel
- Acceso al Administrador de archivos de Hostinger
- Composer no es obligatorio en el servidor si subes tambien la carpeta `vendor`
- Node.js en el servidor no es necesario si subes `public/build` ya compilado

## Variables recomendadas

Usa como base [\.env.hostinger.example](../.env.hostinger.example) y completa estos valores:

- `APP_URL=https://tu-dominio.com`
- `APP_KEY=` se genera una sola vez en el servidor
- Credenciales reales de `DB_*`
- Credenciales reales de `MAIL_*`
- `FILESYSTEM_DISK=public`
- `QUEUE_CONNECTION=sync`
- `APP_DEBUG=false`
- `DEPLOY_WEB_ENABLED=true` solo durante la instalacion inicial
- `DEPLOY_WEB_TOKEN=` con un token largo y unico

## Preparacion local

Ejecuta estos comandos antes de subir el proyecto:

```bash
npm ci
npm run build
composer install --no-dev --optimize-autoloader
```

El ZIP de despliegue debe incluir como minimo:

- todo el proyecto Laravel
- la carpeta `vendor`
- la carpeta `public/build`
- tu archivo `.env` de produccion

## Estructura recomendada en Hostinger

La opcion mas limpia es dejar el proyecto fuera del directorio publico y publicar solo la carpeta `public`.

Ejemplo:

- Aplicacion: `/home/usuario/emi-vet`
- Web root del dominio: `/home/usuario/domains/tu-dominio.com/public_html`

Si tu plan permite cambiar el document root para que apunte a la carpeta `public` del proyecto, usa esa opcion. Si no, sigue esta estructura:

1. Sube todo el proyecto a `/home/usuario/emi-vet`.
2. Copia el contenido de `public/` a `public_html/`.
3. Edita `public_html/index.php` para que apunte a las rutas reales del proyecto.
4. Deja tambien `hostinger-deploy.php` dentro de `public_html/` para el primer despliegue web.

Ejemplo de rutas dentro de `index.php` cuando la app queda en `/home/usuario/emi-vet`:

```php
require __DIR__.'/../emi-vet/vendor/autoload.php';

$app = require_once __DIR__.'/../emi-vet/bootstrap/app.php';
```

## Comandos de primer despliegue

Sin SSH, el primer despliegue se hace desde navegador usando [public/hostinger-deploy.php](../public/hostinger-deploy.php).

Una vez subido el codigo y creado el archivo `.env`:

1. Abre `https://tu-dominio.com/hostinger-deploy.php`.
2. Ingresa el valor de `DEPLOY_WEB_TOKEN`.
3. El script ejecutara:

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan storage:unlink
php artisan storage:link --force
php artisan optimize
```

4. Al terminar, el script intentara cambiar `DEPLOY_WEB_ENABLED=false` automaticamente.
5. Elimina `hostinger-deploy.php` de `public_html` cuando confirmes que el sitio ya funciona.

## Despliegues siguientes

Para actualizaciones posteriores por ZIP:

```bash
1. Genera localmente `vendor` y `public/build`.
2. Vuelve a comprimir el proyecto.
3. Sube y reemplaza archivos en Hostinger.
4. Si agregaste migraciones nuevas, reactiva temporalmente `DEPLOY_WEB_ENABLED=true` en `.env` y vuelve a abrir `hostinger-deploy.php`.
5. Elimina otra vez `hostinger-deploy.php` o dejalo desactivado.
```

## Cron obligatorio

La aplicacion tiene una tarea programada diaria. Si tu plan de Hostinger permite cron por comando, configura:

```bash
* * * * * /usr/bin/php /home/usuario/emi-vet/artisan schedule:run >> /dev/null 2>&1
```

Si tu ruta de PHP es distinta, confirmala con `which php` desde SSH.

Si no tienes acceso a cron por comando, usa el gestor de cron de Hostinger si lo ofrece para ejecutar `schedule:run`. Si tu plan no permite cron real, esa tarea diaria no correra automaticamente y tendras que cambiar de plan o proveedor.

## Permisos

Si Hostinger lo requiere, ajusta permisos para escritura:

```bash
chmod -R 775 storage bootstrap/cache
```

## Checklist despues de subir

1. Abre el sitio y confirma que carga CSS y JS desde `public/build`.
2. Inicia sesion y valida que las sesiones se guardan correctamente.
3. Sube una imagen de consulta o estetica y confirma que abre desde la web.
4. Prueba envio de correo SMTP.
5. Ejecuta `php artisan schedule:list` y confirma que el cron queda configurado.

## Notas importantes

- En este proyecto no se detecto uso de jobs encolados, por eso `QUEUE_CONNECTION=sync` es la opcion adecuada para Hostinger compartido.
- Las imagenes de consultas y estetica usan almacenamiento publico. Por eso es obligatorio correr `php artisan storage:link` y dejar `FILESYSTEM_DISK=public` en produccion.
- Si compilas assets en el servidor, usa Node 20.19 o superior. Con versiones anteriores Vite 7 muestra advertencia o puede fallar.
- Si solo trabajas por ZIP, no dependas de Composer ni Node en Hostinger: sube `vendor` y `public/build` ya listos.
