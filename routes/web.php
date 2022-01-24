<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

if (App::environment('local')) {
    Route::group([
        'prefix' => '/mail-preview',
    ], function ($router) {
        $router->get('/vehicle-enquiry', function () {
            $enquiry = \App\Models\VehicleEnquiry::first();
            $mailer = new \App\Mail\VehicleEnquiry($enquiry);

            return $mailer;
        });
        $router->get('/form-submission', function () {
            $submission = \App\Models\FormSubmission::first();
            $mailer = new \App\Mail\NewFormSubmission($submission);

            return $mailer;
        });

        $router->get('/vacancy-application', function () {
            $application = \App\Models\VacancyApplication::first();
            $mailer = new \App\Mail\NewVacancyApplication($application);

            return $mailer;
        });
    });
}

Auth::routes();
Route::group(
    [
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => ['auth'],
        'as' => 'admin.',
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::resource('aliases', 'AliasesController')->except(['show']);
        Route::resource('article-categories', 'ArticleCategoriesController', [
            'except' => ['show'],
        ]);
        Route::resource('articles', 'ArticlesController', [
            'except' => ['show'],
        ]);
        Route::delete('articles', 'Article\BulkDeleteController')->name('articles.bulk-delete');

        Route::resource('assets', 'AssetsController')
            ->only(['index', 'create', 'store', 'destroy']);
        Route::resource('bed-descriptions', 'BedDescriptionsController', [
            'except' => ['show'],
        ]);
        Route::resource('berths', 'BerthsController')->except(['show']);
        Route::resource('business-areas', 'BusinessAreasController')->except(['show']);
        Route::group([
            'namespace' => 'Caravan',
            'prefix' => '/caravans/{caravan}',
            'as' => 'caravans.',
        ], function ($router) {
            $router->resource('bed-sizes', 'BedSizesController', [
                'except' => ['show'],
            ]);
            $router->delete('night-floorplan', 'DeleteCaravanNightFloorplanController')->name('night-floorplan.destroy');
        });
        Route::group([
            'namespace' => 'CaravanRange',
            'prefix' => '/caravan-ranges/{caravanRange}',
            'as' => 'caravan-ranges.',
        ], function ($router) {
            $router->resource('caravans', 'CaravansController', [
                'except' => ['show'],
            ]);
            $router->resource('exterior-gallery-images', 'ExteriorGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('feature-gallery-images', 'FeatureGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('interior-gallery-images', 'InteriorGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('range-features', 'RangeFeaturesController', [
                'except' => ['show'],
            ]);
            $router->resource('range-specification-small-prints', 'RangeSpecificationSmallPrintsController', [
                'except' => ['show'],
            ]);
            $router->group([
                'prefix' => '/{galleryType}',
                'as' => 'gallery.upload-multiple.',
            ], function ($router) {
                $router->get('/upload-multiple', 'UploadMultipleGalleryImagesController@create')
                    ->name('create');
                $router->post('/upload-multiple', 'UploadMultipleGalleryImagesController@store')
                    ->name('store');
            });
            Route::delete('/{galleryType}', 'BulkDeleteGalleryImagesController')->name('gallery.bulk-delete');
        });
        Route::resource('ctas', 'CtasController', [
            'except' => ['show'],
        ]);
        Route::resource('dealers', 'DealersController', [
            'except' => ['show'],
        ]);
        Route::group([
            'namespace' => 'Dealer',
        ], function ($router) {
            $router->resource('dealers.employees', 'EmployeesController', [
                'except' => ['show'],
            ]);
        });
        Route::resource('email-recipients', 'EmailRecipientsController')->except(['show']);
        Route::resource('event-locations', 'EventLocationsController', [
            'except' => ['show'],
        ]);
        Route::resource('events', 'EventsController', [
            'except' => ['show'],
        ]);
        Route::resource('forms', 'FormsController', [
            'except' => ['show'],
        ]);
        Route::resource('fieldsets', 'FieldsetsController', [
            'except' => ['show'],
        ]);
        Route::group([
            'namespace' => 'Form',
            'prefix' => '/forms/{form}',
            'as' => 'forms.',
        ], function ($router) {
            $router->resource('clones', 'ClonesController', [
                'only' => ['store'],
            ]);
            $router->resource('submissions', 'SubmissionsController', [
                'only' => ['index', 'show'],
            ]);
        });
        Route::group([
            'namespace' => 'Fieldset',
            'as' => 'fieldsets.',
            'prefix' => 'fieldsets/{fieldset}',
        ], function ($router) {
            $router->resource('clones', 'ClonesController')->only(['store']);
            $router->resource('fields', 'FieldsController', [
                'except' => ['show'],
            ]);
        });
        Route::resource('image-banners', 'ImageBannersController')->except(['show']);
        Route::resource('layouts', 'LayoutsController')->except(['show', 'destroy']);
        Route::resource('logos', 'LogosController')->except(['show']);
        Route::resource('pages', 'PagesController');
        Route::resource('pages.clones', 'Pages\CloneController')->only(['create', 'store']);
        Route::resource('pages.areas', 'AreasController', [
            'except' => ['show'],
        ]);
        Route::resource('areas.panels', 'PanelsController');
        Route::resource('manufacturers', 'ManufacturersController')->except([
            'show'
        ]);

        Route::resource('navigations', 'NavigationsController')->except([
            'show'
        ]);
        Route::group([
            'namespace' => 'Navigation',
        ], function (Router $router) {
            $router->put(
                'navigations/{navigation}/navigation-items-hierarchy',
                'UpdateNavigationItemsHierarchyController'
            )->name('navigations.navigation-items-hierarchy.update');

            $router
                ->resource('navigations.navigation-items', 'NavigationItemsController')
                ->except(['show']);
        });

        Route::delete('manufacturer/destroyImage/{image}', 'ManufacturersController@destroyImage')->name('manufacturer.destroyImage');
        Route::delete('pages/destroyImage/{image}', 'PagesController@destroyImage')->name('page.destroyImage');
        Route::delete('ctas/destroyImage/{image}', 'CtasController@destroyImage')->name('cta.destroyImage');
        Route::delete('videos/destroyImage/{image}', 'VideosController@destroyImage')->name('video.destroyImage');
        Route::delete('article/destroyImage/{image}', 'ArticlesController@destroyImage')->name('article.destroyImage');
        Route::delete('brochures/destroyImage/{image}', 'BrochuresController@destroyImage')->name('brochures.destroyImage');

        Route::group([
            'namespace' => 'Manufacturer',
        ], function ($router) {
            $router->resource('manufacturers.caravan-ranges', 'CaravanRangesController', [
                'except' => 'show',
            ]);
            $router->resource('manufacturers.caravan-ranges.clones', 'CaravanRangeCloneController')->only(['create', 'store']);

            $router->resource('manufacturers.motorhome-ranges', 'MotorhomeRangesController', [
                'except' => 'show',
            ]);
            $router->resource('manufacturers.motorhome-ranges.clones', 'MotorhomeRangeCloneController')->only(['create', 'store']);
        });
        Route::group([
            'namespace' => 'Motorhome',
            'prefix' => '/motorhomes/{motorhome}',
            'as' => 'motorhomes.',
        ], function ($router) {
            $router->resource('bed-sizes', 'BedSizesController', [
                'except' => ['show'],
            ]);
            $router->resource('optional-weights', 'OptionalWeightsController')->except(['show']);
            $router->delete('night-floorplan', 'DeleteMotorhomeNightFloorplanController')->name('night-floorplan.destroy');
        });
        Route::group([
            'namespace' => 'MotorhomeRange',
            'prefix' => '/motorhome-ranges/{motorhomeRange}',
            'as' => 'motorhome-ranges.',
        ], function ($router) {
            $router->resource('motorhomes', 'MotorhomesController', [
                'except' => ['show'],
            ]);
            $router->resource('exterior-gallery-images', 'ExteriorGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('feature-gallery-images', 'FeatureGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('interior-gallery-images', 'InteriorGalleryImagesController', [
                'except' => ['edit', 'update', 'show'],
            ]);
            $router->resource('range-features', 'RangeFeaturesController', [
                'except' => ['show'],
            ]);
            $router->resource('range-specification-small-prints', 'RangeSpecificationSmallPrintsController', [
                'except' => ['show'],
            ]);
            $router->group([
                'prefix' => '/{galleryType}',
                'as' => 'gallery.upload-multiple.',
            ], function ($router) {
                $router->get('/upload-multiple', 'UploadMultipleGalleryImagesController@create')
                    ->name('create');
            });
            Route::delete('/{galleryType}', 'BulkDeleteGalleryImagesController')->name('gallery.bulk-delete');
        });
        Route::resource('pop-ups', 'PopUpsController', [
            'except' => ['show'],
        ]);
        Route::group([
            'namespace' => 'RangeFeature',
            'as' => 'range-features.',
            'prefix' => 'range-feature/{rangeFeature}',
        ], function ($router) {
            $router->resource('clones', 'ClonesController', [
                'only' => ['store'],
            ]);
        });
        Route::group([
            'namespace' => 'RangeSpecificationSmallPrint',
            'as' => 'range-specification-small-prints.',
            'prefix' => 'range-specification-small-print/{rangeSpecificationSmallPrint}',
        ], function ($router) {
            $router->resource('clones', 'ClonesController', [
                'only' => ['create', 'store'],
            ]);
        });
        Route::resource('seats', 'SeatsController')->except(['show']);
        Route::resource('site-settings', 'SiteSettingsController')->except('show');
        Route::resource('sites', 'SitesController', [
            'except' => ['show'],
        ]);
        Route::group([
            'namespace' => 'Site',
        ], function ($router) {
            $router->resource('sites.opening-times', 'OpeningTimesController')->except(['show']);
        });

        Route::resource('special-offers', 'SpecialOffersController', [
            'except' => ['show'],
        ]);
        Route::resource('testimonials', 'TestimonialsController', [
            'except' => ['show'],
        ]);
        Route::resource('useful-link-categories', 'UsefulLinkCategoriesController')->except(['show']);
        Route::resource('useful-links', 'UsefulLinksController')->except(['show']);
        Route::resource('users', 'UsersController')->except(['show']);
        Route::resource('video-banners', 'VideoBannersController', [
            'except' => ['show'],
        ]);
        Route::resource('videos', 'VideosController', [
            'except' => ['show'],
        ]);
        Route::resource('brochures', 'BrochuresController', [
            'except' => ['show'],
        ]);
        Route::resource('brochure-groups', 'BrochureGroupsController', [
            'except' => ['show'],
        ]);
        Route::resource('review-categories', 'ReviewCategoriesController', [
            'except' => ['show'],
        ]);
        Route::resource('reviews', 'ReviewsController', [
            'except' => ['show'],
        ]);
        Route::resource('stock-search-links', 'StockSearchLinksController')->except(['show']);
        Route::resource('video-categories', 'VideoCategoriesController', [
            'except' => ['show'],
        ]);
        Route::resource('vacancies', 'VacanciesController', [
            'except' => ['show'],
        ]);
        Route::resource('vacancies.vacancy-applications', 'VacancyApplicationsController', [
            'only' => ['index', 'show'],
        ]);

        /**
         * Routes for buttonables - things that have buttons attached
         *
         * They should use the Buttonable trait and implement the HasButtons interface
         *
         * The buttonable binding is explicitly resolved in the route service provider
         * using the route name, for new buttonables this will need amending
         *
         * The redirect url in the buttons controller assumes the route name will be
         * a pluralised version of the buttonable class base name
         * e.g. ImageBanner => admin.image-banners.buttons.index
         */
        Route::group([
            'prefix' => '/image-banners/{buttonable}',
            'as' => 'image-banners.',
            'namespace' => 'Buttonable',
        ], function ($router) {
            $router->resource('buttons', 'ButtonsController')->except(['show']);
        });
        Route::group([
            'prefix' => '/special-offers/{buttonable}',
            'as' => 'special-offers.',
            'namespace' => 'Buttonable',
        ], function ($router) {
            $router->resource('buttons', 'ButtonsController')->except(['show']);
        });
    }
);

/**
 * For javascript requests from the admin.
 */
Route::group([
    'namespace' => 'Api\Admin',
    'prefix' => 'api/admin',
    'as' => 'api.admin.',
    'middleware' => ['auth'],
], function ($router) {
    $router->get('search-pages', 'SearchPagesController')->name('search-pages');
    $router->resource('pages', 'PagesController', [
        'only' => ['index', 'show'],
    ]);
    Route::group([
        'namespace' => 'CaravanRange',
        'prefix' => '/caravan-ranges/{caravanRange}/{galleryType}',
        'as' => 'caravan-ranges.gallery.upload-multiple.',
    ], function ($router) {
        $router->post('/upload-multiple', 'UploadMultipleGalleryImagesController@store')
            ->name('store');
    });
    Route::group([
        'namespace' => 'FeedStockItems',
        'prefix' => '/feed-stock-items',
        'as' => 'feed-stock-items.',
    ], function ($router) {
        $router->get('caravan-search', 'CaravanSearchController')->name('caravan-search');
        $router->get('motorhome-search', 'MotorhomeSearchController')->name('motorhome-search');
    });
    Route::group([
        'namespace' => 'MotorhomeRange',
        'prefix' => '/motorhome-ranges/{motorhomeRange}/{galleryType}',
        'as' => 'motorhome-ranges.gallery.upload-multiple.',
    ], function ($router) {
        $router->post('/upload-multiple', 'UploadMultipleGalleryImagesController@store')
            ->name('store');
    });
    $router->resource('forms', 'FormsController', [
        'only' => ['index'],
    ]);
    $router->resource('special-offers', 'SpecialOffersController', [
        'only' => ['index'],
    ]);
    $router->resource('videos', 'VideosController', [
        'only' => ['index'],
    ]);
    $router->resource('events', 'EventsController', [
        'only' => ['index'],
    ]);
    $router->resource('brochures', 'BrochuresController', [
        'only' => ['index'],
    ]);
    $router->resource('reviews', 'ReviewsController', [
        'only' => ['index'],
    ]);
    $router->post('wysiwyg-file-uploads', 'WysiwygFileUploadsController@store')
        ->name('wysiwyg-file-uploads.store');
});

Route::group([
    'middleware' => ['setCurrentSite', 'aliasRedirection'],
    'namespace' => 'Site',
], function ($router) {
    $router->get('/sitemap', 'SitemapController@index');
    $router->post('/forms/{form}', 'FormSubmissionsController')->name('form-submissions');
    $router->post('/vacancies/{vacancy}', 'VacancyApplicationsController')->name('vacancy-applications');
    $router->get('/', 'PageController@homepage')->name('homepage');
    $router->get('/{page}/{childPage?}', 'PageController')->name('site');
});
