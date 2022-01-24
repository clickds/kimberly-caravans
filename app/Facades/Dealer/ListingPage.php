<?php

namespace App\Facades\Dealer;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\Dealer;
use App\Models\Page;
use Spatie\Geocoder\Facades\Geocoder;

class ListingPage extends BasePage
{
    private const GEOCODER_RESULT_LATITUDE_KEY = 'lat';
    private const GEOCODER_RESULT_LONGITUDE_KEY = 'lng';
    private const GEOCODER_RESULT_ACCURACY_KEY = 'accuracy';
    private const GEOCODER_RESULT_NOT_FOUND = 'result_not_found';

    private string $postcode = '';
    private array $geolocationResults = [];
    private Collection $dealers;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->postcode = $request->get('postcode', '');
        $this->geolocationResults = $this->fetchGeolocationDataForPostcode();
        $this->dealers = $this->fetchDealers();
    }

    public function getDealerListingPageUrl(): string
    {
        $dealerListingPage = Page::where('site_id', $this->getSite()->id)
            ->template(Page::TEMPLATE_DEALERS_LISTING)
            ->first();

        if (!$dealerListingPage) {
            return '';
        }

        return pageLink($dealerListingPage, false);
    }

    public function getDealers(): Collection
    {
        return $this->dealers;
    }

    public function getDealerCount(): int
    {
        return $this->dealers->filter(fn(Dealer $dealer) => $dealer->is_branch)->count();
    }

    public function hasSelectedPostcode(): bool
    {
        return '' !== $this->postcode;
    }

    public function getSelectedPostcode(): string
    {
        return $this->postcode;
    }

    public function hasGeolocationData(): bool
    {
        if (empty($this->geolocationResults)) {
            return false;
        }

        /**
         * If no geolocation data is found for the postcode entered, the returned array will have result_not_found
         * as the value of the accuracy key.
         */
        if (
            isset($this->geolocationResults[self::GEOCODER_RESULT_ACCURACY_KEY])
            && $this->geolocationResults[self::GEOCODER_RESULT_ACCURACY_KEY] === self::GEOCODER_RESULT_NOT_FOUND
        ) {
            return false;
        }

        /**
         * Ensure both the latitude and longitude values are present as they are used elsewhere in this class.
         */
        return isset($this->geolocationResults[self::GEOCODER_RESULT_LATITUDE_KEY])
            && isset($this->geolocationResults[self::GEOCODER_RESULT_LONGITUDE_KEY]);
    }

    public function getDealerMapData(): Collection
    {
        return $this->dealers->filter(function ($dealer) {
            return $dealer->locations->count() > 0;
        })->map(function ($dealer) {
            return [
                'location' => [
                    'lat' => (float) $dealer->locations->first()->latitude,
                    'lng' => (float) $dealer->locations->first()->longitude,
                ],
                'infoWindowContent' => view(
                    'site.pages.dealers-listing.map-info-window',
                    ['dealer' => $dealer]
                )->render(),
            ];
        });
    }

    public function getListViewUrl(): string
    {
        return request()->fullUrlWithQuery(['view' => 'list']);
    }

    public function listViewSelected(): bool
    {
        return 'map' !== request()->get('view');
    }

    public function getMapViewUrl(): string
    {
        return request()->fullUrlWithQuery(['view' => 'map']);
    }

    public function mapViewSelected(): bool
    {
        $view = request()->get('view');
        return !is_null($view) && $view === 'map';
    }

    private function fetchGeolocationDataForPostcode(): array
    {
        if (!$this->hasSelectedPostcode()) {
            return [];
        }

        return Geocoder::getCoordinatesForAddress($this->postcode);
    }

    private function fetchDealers(): Collection
    {
        if ($this->hasSelectedPostcode() && $this->hasGeolocationData()) {
            $dealers = Dealer::where('site_id', $this->getSite()->id)
                ->with(['locations' => function ($query) {
                    $query->selectDistanceToInMiles([
                        $this->geolocationResults['lng'],
                        $this->geolocationResults['lat'],
                    ]);
                }])
                ->get();

            $dealersSortedByDistance = $dealers->sortBy(function ($dealer) {
                return $dealer->locations->first()->distance_miles;
            })->values();

            return $dealersSortedByDistance;
        }

        return Dealer::where('site_id', $this->getSite()->id)
            ->with('locations')
            ->orderBy('position', 'asc')
            ->get();
    }
}
