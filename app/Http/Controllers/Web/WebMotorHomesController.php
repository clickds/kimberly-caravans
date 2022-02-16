<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CaravanRepository;
use Illuminate\Contracts\View\View;

/**
 * Class WebMotorHomesController
 * @package App\Http\Controllers\Web
 */
class WebMotorHomesController extends Controller
{

    private CaravanRepository $caravansRepo;

    public function __construct(CaravanRepository $caravanRepository)
    {
        $this->caravansRepo = $caravanRepository;
    }

    /**
     * @return View
     */
    public function index() : View
    {
        // fetch
        $new = $this->caravansRepo->getNewByCategory('Motorhome');
        $used  = $this->caravansRepo->getUsedByCategory('Motorhome');

        return view('motor_homes.index',[
            'new_motor_homes'=>$new,
            'used_motor_homes'=>$used
        ]);
    }

    /**
     * @return View
     */
    public function new() : View
    {

        // fetch
        $motorHomes = $this->caravansRepo->getNewByCategory('Motorhome');

        return view('motor_homes.new',[
            'motor_homes' => $motorHomes
        ]);
    }

    /**
     * @return View
     */
    public function used() : View
    {

        // fetch
        $motorHomes = $this->caravansRepo->getUsedByCategory('Motorhome');

        return view('motor_homes.used',[
            'motor_homes' => $motorHomes
        ]);
    }
}
