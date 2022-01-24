<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use App\Models\Area;
use App\Models\Caravan;
use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Panel;

class DashboardController
{
    public function index(): View
    {
        return view('admin.dashboard.index', [
            'pages' => $this->fetchLatestEditedPages(),
            'areas' => $this->fetchLatestEditedAreas(),
            'panels' => $this->fetchLatestEditedPanels(),
            'manufacturers' => $this->fetchLatestEditedManufacturers(),
            'motorhomeRanges' => $this->fetchLatestEditedMotorhomeRanges(),
            'caravanRanges' => $this->fetchLatestEditedCaravanRanges(),
            'motorhomes' => $this->fetchLatestEditedMotorhomes(),
            'caravans' => $this->fetchLatestEditedCaravans(),
        ]);
    }

    private function fetchLatestEditedPages(): Collection
    {
        return Page::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedAreas(): Collection
    {
        return Area::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedPanels(): Collection
    {
        return Panel::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedManufacturers(): Collection
    {
        return Manufacturer::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedMotorhomeRanges(): Collection
    {
        return MotorhomeRange::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedCaravanRanges(): Collection
    {
        return CaravanRange::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedMotorhomes(): Collection
    {
        return Motorhome::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    private function fetchLatestEditedCaravans(): Collection
    {
        return Caravan::orderBy('updated_at', 'desc')->limit(5)->get();
    }
}
