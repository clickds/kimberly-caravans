<?php

namespace App\Presenters\PanelType;

use App\Models\Page;
use App\Models\SpecialOffer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SpecialOfferSliderPresenter extends BasePanelPresenter
{
    private Collection $specialOffers;

    public function getSpecialOffers(): Collection
    {
        if (!isset($this->specialOffers)) {
            $this->specialOffers = $this->fetchSpecialOffers();
        }
        return $this->specialOffers;
    }

    private function fetchSpecialOffers(): Collection
    {
        $site = $this->getSite();
        $panel = $this->getPanel();

        if ($panel->relationLoaded('specialOffers')) {
            return $panel->specialOffers;
        }
        return $panel->specialOffers()->orderedByPosition()->displayable()
            ->whereHas('pages', function ($query) use ($site) {
                $query->where('site_id', $site->id)->displayable();
            })
            ->with([
                'pages' => function ($query) use ($site) {
                    $query->with('parent')->displayable()->where('site_id', $site->id);
                }
            ])->get();
    }
}
