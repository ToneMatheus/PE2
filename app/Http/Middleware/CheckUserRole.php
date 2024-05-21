<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserRole
{

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (Auth::check()) {
            $userRoles = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->pluck('role_id')
                ->toArray();

            if (array_intersect($roles, $userRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
