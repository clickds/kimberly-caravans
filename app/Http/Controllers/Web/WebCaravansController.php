<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CaravanRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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

        $new = $this->caravanRepo->getNewByCategory();
        $used = $this->caravanRepo->getUsedByCategory();


        return view('caravans.index',[
            'new_caravans'=>$new,
            'used_caravans'=>$used
        ]);
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
