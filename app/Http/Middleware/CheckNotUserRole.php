<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\RoleHelper;
use Auth;

class CheckNotUserRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $roleHelper = new RoleHelper();

        if(Auth::check() && !$roleHelper->hasRole($user->ID, 'Customer')){
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
