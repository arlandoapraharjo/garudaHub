<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

header_remove('X-Powered-By');

define('LARAVEL_START', microtime(true));

// Ensure writable storage directories exist on Vercel's /tmp filesystem
$tmpStorage = '/tmp/storage';
foreach ([
    $tmpStorage.'/app/public',
    $tmpStorage.'/framework/cache/data',
    $tmpStorage.'/framework/sessions',
    $tmpStorage.'/framework/testing',
    $tmpStorage.'/framework/views',
    $tmpStorage.'/logs',
] as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Fallback to /tmp/database.sqlite only if sqlite is used without an external DB URL
$hasExternalUrl = ($_ENV['DB_URL'] ?? getenv('DB_URL') ?: $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL') ?: $_ENV['POSTGRES_URL'] ?? getenv('POSTGRES_URL'));
$dbConnection = $_ENV['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?: ($hasExternalUrl ? 'pgsql' : 'sqlite');

if ($dbConnection === 'sqlite' && ! $hasExternalUrl) {
    $tmpDb = '/tmp/database.sqlite';
    if (! file_exists($tmpDb)) {
        touch($tmpDb);
    }
    $_ENV['DB_DATABASE'] = $tmpDb;
    $_SERVER['DB_DATABASE'] = $tmpDb;
    putenv('DB_DATABASE='.$tmpDb);
}

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($tmpStorage);

$kernel = $app->make(HttpKernelContract::class);
$kernel->bootstrap();

// Auto-run migrations and initial seed once per serverless instance cold start
$migratedFlag = '/tmp/.migrated';
if (! file_exists($migratedFlag)) {
    try {
        if (! Schema::hasTable('users')) {
            Artisan::call('migrate', ['--force' => true]);
        }
        if (User::count() === 0) {
            Artisan::call('db:seed', ['--force' => true]);
        }
        file_put_contents($migratedFlag, '1');
    } catch (Throwable $e) {
        error_log('Vercel auto-migrate warning: '.$e->getMessage());
    }
}

$request = Request::capture();
$response = $kernel->handle($request)->send();
$kernel->terminate($request, $response);
