<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
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
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setlocale($locale);
            Carbon::setLocale($locale);
        }
        //    else {
        //     app()->getLocale();
        //    }

        //     pr();
        //     $locale = get_locale_from_url();

        //     $request->session()->put('locale', $locale);
        //     $request->route()->forgetParameter('lc');
        return $next($request);
    }

    // private function subdomain()
    // {
    //     $parsedUrl = parse_url(url()->current());
    //     $host = explode('.', $parsedUrl['host']);
    //     $subdomain = $host[0];
    //     return $subdomain;
    // }
}
