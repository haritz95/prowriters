<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SetDefaultLocaleForUrls
{
    protected $except = [
        'auth',
        'install',
        // 'auth/google',
        // 'auth/google/callback',

    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
         
        //$action = $request->route()->getAction();
        if (is_single_language()) {
            return $next($request);
        }

        // $path = explode("/", $request->path());

        // if (isset($path[0])) {
        //     if (in_array($path[0], $this->except)) {
        //         return $next($request);
        //     }
        // }

        //URL::defaults(['lc' => app()->getLocale()]);

        // if ($request->is('api/*')) {
        //     return $next($request);
        // }

        // pr(get_locale_from_url());
        if (in_array(get_locale_from_url(), allowed_languages())) {
            Setting::switchLanguage($request, get_locale_from_url());
            URL::defaults(['lc' => get_locale_from_url()]);

            return $next($request);
        } else {

            // if (env('ENABLE_APP_SETUP_CONFIG') == false) {
            //     return $next($request);
            // }

            if (Session::has('locale')) {
                $default_locale = Session::get('locale');
            } else {
                $default_locale = config('app.locale');
            }

            $parsed_url = parse_url(url()->current());            

            $sub_folder = get_sub_folder_if_exists();

            if ($sub_folder) {
                $path_without_sub_folder = str_replace('/'.$sub_folder.'/', '', $_SERVER['REQUEST_URI']);
                $path = $sub_folder . '/' . $default_locale . '/'. $path_without_sub_folder;
            }
            else {
                $path = $default_locale . $_SERVER['REQUEST_URI'];
            }

            $url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/' . $path;

            return redirect($url);
        }
    }
}
