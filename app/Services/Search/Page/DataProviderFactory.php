<?php

namespace App\Services\Search\Page;

use App\Models\Article;
use App\Models\CaravanRange;
use App\Models\CaravanStockItem;
use App\Models\Dealer;
use App\Models\Event;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\SpecialOffer;
use App\Models\Vacancy;
use App\Models\Video;
use App\Services\Search\Page\DataProviders\ArticleDataProvider;
use App\Services\Search\Page\DataProviders\CaravanRangeDataProvider;
use App\Services\Search\Page\DataProviders\CaravanStockItemDataProvider;
use App\Services\Search\Page\DataProviders\DealerDataProvider;
use App\Services\Search\Page\DataProviders\EventDataProvider;
use App\Services\Search\Page\DataProviders\ManufacturerDataProvider;
use App\Services\Search\Page\DataProviders\MotorhomeRangeDataProvider;
use App\Services\Search\Page\DataProviders\MotorhomeStockItemDataProvider;
use App\Services\Search\Page\DataProviders\PageDataProvider;
use App\Services\Search\Page\DataProviders\SiteSearchDataProvider;
use App\Services\Search\Page\DataProviders\SpecialOfferDataProvider;
use App\Services\Search\Page\DataProviders\VacancyDataProvider;
use App\Services\Search\Page\DataProviders\VideoDataProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

final class DataProviderFactory
{
    public static function getDataProvider(Page $page): SiteSearchDataProvider
    {
        if (is_null($page->pageable) || !is_a($page->pageable, Model::class)) {
            return new PageDataProvider($page);
        }

        switch (get_class($page->pageable)) {
            case Article::class:
                return new ArticleDataProvider($page);
            case CaravanRange::class:
                return new CaravanRangeDataProvider($page);
            case CaravanStockItem::class:
                return new CaravanStockItemDataProvider($page);
            case Dealer::class:
                return new DealerDataProvider($page);
            case Event::class:
                return new EventDataProvider($page);
            case Manufacturer::class:
                return new ManufacturerDataProvider($page);
            case MotorhomeRange::class:
                return new MotorhomeRangeDataProvider($page);
            case MotorhomeStockItem::class:
                return new MotorhomeStockItemDataProvider($page);
            case SpecialOffer::class:
                return new SpecialOfferDataProvider($page);
            case Vacancy::class:
                return new VacancyDataProvider($page);
            case Video::class:
                return new VideoDataProvider($page);
            default:
                Log::info(sprintf('Unrecognised pageable type: %s', get_class($page->pageable)));
                return new PageDataProvider($page);
        }
    }
}
