<?php

/*
 * Two logical disks the app uses everywhere: `public` and `private`.
 *
 * - Locally (nothing R2-ish set in .env) both fall back to the local
 *   filesystem under storage/app/.
 * - When R2 env vars are set, each disk automatically switches to its
 *   own R2 bucket. No code change anywhere in the app is needed.
 *
 * R2 env vars this expects:
 *   R2_ACCOUNT_ID         (Cloudflare R2 account id — used for the endpoint)
 *   R2_ACCESS_KEY_ID      (R2 API token key)
 *   R2_SECRET_ACCESS_KEY  (R2 API token secret)
 *   R2_PUBLIC_BUCKET      (name of the public bucket)
 *   R2_PRIVATE_BUCKET     (name of the private bucket)
 *   R2_PUBLIC_URL         (optional — the public bucket's custom/r2.dev URL)
 *
 * Fall-back for a single-bucket setup: if R2_PUBLIC_BUCKET isn't set
 * but AWS_BUCKET is, both disks share AWS_BUCKET and we use path
 * prefixes `public/` and `private/` to keep them separated.
 */

$r2AccountId   = env('R2_ACCOUNT_ID');
$r2Key         = env('R2_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'));
$r2Secret      = env('R2_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'));
$r2PublicBkt   = env('R2_PUBLIC_BUCKET',  env('AWS_BUCKET'));
$r2PrivateBkt  = env('R2_PRIVATE_BUCKET', env('AWS_BUCKET'));
$r2PublicUrl   = env('R2_PUBLIC_URL',     env('AWS_URL'));
$r2Endpoint    = env('R2_ENDPOINT', env('AWS_ENDPOINT', $r2AccountId ? "https://{$r2AccountId}.r2.cloudflarestorage.com" : null));

$useR2 = $r2Endpoint && $r2Key && $r2Secret && $r2PublicBkt;

$publicDisk = $useR2
    ? [
        'driver' => 's3',
        'key' => $r2Key,
        'secret' => $r2Secret,
        'region' => 'auto',
        'bucket' => $r2PublicBkt,
        'endpoint' => $r2Endpoint,
        'use_path_style_endpoint' => true,
        'url' => $r2PublicUrl,
        'visibility' => 'public',
        'throw' => true,
        'report' => false,
    ]
    : [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
        'visibility' => 'public',
        'throw' => false,
        'report' => false,
    ];

$privateDisk = $useR2
    ? [
        'driver' => 's3',
        'key' => $r2Key,
        'secret' => $r2Secret,
        'region' => 'auto',
        'bucket' => $r2PrivateBkt,
        'endpoint' => $r2Endpoint,
        'use_path_style_endpoint' => true,
        // No `url` — private bucket must NEVER be served directly; access
        // is always via signed URLs through the app's study/download route.
        'visibility' => 'private',
        'throw' => true,
        'report' => false,
    ]
    : [
        'driver' => 'local',
        'root' => storage_path('app/private'),
        'serve' => false,
        'throw' => false,
        'report' => false,
        'visibility' => 'private',
    ];

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        // Framework "local" — stays available for scratch files.
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        // App's two canonical disks. Routed to R2 automatically when env is set.
        'public'  => $publicDisk,
        'private' => $privateDisk,

        // Raw s3 kept for anything that wants the legacy name.
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
