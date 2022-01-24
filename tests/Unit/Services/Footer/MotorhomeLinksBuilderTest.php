<?php

namespace Tests\Unit\Services\Footer;

use App\Models\FooterLink;
use App\Models\Motorhome;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\Site;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Footer\MotorhomeLinksBuilder;

class MotorhomeLinksBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider conversionProvider
     */
    public function test_contains_link_for_each_conversion_that_exists_on_a_stock_item(string $conversion): void
    {
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'conversion' => $conversion,
        ]);
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create([
            'template' => Page::TEMPLATE_MOTORHOME_SEARCH,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new MotorhomeLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($conversion, $presenter->link(['conversion' => $conversion]));
        $this->assertTrue($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    /**
     * @dataProvider conversionProvider
     */
    public function test_does_not_contain_link_for_conversion_that_do_not_exist_on_a_stock_item(string $conversion): void
    {
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create([
            'template' => Page::TEMPLATE_MOTORHOME_SEARCH,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new MotorhomeLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($conversion, $presenter->link(['conversion' => $conversion]));
        $this->assertFalse($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    public function conversionProvider(): array
    {
        $data = [];

        foreach (Motorhome::CONVERSIONS as $conversion) {
            $data[] = [$conversion];
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
            'template' => Page::TEMPLATE_NEW_MOTORHOMES,
            'site_id' => $site->id,
        ]);
        $presenter = (new BasePagePresenter())->setWrappedObject($page);
        $builder = new MotorhomeLinksBuilder($site);

        $links = $builder->call();

        $link = new FooterLink($name, $presenter->link());
        $this->assertTrue($links->contains(function ($footerLink) use ($link) {
            return $link->getName() == $footerLink->getName() && $link->getUrl() == $footerLink->getUrl();
        }));
    }

    public function newPageLinksProvider(): array
    {
        return [
            ['New Motorhomes'],
        ];
    }
}
