<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::user();

        if (Auth::check()) {
            $userRole = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->first();

            if ($userRole && $userRole->role_id == $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
