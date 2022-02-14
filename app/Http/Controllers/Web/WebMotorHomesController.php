<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebMotorHomesController
 * @package App\Http\Controllers\Web
 */
class WebMotorHomesController extends Controller
{

    /**
     * @return View
     */
    public function index() : View
    {
        return view('motor_homes.index');
    }

    /**
     * @return View
     */
    public function new() : View
    {
        return view('motor_homes.new');
    }

    /**
     * @return View
     */
    public function used() : View
    {
        return view('motor_homes.used');
    }
}
