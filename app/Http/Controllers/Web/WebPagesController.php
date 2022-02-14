<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebPagesController
 * @package App\Http\Controllers\Web
 */
class WebPagesController extends Controller
{

    /**
     * @return View
     */
    public function about() : View
    {
        return view('pages.about');
    }

    /**
     * @return View
     */
    public function contact() : View
    {
        return view('pages.contact');
    }

    /**
     * @return View
     */
    public function locations() : View
    {
        return view('pages.locations');
    }
}
