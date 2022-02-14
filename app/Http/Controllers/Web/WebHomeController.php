<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebHomeController
 * @package App\Http\Controllers\Web
 */
class WebHomeController extends Controller
{

    /**
     * @return View
     */
    public function index() : View
    {
        return view('home.index');
    }
}
