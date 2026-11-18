<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * One-time maintenance endpoints for shared hosts without CLI access
 * (InfinityFree, most cPanel free plans).
 *
 * Protected by a shared secret in the OPS_TOKEN env var. Leave OPS_TOKEN
 * empty in production to disable these routes entirely. Delete / blank
 * OPS_TOKEN once the first migration + admin seed are done.
 */
class OpsController extends Controller
{
    private function guard(Request $request): ?Response
    {
        $expected = (string) env('OPS_TOKEN', '');
        if ($expected === '') {
            return response('Ops endpoints disabled: OPS_TOKEN is not set.', 404);
        }
        $given = (string) $request->query('token', '');
        // hash_equals avoids timing-attack guessing.
        if (! hash_equals($expected, $given)) {
            return response('Forbidden.', 403);
        }
        return null;
    }

    public function migrate(Request $request): Response
    {
        if ($r = $this->guard($request)) return $r;

        $fresh = $request->boolean('fresh');
        $exit = Artisan::call($fresh ? 'migrate:fresh' : 'migrate', ['--force' => true]);
        $out = Artisan::output();

        return response(
            "<pre style='font:13px/1.4 monospace;padding:14px'>"
            ."artisan ".($fresh ? 'migrate:fresh' : 'migrate')." --force\n"
            ."exit code: $exit\n\n"
            .e($out)
            ."</pre>",
            $exit === 0 ? 200 : 500,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }

    public function seedAdmin(Request $request): Response
    {
        if ($r = $this->guard($request)) return $r;

        $email = trim((string) $request->query('email'));
        $password = (string) $request->query('password');
        $name = (string) $request->query('name', 'Admin');

        if ($email === '' || $password === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            return response('Pass ?email=you@domain&password=atleast8chars&name=Admin&token=...', 422);
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->fill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ])->save();

        return response(
            "<pre style='font:13px/1.4 monospace;padding:14px'>"
            ."Admin ready:\n"
            ."  id:    {$user->id}\n"
            ."  email: {$user->email}\n"
            ."  role:  {$user->role}\n\n"
            ."Sign in at /admin.\n"
            ."Blank OPS_TOKEN now to disable these ops endpoints."
            ."</pre>",
            200,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }

    public function status(Request $request): Response
    {
        if ($r = $this->guard($request)) return $r;

        $info = [
            'app_env'   => app()->environment(),
            'app_debug' => config('app.debug'),
            'db'        => 'unknown',
            'migrations' => 'unknown',
            'users'     => 'unknown',
            'php'       => PHP_VERSION,
        ];
        try {
            DB::connection()->getPdo();
            $info['db'] = 'connected ('.DB::connection()->getDriverName().')';
            $info['migrations'] = (string) DB::table('migrations')->count();
            $info['users']      = (string) User::query()->count();
        } catch (\Throwable $e) {
            $info['db'] = 'error: '.$e->getMessage();
        }
        $rows = '';
        foreach ($info as $k => $v) {
            $rows .= sprintf("  %-12s %s\n", $k, $v);
        }
        return response(
            "<pre style='font:13px/1.4 monospace;padding:14px'>".e($rows)."</pre>",
            200,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }
}
