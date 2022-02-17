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

    /**
     * @return View
     */
    public function webuy() : View
    {
        return view('pages.webuy');
    }

    /**
     * @return View
     */
    public function privacy() : View
    {
        return view('pages.privacy');
    }

    /**
     * @return View
     */
    public function tc() : View
    {
        return view('pages.tc');
    }

    /**
     * @return View
     */
    public function cookie() : View
    {
        return view('pages.cookie');
    }

    /**
     * @return View
     */
    public function sitemap() : View
    {
        return view('pages.sitemap');
    }
}
