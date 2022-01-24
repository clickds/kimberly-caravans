<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Facades\BasePage as BasePageFacade;
use App\Services\Site\RedirectCalculators\CaravanRangePageRedirectCalculator;
use App\Services\Site\RedirectCalculators\ManufacturerCaravansPageRedirectCalculator;
use App\Services\Site\RedirectCalculators\ManufacturerMotorhomesPageRedirectCalculator;
use App\Services\Site\RedirectCalculators\MotorhomeRangePageRedirectCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __invoke(Request $request, string $page, string $childPage = '')
    {
        $site = resolve('currentSite');
        $page = $this->getPage($site, $page, $childPage);

        $redirect = $this->calculateRedirect($page);
        if (!is_null($redirect)) {
            return $redirect;
        }

        $pageFacade = BasePageFacade::for($page, $request);

        return view('site.pages.page')->with([
            'pageFacade' => $pageFacade,
        ]);
    }

    private function getPage(Site $site, string $pageSlug, string $childPageSlug = ''): Page
    {
        $query = $site->pages()->displayable()->with('parent');

        if ('' === $childPageSlug) {
            return $query->whereNull('parent_id')->where('slug', '=', $pageSlug)->firstOrFail();
        }

        return $query->whereHas('parent', function ($query) use ($pageSlug) {
            $query->where('slug', '=', $pageSlug);
        })->where('slug', '=', $childPageSlug)->firstOrFail();
    }


    public function homepage(Request $request): View
    {
        $site = resolve('currentSite');
        $page = $site->pages()->firstOrNew(
            [
                'template' => Page::TEMPLATE_HOMEPAGE,
            ],
            [
                'name' => $site->country,
            ]
        );
        $pageFacade = BasePageFacade::for($page, $request);

        return view('site.pages.page')->with([
            'pageFacade' => $pageFacade,
        ]);
    }

    private function calculateRedirect(Page $page): ?RedirectResponse
    {
        switch ($page->template) {
            case Page::TEMPLATE_MANUFACTURER_CARAVANS:
                $redirectCalculator = new ManufacturerCaravansPageRedirectCalculator($page);
                break;
            case Page::TEMPLATE_MANUFACTURER_MOTORHOMES:
                $redirectCalculator = new ManufacturerMotorhomesPageRedirectCalculator($page);
                break;
            case Page::TEMPLATE_CARAVAN_RANGE:
                $redirectCalculator = new CaravanRangePageRedirectCalculator($page);
                break;
            case Page::TEMPLATE_MOTORHOME_RANGE:
                $redirectCalculator = new MotorhomeRangePageRedirectCalculator($page);
                break;
            default:
                return null;
        }

        return $redirectCalculator->calculateRedirect();
    }
}
