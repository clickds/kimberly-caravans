<?php

use App\Http\Controllers\Web\WebAwningsController;
use App\Http\Controllers\Web\WebCaravansController;
use App\Http\Controllers\Web\WebHomeController;
use App\Http\Controllers\Web\WebMotorHomesController;
use App\Http\Controllers\Web\WebNewsController;
use App\Http\Controllers\Web\WebPagesController;
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

Route::get('/', [WebHomeController::class,'index']);
Route::get('/caravans',[WebCaravansController::class,'index']);
Route::get('/caravans/new',[WebCaravansController::class,'new']);
Route::get('/caravans/used',[WebCaravansController::class,'used']);
Route::get('/motor-homes',[WebMotorHomesController::class,'index']);
Route::get('/motor-homes/new',[WebMotorHomesController::class,'new']);
Route::get('/motor-homes/used',[WebMotorHomesController::class,'used']);
Route::get('/awnings',[WebAwningsController::class,'index']);
Route::get('/awnings/caravan',[WebAwningsController::class,'caravan']);
Route::get('/awnings/camper-van',[WebAwningsController::class,'camper']);
Route::get('/awnings/motor-home',[WebAwningsController::class,'motorHome']);
Route::get('/news',[WebNewsController::class,'index']);
Route::get('/news/{news}',[WebNewsController::class,'single']);
Route::get('/about-us',[WebPagesController::class,'about']);
Route::get('/contact-us',[WebPagesController::class,'contact']);
Route::get('/our-locations',[WebPagesController::class,'locations']);
