<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

set_time_limit(0);

define('LARAVEL_START', microtime(true));

$appBasePath = detectAppBasePath();

if ($appBasePath === null) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "No se pudo detectar la raiz del proyecto Laravel.\n";
    echo "Coloca este archivo dentro de public/ o public_html y asegúrate de subir la carpeta completa del proyecto con vendor/.\n";
    exit;
}

require $appBasePath.'/vendor/autoload.php';

$app = require_once $appBasePath.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$deployEnabled = filter_var((string) env('DEPLOY_WEB_ENABLED', 'false'), FILTER_VALIDATE_BOOL);
$expectedToken = trim((string) env('DEPLOY_WEB_TOKEN', ''));

if (! $deployEnabled) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "El despliegue web está deshabilitado. Activa DEPLOY_WEB_ENABLED=true en .env para usar este archivo.\n";
    exit;
}

if ($expectedToken === '') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Falta DEPLOY_WEB_TOKEN en .env.\n";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    renderForm();
    exit;
}

$submittedToken = (string) ($_POST['token'] ?? '');

if (! hash_equals($expectedToken, $submittedToken)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Token inválido.\n";
    exit;
}

$commands = [
    ['name' => 'optimize:clear', 'parameters' => []],
    ['name' => 'migrate', 'parameters' => ['--force' => true]],
    ['name' => 'storage:unlink', 'parameters' => []],
    ['name' => 'storage:link', 'parameters' => ['--force' => true]],
    ['name' => 'optimize', 'parameters' => []],
];

$results = [];
$hasError = false;

foreach ($commands as $command) {
    try {
        $exitCode = Artisan::call($command['name'], $command['parameters']);
        $output = trim(Artisan::output());

        $results[] = [
            'command' => $command['name'],
            'exit_code' => $exitCode,
            'output' => $output === '' ? 'Sin salida.' : $output,
        ];

        if ($exitCode !== 0) {
            $hasError = true;
        }
    } catch (Throwable $exception) {
        $hasError = true;
        $results[] = [
            'command' => $command['name'],
            'exit_code' => 1,
            'output' => $exception->getMessage(),
        ];
    }
}

$envUpdated = false;

if (! $hasError) {
    $envUpdated = disableDeployWeb($appBasePath.'/.env');
}

renderResults($results, $hasError, $envUpdated);

function detectAppBasePath(): ?string
{
    $candidates = array_filter([
        realpath(__DIR__.'/..'),
        realpath(dirname(__DIR__)),
    ]);

    foreach ($candidates as $candidate) {
        if (isLaravelBasePath($candidate)) {
            return $candidate;
        }
    }

    $parent = realpath(dirname(__DIR__));

    if ($parent === false) {
        return null;
    }

    $entries = scandir($parent);

    if ($entries === false) {
        return null;
    }

    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }

        $candidate = $parent.DIRECTORY_SEPARATOR.$entry;

        if (! is_dir($candidate)) {
            continue;
        }

        $realCandidate = realpath($candidate);

        if ($realCandidate !== false && isLaravelBasePath($realCandidate)) {
            return $realCandidate;
        }
    }

    return null;
}

function isLaravelBasePath(string $path): bool
{
    return is_file($path.'/artisan')
        && is_file($path.'/bootstrap/app.php')
        && is_file($path.'/vendor/autoload.php');
}

function disableDeployWeb(string $envPath): bool
{
    if (! is_file($envPath) || ! is_writable($envPath)) {
        return false;
    }

    $content = file_get_contents($envPath);

    if ($content === false) {
        return false;
    }

    $updatedContent = preg_replace('/^DEPLOY_WEB_ENABLED\s*=\s*true$/mi', 'DEPLOY_WEB_ENABLED=false', $content, 1, $count);

    if (($count ?? 0) < 1 || $updatedContent === null) {
        return false;
    }

    return file_put_contents($envPath, $updatedContent) !== false;
}

function renderForm(): void
{
    header('Content-Type: text/html; charset=utf-8');

    echo <<<'HTML'
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostinger Deploy</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; background: #f7f7f7; color: #222; }
        .card { max-width: 720px; margin: 0 auto; background: #fff; border: 1px solid #ddd; border-radius: 12px; padding: 24px; }
        input, button { width: 100%; padding: 12px; margin-top: 12px; font-size: 16px; }
        button { cursor: pointer; background: #1f6feb; color: #fff; border: 0; border-radius: 8px; }
        p { line-height: 1.5; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Despliegue web</h1>
        <p>Este archivo ejecuta migraciones, storage link y optimizaciones sin SSH. Úsalo una vez y luego elimínalo de public_html.</p>
        <form method="post">
            <label for="token">Token de despliegue</label>
            <input id="token" name="token" type="password" required>
            <button type="submit">Ejecutar despliegue</button>
        </form>
    </div>
</body>
</html>
HTML;
}

function renderResults(array $results, bool $hasError, bool $envUpdated): void
{
    header('Content-Type: text/html; charset=utf-8');

    $title = $hasError ? 'Despliegue con errores' : 'Despliegue completado';
    $status = $hasError
        ? 'Algún comando falló. Revisa la salida antes de abrir el sitio.'
        : 'Los comandos terminaron correctamente.';
    $nextStep = $hasError
        ? 'Corrige el problema, vuelve a activar DEPLOY_WEB_ENABLED=true si el archivo .env ya se desactivó y ejecuta otra vez.'
        : 'El script intentó desactivar DEPLOY_WEB_ENABLED en .env. Aun así, elimina este archivo de public_html cuando termines.';
    $envMessage = $envUpdated
        ? 'DEPLOY_WEB_ENABLED fue cambiado a false automáticamente.'
        : 'No se pudo cambiar DEPLOY_WEB_ENABLED automáticamente. Desactívalo manualmente en .env.';

    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.$title.'</title><style>body{font-family:Arial,sans-serif;padding:24px;background:#f7f7f7;color:#222}.card{max-width:900px;margin:0 auto;background:#fff;border:1px solid #ddd;border-radius:12px;padding:24px}pre{white-space:pre-wrap;background:#111;color:#f5f5f5;padding:16px;border-radius:8px;overflow:auto}</style></head><body><div class="card">';
    echo '<h1>'.$title.'</h1>';
    echo '<p>'.$status.'</p>';
    echo '<p>'.$envMessage.'</p>';
    echo '<p>'.$nextStep.'</p>';

    foreach ($results as $result) {
        echo '<h2>'.htmlspecialchars((string) $result['command'], ENT_QUOTES, 'UTF-8').' (exit code '.(int) $result['exit_code'].')</h2>';
        echo '<pre>'.htmlspecialchars((string) $result['output'], ENT_QUOTES, 'UTF-8').'</pre>';
    }

    echo '</div></body></html>';
}