<?php

namespace App\Presenters;

use App\Presenters\Page\BasePagePresenter;
use McCool\LaravelAutoPresenter\BasePresenter;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CaravanRangePresenter extends BasePresenter
{
    public function numberOfModels(): int
    {
        return $this->wrappedObject->caravans->count();
    }

    public function lowestPrice(SitePresenter $sitePresenter): string
    {
        $price = $this->wrappedObject->caravans->map(function ($caravan) {
            // The eager load means only the the current site will be loaded
            $recommendedPrice = $caravan->sites->first()->pivot->recommended_price;
            $price = $caravan->sites->first()->pivot->price;

            return ($price < $recommendedPrice) ? $price : $recommendedPrice;
        })->min();

        return is_null($price)
            ? sprintf('%s%s', $sitePresenter->currencySymbol(), 'TBA')
            : sprintf('%s%s', $sitePresenter->currencySymbol(), number_format($price));
    }

    public function mainImage(): ?Media
    {
        return $this->wrappedObject->getFirstMedia('mainImage');
    }

    public function mainImageUrl(): string
    {
        return $this->wrappedObject->getFirstMediaUrl('mainImage');
    }

    public function page(SitePresenter $site): ?BasePagePresenter
    {
        $page = $this->wrappedObject->sitePage($site->getWrappedObject());

        if (is_null($page)) {
            return null;
        }

        return (new BasePagePresenter())->setWrappedObject($page);
    }

    public function getBannerContentBoxCssClasses(): string
    {
        $primaryThemeColour = $this->getWrappedObject()->primary_theme_colour ?? 'endeavour';
        return $this->getWrappedObject()->getBackgroundColourClass($primaryThemeColour);
    }

    public function getBannerTitleCssClasses(): string
    {
        $secondaryThemeColour = $this->getWrappedObject()->secondary_theme_colour ?? 'white';
        return $this->getWrappedObject()->getTextColourClass($secondaryThemeColour);
    }

    public function getBannerTextCssClasses(): string
    {
        $secondaryThemeColour = $this->getWrappedObject()->secondary_theme_colour ?? 'white';
        return $this->getWrappedObject()->getTextColourClass($secondaryThemeColour);
    }

    public function getBannerButtonCssClasses(): string
    {
        $cssClasses = [];
        $secondaryThemeColour = $this->getWrappedObject()->secondary_theme_colour ?? 'white';
        $cssClasses[] = $this->getWrappedObject()->getTextColourClass($secondaryThemeColour);
        $cssClasses[] = $this->getWrappedObject()->getBorderColourClass($secondaryThemeColour);

        return implode(' ', $cssClasses);
    }
}
