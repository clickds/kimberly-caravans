<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CampaignMonitor\ApiClient;

class CampaignMonitorProvider extends ServiceProvider
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
        $this->app->bind(ApiClient::class, function () {
            $apiToken = env('CAMPAIGN_MONITOR_API_KEY');

            return new ApiClient($apiToken);
        });
    }
}
