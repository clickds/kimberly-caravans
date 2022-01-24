<?php

namespace Tests\Unit\Presenters;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\SitePresenter;
use App\Models\Site;

class SitePresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_flag_url(): void
    {
        $site = new Site([
            'flag' => 'england.svg',
        ]);
        $presenter = (new SitePresenter())->setWrappedObject($site);

        $this->assertEquals('/images/flags/england.svg', $presenter->flagUrl());
    }

    public function test_url(): void
    {
        $site = new Site([
            'subdomain' => 'www',
        ]);
        $presenter = (new SitePresenter())->setWrappedObject($site);

        $this->assertEquals('www.marquisleisure.co.uk', $presenter->url());
    }

    /**
     * @dataProvider currencyProvider
     */
    public function test_currency_symbol(string $flag, ?bool $returnHtmlEntity, string $symbol): void
    {
        $site = new Site([
            'flag' => $flag,
        ]);
        $presenter = (new SitePresenter())->setWrappedObject($site);

        if (is_null($returnHtmlEntity)) {
            $result = $presenter->currencySymbol();
        } else {
            $result = $presenter->currencySymbol($returnHtmlEntity);
        }

        $this->assertEquals($symbol, $result);
    }

    public function currencyProvider(): array
    {
        return [
            ['ireland.svg', null, '&euro;'],
            ['new-zealand.svg', null, '&dollar;'],
            ['england.svg', null, '&pound;'],
            ['scotland.svg', null, '&pound;'],
            ['wales.svg', null, '&pound;'],
            ['ireland.svg', true, '&euro;'],
            ['new-zealand.svg', true, '&dollar;'],
            ['england.svg', true, '&pound;'],
            ['scotland.svg', true, '&pound;'],
            ['wales.svg', true, '&pound;'],
            ['ireland.svg', false, '€'],
            ['new-zealand.svg', false, '$'],
            ['england.svg', false, '£'],
            ['scotland.svg', false, '£'],
            ['wales.svg', false, '£'],
        ];
    }
}
