<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Class WebNewsController
 * @package App\Http\Controllers\Web
 */
class WebNewsController extends Controller
{

    /**
     * @return View
     */
    public function index() : View
    {
        return view('news.index');
    }
}
