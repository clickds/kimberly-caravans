<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Dealers\StoreRequest;
use App\Http\Requests\Admin\Dealers\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\Dealer;
use App\Models\DealerLocation;
use App\Models\MotorhomeRange;
use App\Models\Page;
use App\Models\Site;
use App\Services\Pageable\DealerPageSaver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Exception;
use Throwable;

class DealersController extends BaseController
{
    public function index(Request $request): View
    {
        $dealers = Dealer::with('site', 'pages')->ransack($request->all())
            ->withCount('employees')
            ->orderBy('position', 'asc')->get();

        return view('admin.dealers.index', [
            'dealers' => $dealers,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_DEALERS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.dealers.create', [
            'dealer' => new Dealer(),
            'location' => new DealerLocation(),
            'sites' => Site::all(),
            'caravanRanges' => CaravanRange::orderBy('name')->get(),
            'motorhomeRanges' => MotorhomeRange::orderBy('name')->get(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $dealer = Dealer::create($this->getValidatedDealerFields($request));

            $dealer->locations()->create($this->getValidatedDealerLocationFields($request));

            $this->syncRanges($dealer, $request);

            $this->maintainDealerPage($dealer);

            DB::commit();
        } catch (\Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return redirect()->route('admin.dealers.index')->with('error', 'Failed to create dealer');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Dealer created');
        }

        return redirect()
            ->route('admin.dealers.index')
            ->with('success', 'Dealer created');
    }

    public function edit(Dealer $dealer, Request $request): View
    {
        return view('admin.dealers.edit', [
            'dealer' => $dealer,
            'location' => $dealer->locations()->first(),
            'sites' => Site::all(),
            'caravanRanges' => CaravanRange::orderBy('name')->get(),
            'motorhomeRanges' => MotorhomeRange::orderBy('name')->get(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Dealer $dealer): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $dealer->update($this->getValidatedDealerFields($request));

            $dealer->locations()
                ->firstOrFail()
                ->update($this->getValidatedDealerLocationFields($request));

            $this->syncRanges($dealer, $request);

            $this->maintainDealerPage($dealer);

            DB::commit();
        } catch (\Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update dealer');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Dealer updated');
        }

        return redirect()
            ->route('admin.dealers.index')
            ->with('success', 'Dealer updated');
    }

    public function destroy(Dealer $dealer, Request $request): RedirectResponse
    {
        try {
            $dealer->delete();
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()
                ->back()
                ->with('warning', 'Could not delete dealer as it has asssociated items');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Dealer deleted');
        }

        return redirect()
            ->route('admin.dealers.index')
            ->with('success', 'Dealer deleted');
    }

    private function getValidatedDealerFields(FormRequest $request): array
    {
        $dealer = new Dealer();

        return Arr::only($request->validated(), $dealer->getFillable());
    }

    private function getValidatedDealerLocationFields(FormRequest $request): array
    {
        $location = new DealerLocation();

        return Arr::only($request->validated(), $location->getFillable());
    }

    private function maintainDealerPage(Dealer $dealer): void
    {
        $site = $dealer->site;

        if (is_null($site)) {
            throw new Exception("Dealer must have site");
        }

        $dealer->pages()->where('site_id', '!=', $site->id)->delete();

        $pageSaver = new DealerPageSaver($dealer, $site);

        $pageSaver->call();
    }

    private function syncRanges(Dealer $dealer, Request $request): void
    {
        $dealer->caravanRanges()->sync(
            $request->get('caravan_range_ids', [])
        );

        $dealer->motorhomeRanges()->sync(
            $request->get('motorhome_range_ids', [])
        );
    }
}
