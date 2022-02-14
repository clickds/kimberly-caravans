<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebAwningsController
 * @package App\Http\Controllers\Web
 */
class WebAwningsController extends Controller
{

    /**
     * @return View
     */
    public function index() : View
    {
        return view('awnings.index');
    }

    /**
     * @return View
     */
    public function caravan() : View
    {
        return view('awnings.caravan');
    }

    /**
     * @return View
     */
    public function motorHome() : View
    {
        return view('awnings.motor_home');
    }

    /**
     * @return View
     */
    public function camper() : View
    {
        return view('awnings.camper_van');
    }
}
