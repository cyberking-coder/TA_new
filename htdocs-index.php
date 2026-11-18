<?php
/*
 * Shared-hosting shim for InfinityFree / most cPanel hosts.
 *
 * Server-side layout (everything lives inside htdocs because InfinityFree
 * disallows writing outside it):
 *   /htdocs/
 *     index.php      ← this file, renamed to index.php
 *     .htaccess      ← main rewrite rules (htdocs.htaccess)
 *     laravel/       ← the Laravel app
 *       .htaccess    ← deny-all so no URL can reach the code
 *       app/ bootstrap/ config/ vendor/ .env ...
 */

define('LARAVEL_BASE', __DIR__.'/laravel');

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = LARAVEL_BASE.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require LARAVEL_BASE.'/vendor/autoload.php';

(require_once LARAVEL_BASE.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
