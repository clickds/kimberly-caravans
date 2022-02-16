<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Repositories\LocationsRepository;
use Illuminate\Contracts\View\View;

/**
 * Class WebLocationsController
 * @package App\Http\Controllers\Web
 */
class WebLocationsController extends Controller
{
    /**
     * @var LocationsRepository
     */
    private LocationsRepository $locationsRepo;

    /**
     * WebLocationsController constructor.
     * @param LocationsRepository $locationsRepository
     */
    public function __construct(private LocationsRepository $locationsRepository)
    {
        $this->locationsRepo = $$this->locationsRepository;
    }

    /**
     * @return View
     */
    public function index() : View
    {

        // get all locations
        $locations = $this->locationsRepository->getAll();

        return view('locations.index',[
            'locations'=>$locations
        ]);
    }

    /**
     * @param Location $location
     * @return View
     */
    public function single(Location $location) : View
    {

        return view('locations.single',[
            'location'=>$location
        ]);
    }
}
