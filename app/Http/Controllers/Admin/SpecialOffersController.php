<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SpecialOffers\StoreRequest;
use App\Http\Requests\Admin\SpecialOffers\UpdateRequest;
use App\Models\CaravanStockItem;
use App\Models\Manufacturer;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\SpecialOffer;
use App\Services\Pageable\SpecialOfferPageSaver;
use App\Services\SpecialOffer\IconFinder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SpecialOffersController extends BaseController
{
    public function index(Request $request): View
    {
        $specialOffers = SpecialOffer::ransack($request->all())
            ->orderedByPosition()
            ->orderBy('published_at', 'desc')->paginate(20);

        return view('admin.special-offers.index', [
            'specialOffers' => $specialOffers,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_SPECIAL_OFFERS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        $specialOffer = new SpecialOffer();

        return view('admin.special-offers.create', [
            'currentSiteIds' => [],
            'currentCaravanIds' => [],
            'currentMotorhomeIds' => [],
            'manufacturers' => $this->fetchManufacturers(),
            'specialOffer' => $specialOffer,
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'icons' => $this->fetchIcons(),
            'offerTypes' => $this->fetchOfferTypes(),
            'stockBarColours' => $this->fetchStockBarColours(),
            'stockBarTextColours' => $this->fetchStockBarTextColours(),
            'currentCaravanFeedStockItems' => $this->fetchCurrentCaravanFeedStockItems($specialOffer),
            'currentMotorhomeFeedStockItems' => $this->fetchCurrentMotorhomeFeedStockItems($specialOffer),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $specialOffer = new SpecialOffer();
        if ($this->saveSpecialOffer($specialOffer, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Special offer created');
            }

            return redirect()
                ->route('admin.special-offers.index')
                ->with('success', 'Special offer created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to create special offer');
    }

    public function edit(SpecialOffer $special_offer, Request $request): View
    {
        return view('admin.special-offers.edit', [
            'manufacturers' => $this->fetchManufacturers(),
            'specialOffer' => $special_offer,
            'currentSiteIds' => $special_offer->sites()->pluck('id')->toArray(),
            'currentCaravanIds' => $special_offer->caravans()->pluck('id')->toArray(),
            'currentMotorhomeIds' => $special_offer->motorhomes()->pluck('id')->toArray(),
            'sites' => $this->fetchSites(),
            'types' => $this->fetchTypes(),
            'icons' => $this->fetchIcons(),
            'offerTypes' => $this->fetchOfferTypes(),
            'stockBarColours' => $this->fetchStockBarColours(),
            'stockBarTextColours' => $this->fetchStockBarTextColours(),
            'currentCaravanFeedStockItems' => $this->fetchCurrentCaravanFeedStockItems($special_offer),
            'currentMotorhomeFeedStockItems' => $this->fetchCurrentMotorhomeFeedStockItems($special_offer),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, SpecialOffer $special_offer): RedirectResponse
    {
        if ($this->saveSpecialOffer($special_offer, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Special offer updated');
            }

            return redirect()
                ->route('admin.special-offers.index')
                ->with('success', 'Special offer updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to update special offer');
    }

    public function destroy(SpecialOffer $special_offer, Request $request): RedirectResponse
    {
        $special_offer->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Special offer deleted');
        }

        return redirect()
            ->route('admin.special-offers.index')
            ->with('success', 'Special offer deleted');
    }

    private function saveSpecialOffer(SpecialOffer $specialOffer, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $specialOffer->fill($data);
            $specialOffer->save();
            $this->syncSites($specialOffer, $request);
            $this->syncNewCaravans($specialOffer, $request);
            $this->syncNewMotorhomes($specialOffer, $request);
            if ($this->shouldSyncIndividualStockItems($specialOffer)) {
                $this->syncFeedCaravans($specialOffer, $request);
                $this->syncFeedMotorhomes($specialOffer, $request);
            }
            $this->savePages($specialOffer);
            $this->addImages($specialOffer, $request);
            $this->addDocument($specialOffer, $request);
            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }

    private function savePages(SpecialOffer $specialOffer): void
    {
        $specialOffer->pages()->whereNotIn('site_id', $specialOffer->sites()->pluck('id'))->delete();
        foreach ($specialOffer->sites as $site) {
            $saver = new SpecialOfferPageSaver($specialOffer, $site);
            $saver->call();
        }
    }

    private function addImages(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $imageInputs = [
            'landscape_image' => 'landscapeImage',
            'square_image' => 'squareImage',
        ];

        foreach ($imageInputs as $inputKey => $collection) {
            if ($request->hasFile($inputKey)) {
                $specialOffer->addMediaFromRequest($inputKey)->toMediaCollection($collection);
            }
        }
    }

    private function addDocument(SpecialOffer $specialOffer, FormRequest $request): void
    {
        if ($request->hasFile('document')) {
            $specialOffer->addMediaFromRequest('document')->toMediaCollection('document');
        }
    }

    private function syncSites(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $siteIds = $request->get('site_ids');
        $specialOffer->sites()->sync($siteIds);
    }

    private function syncNewCaravans(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $caravanIds = $request->get('caravan_ids', []);
        $specialOffer->caravans()->sync($caravanIds);
        // Detach stock items that are attached to caravans - "Virtual Stock" where they should no longer be attached
        $oldCaravanStockItemIds = $specialOffer->caravanStockItems()->whereNotNull('caravan_id')
            ->toBase()->pluck('id')->toArray();
        $specialOffer->caravanStockItems()->detach($oldCaravanStockItemIds);
        $newCaravanStockItemIds = CaravanStockItem::whereIn('caravan_id', $caravanIds)
            ->toBase()->pluck('id')->toArray();
        // Attach the new virtual stock items
        $specialOffer->caravanStockItems()->attach($newCaravanStockItemIds);
    }

    private function syncNewMotorhomes(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $motorhomeIds = $request->get('motorhome_ids', []);
        $specialOffer->motorhomes()->sync($motorhomeIds);
        // Detach stock items that are attached to motorhomes - "Virtual Stock" where they should no longer be attached
        $oldMotorhomeStockItemIds = $specialOffer->motorhomeStockItems()->whereNotNull('motorhome_id')
            ->toBase()->pluck('id')->toArray();
        $specialOffer->motorhomeStockItems()->detach($oldMotorhomeStockItemIds);
        $newMotorhomeStockItemIds = MotorhomeStockItem::whereIn('motorhome_id', $motorhomeIds)
            ->toBase()->pluck('id')->toArray();
        // Attach the new virtual stock items
        $specialOffer->motorhomeStockItems()->attach($newMotorhomeStockItemIds);
    }

    private function syncFeedCaravans(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $feedIds = $request->get('feed_caravan_stock_item_ids', []);
        // Detach old feed stock items
        $oldFeedIds = $specialOffer->caravanStockItems()->fromFeed()->toBase()->pluck('id')->toArray();
        $specialOffer->caravanStockItems()->detach($oldFeedIds);
        // Attach the new stock items
        $specialOffer->caravanStockItems()->attach($feedIds);
    }

    private function syncFeedMotorhomes(SpecialOffer $specialOffer, FormRequest $request): void
    {
        $feedIds = $request->get('feed_motorhome_stock_item_ids', []);
        // Detach old feed stock items
        $oldFeedIds = $specialOffer->motorhomeStockItems()->fromFeed()->toBase()->pluck('id')->toArray();
        $specialOffer->motorhomeStockItems()->detach($oldFeedIds);
        // Attach the new stock items
        $specialOffer->motorhomeStockItems()->attach($feedIds);
    }

    private function shouldSyncIndividualStockItems(SpecialOffer $specialOffer): bool
    {
        $collection = collect([
            $specialOffer->link_used_caravan_stock,
            $specialOffer->link_used_motorhome_stock,
            $specialOffer->link_managers_special_stock,
            $specialOffer->link_on_sale_stock,
        ]);
        return !$collection->contains(true);
    }

    private function fetchStockBarColours(): array
    {
        return SpecialOffer::STOCK_BAR_COLOURS;
    }

    private function fetchStockBarTextColours(): array
    {
        return SpecialOffer::STOCK_BAR_TEXT_COLOURS;
    }

    private function fetchManufacturers(): Collection
    {
        return Manufacturer::with([
            'caravanRanges' => function ($query) {
                $query->whereHas('caravans')->with('caravans:id,name,caravan_range_id')
                    ->select('id', 'name', 'manufacturer_id');
            },
            'motorhomeRanges' => function ($query) {
                $query->whereHas('motorhomes')->with('motorhomes:id,name,motorhome_range_id')
                    ->select('id', 'name', 'manufacturer_id');
            },
        ])->orderBy('name', 'asc')->select('name', 'id')->get();
    }

    private function fetchTypes(): array
    {
        return SpecialOffer::TYPES;
    }

    private function fetchOfferTypes(): array
    {
        return SpecialOffer::OFFER_TYPES;
    }

    private function fetchCurrentCaravanFeedStockItems(SpecialOffer $specialOffer): array
    {
        $currentIds = $specialOffer->caravanStockItems()->fromFeed()->pluck('id')->toArray();
        $ids = old('feed_caravan_stock_item_ids', $currentIds);
        return CaravanStockItem::whereIn('id', $ids)
            ->select('id', 'model', 'unique_code')->get()->toArray();
    }

    private function fetchCurrentMotorhomeFeedStockItems(SpecialOffer $specialOffer): array
    {
        $currentIds = $specialOffer->motorhomeStockItems()->fromFeed()->pluck('id')->toArray();
        $ids = old('feed_motorhome_stock_item_ids', $currentIds);
        return MotorhomeStockItem::whereIn('id', $ids)
            ->select('id', 'model', 'unique_code')->get()->toArray();
    }

    private function fetchIcons(): array
    {
        $finder = new IconFinder();
        return $finder->call();
    }
}
