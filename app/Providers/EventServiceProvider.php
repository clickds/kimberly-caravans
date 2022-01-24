<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PageableUpdated;
use App\Events\CaravanSaved;
use App\Events\MotorhomeSaved;
use App\Listeners\SaveCaravanStockItem;
use App\Listeners\UpdatePageablePages;
use App\Listeners\SavePageablePages;
use App\Listeners\SaveMotorhomeStockItem;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PageableUpdated::class => [
            UpdatePageablePages::class,
        ],
        CaravanSaved::class => [
            SaveCaravanStockItem::class,
        ],
        MotorhomeSaved::class => [
            SaveMotorhomeStockItem::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
