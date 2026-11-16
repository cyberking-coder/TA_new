<?php
/*
 * Vercel serverless PHP entry point.
 *
 * On Vercel the deployed filesystem is read-only; only /tmp is writable.
 * Laravel's storage/, compiled views, and sessions must live there, so we
 * prepare that layout BEFORE booting the framework.
 */

$isVercel = getenv('VERCEL') === '1';

if ($isVercel) {
    $tmp = '/tmp/laravel';
    foreach ([
        $tmp,
        $tmp.'/framework',
        $tmp.'/framework/cache',
        $tmp.'/framework/cache/data',
        $tmp.'/framework/sessions',
        $tmp.'/framework/testing',
        $tmp.'/framework/views',
        $tmp.'/logs',
        $tmp.'/app',
        $tmp.'/app/public',
    ] as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
    }

    // Tell Laravel where its writable storage lives.
    putenv('LARAVEL_STORAGE_PATH='.$tmp);
    $_ENV['LARAVEL_STORAGE_PATH'] = $tmp;
    $_SERVER['LARAVEL_STORAGE_PATH'] = $tmp;

    // Safe defaults that don't need external services, so the function can
    // serve HTML even before the user finishes wiring DB/Redis env vars.
    foreach ([
        'LOG_CHANNEL'        => 'stderr',
        'VIEW_COMPILED_PATH' => $tmp.'/framework/views',
        'SESSION_DRIVER'     => 'cookie',
        'CACHE_STORE'        => 'array',
        'QUEUE_CONNECTION'   => 'sync',
    ] as $k => $v) {
        if (getenv($k) === false) {
            putenv("$k=$v");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
    }
}

require __DIR__.'/../public/index.php';
