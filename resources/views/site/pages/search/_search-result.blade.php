@switch($searchResult['type'])
    @case(\App\Services\Search\Page\DataProviders\CaravanStockItemDataProvider::TYPE)
    @case(\App\Services\Search\Page\DataProviders\MotorhomeStockItemDataProvider::TYPE)
        @include('site.pages.search.search-results.stock-item', ['searchResult' => $searchResult])
        @break
    @default
        @include('site.pages.search.search-results.default', ['searchResult' => $searchResult])
@endswitch