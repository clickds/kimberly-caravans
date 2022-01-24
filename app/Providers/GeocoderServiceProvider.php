<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Spatie\Geocoder\Geocoder;

class GeocoderServiceProvider extends ServiceProvider
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
    public function boot()
    {
        $this->app->bind(Geocoder::class, function () {
            $client = new Client();
            $geocoder = new Geocoder($client);
            $geocoder->setApiKey(config('geocoder.key'));
            $geocoder->setCountry(config('geocoder.country', 'UK'));

            return $geocoder;
        });
    }
}
