<?php

// Suppress deprecation warnings on Vercel's PHP 8.5 runtime
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Prevent Laravel from writing to the read-only bootstrap/cache directory
$_ENV['APP_CONFIG_CACHE'] = '/tmp/cache/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/cache/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/cache/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/cache/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/cache/services.php';

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Configure Vercel storage path to /tmp
$app->useStoragePath('/tmp');
$storagePath = $app->storagePath();

$directories = [
    '/tmp/cache',
    $storagePath . '/app',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

$app->handleRequest(Request::capture());
