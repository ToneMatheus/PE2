<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\RoleHelper;
use Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        
        switch($role){
            case 'Customer':
                return $this->handleCustomer($request, $next);
            case 'Finance analyst':
                return $this->handleFinance($request, $next);
            case 'Manager':
                return $this->handleManager($request, $next);
            case 'Executive manager':
                return $this->handleExecutiveManager($request, $next);
        }

        abort(403, 'Unauthorized');
    }
 
    public  function handleCustomer(Request $request, Closure $next){
        $user = Auth::user();
        $roleHelper = new RoleHelper();

        if (Auth::check() && $roleHelper->hasRole($user->ID, 'Customer')) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }

    public  function handleFinance(Request $request, Closure $next){
        $user = Auth::user();
        $roleHelper = new RoleHelper();

        if (Auth::check() && $roleHelper->hasRole($user->ID, 'Finance analyst')) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }


    public  function handleManager(Request $request, Closure $next){
        $user = Auth::user();
        $roleHelper = new RoleHelper();

        if (Auth::check() && $roleHelper->hasRole($user->ID, 'Manager')) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }


    public  function handleExecutiveManager(Request $request, Closure $next){
        $user = Auth::user();
        $roleHelper = new RoleHelper();

        if (Auth::check() && $roleHelper->hasRole($user->ID, 'Executive manager')) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }

}
