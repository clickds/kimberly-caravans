<?php

namespace App\Facades\Manufacturer;

use App\Facades\BasePage;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class MotorhomesPage extends BasePage
{
    /**
     * @var Manufacturer
     */
    private $manufacturer;

    /**
     * @var Collection
     */
    private $motorhomeRanges;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);
        $manufacturer = $page->pageable;
        $this->manufacturer = $manufacturer;
        $this->motorhomeRanges = $this->fetchMotorhomeRanges($manufacturer, $this->getSite());
    }

    public function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function getMotorhomeRanges(): Collection
    {
        return $this->motorhomeRanges;
    }

    private function fetchMotorhomeRanges(Manufacturer $manufacturer, Site $site): Collection
    {
        return $manufacturer->motorhomeRanges()->live()->orderBy('position', 'asc')
            ->whereHas('sites', function ($query) use ($site) {
                $query->where('id', $site->id);
            })
            ->with([
                'media' => function ($query) {
                    $query->where('collection_name', 'mainImage');
                },
                'motorhomes' => function ($query) use ($site) {
                    $query->live()->whereHas('sites', function ($query) use ($site) {
                        $query->where('id', $site->id);
                    })->orderBy('position', 'asc')
                        ->with([
                            'berths' => function ($query) {
                                $query->orderBy('number', 'asc');
                            },
                            'seats' => function ($query) {
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
