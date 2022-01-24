<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\Site\OpeningTimes\StoreRequest;
use App\Http\Requests\Admin\Site\OpeningTimes\UpdateRequest;
use App\Models\OpeningTime;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OpeningTimesController extends BaseController
{
    public function index(Site $site): View
    {
        $openingTimes = $site->openingTimes()->get();

        return view('admin.site.opening-times.index', [
            'site' => $site,
            'openingTimes' => $openingTimes,
        ]);
    }

    public function create(Site $site): View
    {
        $openingTime = $site->openingTimes()->make([
            'closes_at' => '17:00',
            'opens_at' => '09:00',
        ]);

        return view('admin.site.opening-times.create', [
            'site' => $site,
            'openingTime' => $openingTime,
            'days' => $this->fetchDays(),
        ]);
    }

    public function store(StoreRequest $request, Site $site): RedirectResponse
    {
        $openingTime = $site->openingTimes()->make();
        $this->saveOpeningTime($openingTime, $request);

        return redirect()
            ->route('admin.sites.opening-times.index', $site)
            ->with('success', 'Opening times created');
    }

    public function edit(Site $site, OpeningTime $openingTime): View
    {
        return view('admin.site.opening-times.edit', [
            'site' => $site,
            'openingTime' => $openingTime,
            'days' => $this->fetchDays(),
        ]);
    }

    public function update(UpdateRequest $request, Site $site, OpeningTime $openingTime): RedirectResponse
    {
        $this->saveOpeningTime($openingTime, $request);

        return redirect()
            ->route('admin.sites.opening-times.index', $site)
            ->with('success', 'Opening times updated');
    }

    public function destroy(Site $site, OpeningTime $openingTime): RedirectResponse
    {
        $openingTime->delete();

        return redirect()
            ->route('admin.sites.opening-times.index', $site)
            ->with('success', 'Opening time deleted');
    }

    private function saveOpeningTime(OpeningTime $openingTime, FormRequest $request): void
    {
        $openingTime->fill($request->validated());
        $openingTime->save();
    }

    private function fetchDays(): array
    {
        return Carbon::getDays();
    }
}
