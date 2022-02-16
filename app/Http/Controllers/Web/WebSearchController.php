<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class WebSearchController
 * @package App\Http\Controllers\Web
 */
class WebSearchController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {

        // hold results
        $caravans = null;

        // validate request
        if($request->validate([
            'type' => 'integer|null',
            'category' => 'integer|null', // the condition field populated with category model
            'berths' => 'integer|null',
            //'finance'
            'branch' => 'integer|null' // drop down with branch id from model
        ])){
            // pull results
            $caravans = $this->caravanRepo->search($request->all());
        }

        return view('caravans.search',[
            'caravans' => $caravans
        ]);
    }
}
