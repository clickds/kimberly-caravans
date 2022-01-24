<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Site;
use App\Models\SiteSetting;

abstract class AbstractStockItemQueryBuilder extends AbstractQueryBuilder
{
    public const FILTER_STATUS = 'status';
    public const FILTER_MANUFACTURER = 'manufacturer';
    public const FILTER_LAYOUT = 'layout';
    public const FILTER_RANGE = 'range';

    public const STATUS_USED_STOCK = 'Used stock';
    public const STATUS_NEW_STOCK = 'New stock';
    public const STATUS_NEW_EXCLUSIVE_MODELS = 'New exclusive models';
    public const STATUS_NEW_ARRIVALS = 'New arrivals';
    public const STATUS_LATEST_OFFERS_ADDED = 'Latest offers added';

    public const STATUSES = [
        self::STATUS_USED_STOCK,
        self::STATUS_NEW_STOCK,
        self::STATUS_NEW_EXCLUSIVE_MODELS,
        self::STATUS_NEW_ARRIVALS,
        self::STATUS_LATEST_OFFERS_ADDED,
    ];

    abstract protected function applyRangeQuery(array $filters, Builder $query): void;

    protected function applyStatusQuery(array $filters, Builder $query): void
    {
        $statuses = $filters[self::FILTER_STATUS][self::FILTER_IN_KEY] ?? null;

        if (null === $statuses) {
            return;
        }

        $query->where(function (Builder $query) use ($statuses) {
            foreach ($statuses as $status) {
                switch ($status) {
                    case self::STATUS_USED_STOCK:
                        $query->orWhere(fn (Builder $query) => $query->used());
                        break;
                    case self::STATUS_NEW_STOCK:
                        $query->orWhere(fn (Builder $query) => $query->new());
                        break;
                    case self::STATUS_NEW_EXCLUSIVE_MODELS:
                        $query->orWhere(fn (Builder $query) => $query->new()->exclusive());
                        break;
                    case self::STATUS_NEW_ARRIVALS:
                        $query->orWhere(fn (Builder $query) => $query->newArrival()->orderByDeliveryDate('desc'));
                        break;
                    case self::STATUS_LATEST_OFFERS_ADDED:
                        $latestOffersAddedTimeFrame = null;
                        $latestOffersAddedSetting = SiteSetting::latestOffersAddedTimeFrame()->first();

                        if ($latestOffersAddedSetting) {
                            $latestOffersAddedTimeFrame = (int) $latestOffersAddedSetting->value;
                        }

                        $query->orWhere(function (Builder $query) use ($latestOffersAddedTimeFrame) {
                            $query->reducedPriceRecentlyStarted($latestOffersAddedTimeFrame ?? 14)
                                ->orWhere(function (Builder $query) use ($latestOffersAddedTimeFrame) {
                                    $query->hasRecentlyCreatedOffer($latestOffersAddedTimeFrame ?? 14);
                                });
                        });
                        break;
                }
            }
        });
    }

    protected function getSite(): Site
    {
        return resolve('currentSite');
    }

    protected function applyOrder(?string $order, Builder $query): void
    {
        switch ($order) {
            case 'price_desc':
                $query->orderBy('recommended_price', 'desc');
                break;
            default:
                $query->orderBy('recommended_price', 'asc');
                break;
        }
    }

    protected function applyManufacturerQuery(array $filters, Builder $query): void
    {
        $manufacturers = $filters[self::FILTER_MANUFACTURER][self::FILTER_IN_KEY] ?? null;

        if (null === $manufacturers) {
            return;
        }

        $query->where(function ($query) use ($manufacturers) {
            $query->whereHas('manufacturer', function ($manufacturerQuery) use ($manufacturers) {
                $manufacturerQuery->whereIn('name', $manufacturers);
            })->orwhereHas('manufacturer.linkedToManufacturers', function ($manufacturerQuery) use ($manufacturers) {
                $manufacturerQuery->whereIn('name', $manufacturers);
            })->orWhereHas('manufacturer.linkedByManufacturers', function ($manufacturerQuery) use ($manufacturers) {
                $manufacturerQuery->whereIn('name', $manufacturers);
            });
        });
    }

    protected function applyLayoutQuery(array $filters, Builder $query): void
    {
        $layouts = $filters[self::FILTER_LAYOUT][self::FILTER_IN_KEY] ?? null;

        if (null === $layouts) {
            return;
        }

        $query->whereHas('layout', function ($layoutQuery) use ($layouts) {
            $layoutQuery->whereIn('name', $layouts);
        });
    }
}
