<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['setCurrentSite', 'throttle:60,1'],
    'namespace' => 'Api',
    'as' => 'api.',
], function ($router) {
    Route::group([
        'prefix' => 'caravan-stock-items',
        'namespace' => 'CaravanStockItems',
        'as' => 'caravan-stock-items.',
    ], function () {
        Route::resource('/', 'CaravanStockItemController')->only(['index']);
        Route::get('search', 'SearchController')->name('search');
        Route::get('/managers-specials/search', 'ManagersSpecialsSearchController')
            ->name('managers-specials.search');
        Route::get('special-offer/{specialOffer}/search', 'SpecialOfferSearchController')
            ->name('special-offer.search');
    });

    Route::group([
        'prefix' => 'motorhome-stock-items',
        'namespace' => 'MotorhomeStockItems',
        'as' => 'motorhome-stock-items.',
    ], function () {
        Route::resource('/', 'MotorhomeStockItemController')->only(['index']);
        Route::get('search', 'SearchController')->name('search');
        Route::get('/managers-specials/search', 'ManagersSpecialsSearchController')
            ->name('managers-specials.search');
        Route::get('special-offer/{specialOffer}/search', 'SpecialOfferSearchController')
            ->name('special-offer.search');
    });

    Route::apiResource('vehicle-enquiries', 'VehicleEnquiriesController')->only(['store']);
});
