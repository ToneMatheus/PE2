<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = session('applocale');

        // Check if the session locale is valid, otherwise fallback to default locale
        if ($locale && in_array($locale, config('app.available_locales'))) {
            App::setLocale($locale);
        } else {
            // Fallback to default locale
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
