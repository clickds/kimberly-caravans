<?php

namespace App\Providers;

use App\ViewComposers\CookieConsentComposer;
use App\ViewComposers\FooterComposer;
use App\ViewComposers\HeaderComposer;
use App\ViewComposers\SitesComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if (app()->runningInConsole() && App::environment() != 'testing') {
            return;
        }

        View::composer('cookieConsent::index', CookieConsentComposer::class);
        View::composer('layouts.header.main', HeaderComposer::class);
        View::composer('layouts.footer.main', FooterComposer::class);
    }
}
