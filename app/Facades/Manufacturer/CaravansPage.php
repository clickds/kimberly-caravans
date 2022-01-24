<?php

namespace App\Facades\Manufacturer;

use App\Facades\BasePage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Site;

class CaravansPage extends BasePage
{
    /**
     * @var Manufacturer
     */
    private $manufacturer;

    /**
     * @var Collection
     */
    private $caravanRanges;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $manufacturer = $page->pageable;
        $this->manufacturer = $manufacturer;
        $this->caravanRanges = $this->fetchCaravanRanges($manufacturer, $this->getSite());
    }

    public function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function getCaravanRanges(): Collection
    {
        return $this->caravanRanges;
    }

    private function fetchCaravanRanges(Manufacturer $manufacturer, Site $site): Collection
    {
        return $manufacturer->caravanRanges()->live()->orderBy('position', 'asc')
            ->whereHas('sites', function ($query) use ($site) {
                $query->where('id', $site->id);
            })
            ->with([
                'media' => function ($query) {
                    $query->where('collection_name', 'mainImage');
                },
                'caravans' => function ($query) use ($site) {
                    $query->live()->whereHas('sites', function ($query) use ($site) {
                        $query->where('id', $site->id);
                    })->orderBy('position', 'asc')
                        ->with([
                            'berths' => function ($query) {
                                $query->orderBy('number', 'asc');
                            },
                            'media' => function ($query) {
                                $query->where('collection_name', 'dayFloorplan');
                            },
                            'sites' => function ($query) use ($site) {
                                $query->withPivot('price')->where('id', $site->id);
                            },
                        ]);
                },
            ])->get();
    }
}
