<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CaravanRepository;
use Illuminate\Contracts\View\View;

/**
 * Class WebHomeController
 * @package App\Http\Controllers\Web
 */
class WebHomeController extends Controller
{

    /**
     * @var CaravanRepository
     */
    private CaravanRepository $caravanRepo;

    /**
     * WebHomeController constructor.
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

        // get caravans/motorhomes for the home page
        $newCaravans = $this->caravanRepo->getNewByCategory('Caravan', 'caravan.kimberley_date_updated');
        $newMotorHomes = $this->caravanRepo->getNewByCategory('Motorhome', 'caravan.kimberley_date_updated');
        $usedCaravans = $this->caravanRepo->getUsedByCategory('Caravan', 'caravan.kimberley_date_updated');
        $usedMotorHomes = $this->caravanRepo->getUsedByCategory('Motorhome', 'caravan.kimberley_date_updated');


        return view('home.index',[
            'new_caravans'=>$newCaravans,
            'new_motor_homes'=>$newMotorHomes,
            'used_caravans'=>$usedCaravans,
            'used_motor_homes'=>$usedMotorHomes
        ]);
    }
}
