<?php

use App\Http\Controllers\Web\WebAwningsController;
use App\Http\Controllers\Web\WebCaravansController;
use App\Http\Controllers\Web\WebHomeController;
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
Route::get('/about-us',[WebPagesController::class,'about']);
Route::get('/contact-us',[WebPagesController::class,'contact']);
Route::get('/our-locations',[WebPagesController::class,'locations']);
Route::get('/search',[WebSearchController::class,'index'])->name('search.index');
