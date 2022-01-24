<?php

namespace App\Facades;

use Algolia\AlgoliaSearch\SearchIndex;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Services\Search\Page\DataProviders\SiteSearchDataProvider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class SearchPage extends BasePage
{
    private string $searchTerm;
    private LengthAwarePaginator $searchResults;
    private int $perPage;
    private int $pageNumber;
    private array $categories;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->searchTerm = $request->get('query', '');
        $this->perPage = $request->get('per_page', 12);
        $this->pageNumber = $request->get('page', 1);
        $this->categories = $request->get('categories', []);

        $this->initialiseSearchResults();
    }

    public function getCategories(): array
    {
        return SiteSearchDataProvider::VALID_TYPES;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    public function hasSearchResults(): bool
    {
        return 0 < count($this->searchResults->items()) && isset($this->searchResults->items()['hits']);
    }

    public function getSearchResults(): array
    {
        return $this->searchResults->items();
    }

    public function getSearchResultsPaginator(): LengthAwarePaginator
    {
        return $this->searchResults;
    }

    private function initialiseSearchResults(): void
    {
        if (strlen($this->searchTerm) <= 1) {
            $this->searchResults = $this->createEmptySearchResultsCollection();
        } else {
            $site = resolve('currentSite');

            $rawPaginator = Page::search(
                $this->searchTerm,
                function (SearchIndex $algolia, string $query, array $options) {
                    if (!empty($this->categories)) {
                        $options = array_merge($options, $this->buildFiltersArray());
                    }
                    return $algolia->search($query, $options);
                }
            )
            ->where('siteId', $site->id)
            ->paginateRaw($this->perPage);

            $this->searchResults = new Paginator(
                collect($rawPaginator->items()['hits']),
                $rawPaginator->total(),
                $rawPaginator->perPage(),
                $rawPaginator->currentPage(),
                [
                    'path' => url()->current(),
                ]
            );
        }
    }

    private function createEmptySearchResultsCollection(): LengthAwarePaginator
    {
        return new Paginator(collect(), 0, $this->perPage);
    }

    private function buildFiltersArray(): array
    {
        $formattedCategories = array_map(function ($category) {
            return sprintf('type:"%s"', $category);
        }, $this->categories);

        return ['filters' => sprintf('(%s)', implode(' OR ', $formattedCategories))];
    }
}
