<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'app' => config('app.name'),
            'env' => app()->environment(),
            'time' => now()->toIso8601String(),
        ];

        $ok = true;

        try {
            DB::connection()->getPdo();
            $checks['db'] = 'ok';
        } catch (Throwable $e) {
            $checks['db'] = 'fail';
            $ok = false;
        }

        try {
            $probe = 'health:'.bin2hex(random_bytes(4));
            Cache::put($probe, 1, 5);
            Cache::forget($probe);
            $checks['cache'] = 'ok';
        } catch (Throwable $e) {
            $checks['cache'] = 'fail';
            $ok = false;
        }

        $checks['status'] = $ok ? 'ok' : 'degraded';

        return response()->json($checks, $ok ? 200 : 503);
    }
}
