<?php

namespace App\Presenters;

use App\Models\Dealer;
use App\Models\DealerLocation;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use McCool\LaravelAutoPresenter\BasePresenter;
use UnexpectedValueException;

class DealerPresenter extends BasePresenter
{
    public function getFacilitiesContent(): ?string
    {
        return $this->getDealer()->facilities;
    }

    public function getServicingCentreContent(): ?string
    {
        return $this->getDealer()->servicing_centre;
    }

    public function getEmployees(): Collection
    {
        return $this->getDealer()->employees()->orderBy('position', 'asc')->get();
    }

    public function getPostcode(): ?string
    {
        return $this->getDealerLocation()->postcode;
    }

    public function getLongitude(): float
    {
        return $this->getDealerLocation()->longitude;
    }

    public function getLatitude(): float
    {
        return $this->getDealerLocation()->latitude;
    }

    public function getGoogleMapsUrl(): string
    {
        return $this->getDealerLocation()->google_maps_url;
    }

    public function getGoogleMapsDirectionApiUrl(): string
    {
        return config('google-maps.directions-api-url', '');
    }

    public function getOpeningHoursContent(): ?string
    {
        return $this->getDealerLocation()->opening_hours;
    }

    public function getDealerPageUrl(): string
    {
        $currentSite = resolve('currentSite');

        $sitePage = $this->getDealer()->sitePage($currentSite);

        if (is_null($sitePage) || !is_a($sitePage, Page::class)) {
            return '';
        }

        return pageLink($sitePage);
    }

    public function getAddressAsSingleLine(): string
    {
        $dealerLocation = $this->getDealerLocation();

        $addressArray = $dealerLocation->only(['line_1', 'line_2', 'town', 'county', 'postcode']);

        return implode(", ", array_filter($addressArray));
    }

    public function getFormattedAddress(): string
    {
        $dealerLocation = $this->getDealerLocation();

        $addressArray = $dealerLocation->only(['line_1', 'line_2', 'town', 'county', 'postcode']);

        return implode("\n", array_filter($addressArray));
    }

    public function getPhoneNumber(): ?string
    {
        $dealerLocation = $this->getDealerLocation();

        return $dealerLocation->phone;
    }

    public function getFaxNumber(): ?string
    {
        $dealerLocation = $this->getDealerLocation();

        return $dealerLocation->fax;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->getDealer()->website_url;
    }

    public function getWebsiteLinkText(): ?string
    {
        return $this->getDealer()->website_link_text;
    }

    public function getDistanceFromDealerLocationInMiles(): string
    {
        $distanceInMiles = $this->getDealerLocation()->distance_miles;

        if (!$distanceInMiles) {
            return '';
        }

        return sprintf('%s miles away', number_format($distanceInMiles, 1));
    }

    private function getDealer(): Dealer
    {
        $dealer = $this->getWrappedObject();

        if (is_null($dealer) || !is_a($dealer, Dealer::class)) {
            throw new UnexpectedValueException('Expected an instance of Dealer');
        }

        return $dealer;
    }

    private function getDealerLocation(): DealerLocation
    {
        $dealerLocation = $this->getDealer()->locations->first();

        if (is_null($dealerLocation) || !is_a($dealerLocation, DealerLocation::class)) {
            throw new UnexpectedValueException('Expected an instance of DealerLocation');
        }

        return $dealerLocation;
    }
}
