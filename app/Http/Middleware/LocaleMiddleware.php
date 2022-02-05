<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //ideme nastavovat locale podla usera

        $locale = null;

        // kontrola ci je user prihlaseny
        if (Auth::check() && !Session::has('locale')){
            // nastavime locale podla toho co ma user ulozene
            $locale = $request->user()->locale;
            // ulozime tuto hodnotu do session
            Session::put('locale', $locale);
        }

        // ak request ma nastaveny locale parameter - http://localhost:8000/?locale=en
        // potom je locale nastaveny pre celu session, dalej uz to nemusime davat do URL
        // toto je vyuzivane pri jazykovom presmerovani apache /en/page -=> /page?locale=en
        if ($request->has('locale')){
            $locale = $request->get('locale');
            Session::put('locale', $locale);
        }

        // ak ma session nastavene locale
        $locale = Session::get('locale');

        // ak stale nic nemame, nastavime locale podla hodnoty fallback_locale v subore config/app.php
        if ($locale === null){
            $locale = config('app.fallback_locale');
        }

        // nastavi jazyk aplikacie
        App::setLocale($locale);

        return $next($request);
    }
}
