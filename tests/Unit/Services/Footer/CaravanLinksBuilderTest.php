<?php

namespace Tests\Unit\Services\Footer;

use App\Models\FooterLink;
use App\Models\Caravan;
use App\Models\CaravanStockItem;
use App\Models\Page;
use App\Models\Site;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Footer\CaravanLinksBuilder;

class CaravanLinksBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider axleProvider
     */
    public function test_contains_link_for_each_axle_that_exists_on_a_stock_item(string $axle): void
    {
        $stockItem = factory(CaravanStockItem::class)->create([
            'axles' => $axle,
        ]);
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create([
            'template' => Page::TEMPLATE_CARAVAN_SEARCH,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new CaravanLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($axle . ' Axle', $presenter->link(['axles' => $axle]));
        $this->assertTrue($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    /**
     * @dataProvider axleProvider
     */
    public function test_does_not_contain_link_for_axle_that_do_not_exist_on_a_stock_item(string $axle): void
    {
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create([
            'template' => Page::TEMPLATE_CARAVAN_SEARCH,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new CaravanLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($axle, $presenter->link(['axle' => $axle]));
        $this->assertFalse($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    public function axleProvider(): array
    {
        $data = [];

        foreach (Caravan::AXLES as $axle) {
            $data[] = [$axle];
        }

        return $data;
    }

    /**
     * @dataProvider newPageLinksProvider
     */
    public function test_new_page_links(string $name): void
    {
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create([
            'template' => Page::TEMPLATE_NEW_CARAVANS,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new CaravanLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($name, $presenter->link());
        $this->assertTrue($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    public function newPageLinksProvider(): array
    {
        return [
            ['New Caravans'],
        ];
    }
}
