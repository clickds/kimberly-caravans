<?php

use App\Http\Controllers\Web\WebAwningsController;
use App\Http\Controllers\Web\WebCaravansController;
use App\Http\Controllers\Web\WebHomeController;
use App\Http\Controllers\Web\WebLocationsController;
use App\Http\Controllers\Web\WebMotorHomesController;
use App\Http\Controllers\Web\WebNewsController;
use App\Http\Controllers\Web\WebPagesController;
use App\Http\Controllers\Web\WebSearchController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [WebHomeController::class,'index'])->name('home');
Route::get('/caravans',[WebCaravansController::class,'index'])->name('caravans.index');
Route::get('/caravans/new',[WebCaravansController::class,'new'])->name('caravans.new');
Route::get('/caravans/used',[WebCaravansController::class,'used'])->name('caravans.used');
Route::get('/motor-homes',[WebMotorHomesController::class,'index'])->name('motorhomes.index');
Route::get('/motor-homes/new',[WebMotorHomesController::class,'new'])->name('motorhomes.new');
Route::get('/motor-homes/used',[WebMotorHomesController::class,'used'])->name('motorhomes.used');
Route::get('/awnings',[WebAwningsController::class,'index'])->name('awnings.index');
Route::get('/awnings/caravan',[WebAwningsController::class,'caravan'])->name('awnings.caravan');
Route::get('/awnings/camper-van',[WebAwningsController::class,'camper'])->name('awnings.campervan');
Route::get('/awnings/motor-home',[WebAwningsController::class,'motorHome'])->name('awnings.motorhome');
Route::get('/news',[WebNewsController::class,'index'])->name('news.index');
Route::get('/news/{news}',[WebNewsController::class,'single'])->name('news.single');
Route::get('/about-us',[WebPagesController::class,'about'])->name('page.about');
Route::get('/contact-us',[WebPagesController::class,'contact'])->name('page.contact');
Route::get('/we-buy',[WebPagesController::class,'webuy'])->name('page.webuy');
Route::get('/privacy-policy',[WebPagesController::class,'privacy'])->name('page.privacy');
Route::get('/terms-conditions',[WebPagesController::class,'tc'])->name('page.tc');
Route::get('/cookie-policy',[WebPagesController::class,'cookie'])->name('page.cookie');
Route::get('/sitemap',[WebPagesController::class,'sitemap'])->name('page.sitemap');
Route::get('/our-locations',[WebLocationsController::class,'index'])->name('locations.index');
Route::get('/our-locations/{location}',[WebLocationsController::class,'single'])->name('locations.single');
Route::get('/search',[WebSearchController::class,'index'])->name('search.index');

// Temporary Routes to Show HTML.
Route::view('/awning-list', 'static/awning-list');


