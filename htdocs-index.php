<?php
/*
 * Shared-hosting shim for InfinityFree / most cPanel hosts.
 *
 * USAGE (copy to the server's htdocs/ as index.php):
 *   /home/user/htdocs/index.php      ← this file, renamed to index.php
 *   /home/user/laravel/              ← everything from this repo goes here
 *
 * The constant below must point to the Laravel app root (one level up
 * from htdocs by default).
 */

define('LARAVEL_BASE', __DIR__.'/../laravel');

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = LARAVEL_BASE.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require LARAVEL_BASE.'/vendor/autoload.php';

(require_once LARAVEL_BASE.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
