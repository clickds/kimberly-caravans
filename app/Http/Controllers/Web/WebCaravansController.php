<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebCaravansController
 * @package App\Http\Controllers\Web
 */
class WebCaravansController extends Controller
{

    /**
     * @return View
     */
    public function index() : View
    {
        return view('caravans.index');
    }

    /**
     * @return View
     */
    public function new() : View
    {
        return view('caravans.new');
    }

    /**
     * @return View
     */
    public function used() : View
    {
        return view('caravans.used');
    }
}
