<?php

namespace App\Http\Middleware;

use App;
use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if (Session()->has('applocale') AND array_key_exists(Session()->get('applocale'), config('languages'))) {
        //     App::setLocale(Session()->get('applocale'));
        // }
        // else { // This is optional as Laravel will automatically set the fallback language if there is none specified
        //     App::setLocale(config('app.fallback_locale'));

        // }

        // Check header request and determine localizaton
     $local = ($request->hasHeader('lang')) ? $request->header('lang') : 'en';
     // set laravel localization
     app()->setLocale($local);
        return $next($request);
    }
}