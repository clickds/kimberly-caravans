<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CaravanRepository;
use Illuminate\Contracts\View\View;

/**
 * Class WebCaravansController
 * @package App\Http\Controllers\Web
 */
class WebCaravansController extends Controller
{

    /**
     * @var CaravanRepository
     */
    private CaravanRepository $caravanRepo;

    /**
     * WebCaravansController constructor.
     * @param CaravanRepository $caravanRepository
     */
    public function __construct(CaravanRepository $caravanRepository)
    {
        $this->caravanRepo = $caravanRepository;
    }


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

        // fetch
        $caravans = $this->caravanRepo->getNewByCategory();

        return view('caravans.new',[
            'caravans'=>$caravans
        ]);
    }

    /**
     * @return View
     */
    public function used() : View
    {

        // fetch
        $caravans = $this->caravanRepo->getUsedByCategory();

        return view('caravans.used',[
            'caravans'=>$caravans
        ]);
    }
}
