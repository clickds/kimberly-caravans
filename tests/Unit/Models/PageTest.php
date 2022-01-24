<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use UnexpectedValueException;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_template_name_when_using_standard_template()
    {
        $templates = array_keys(Page::STANDARD_TEMPLATES);
        $key = $templates[0];
        $name = Page::STANDARD_TEMPLATES[$key];

        $page = new Page([
            'template' => $key,
        ]);

        $this->assertEquals($name, $page->templateName());
    }

    public function test_template_name_when_pageable_page()
    {
        $template = Page::TEMPLATE_MOTORHOME_SEARCH;
        $name = ucwords(str_replace("-", " ", $template));
        $page = new Page([
            'template' => $template,
        ]);

        $this->assertEquals($name, $page->templateName());
    }

    public function test_cannot_set_variety_to_value_not_in_varieties_array()
    {
        $page = new Page;

        $this->expectException(UnexpectedValueException::class);
        $page->variety = 'blah';
    }

    /**
     * @dataProvider holdersProvider
     */
    public function test_available_holders(string $template, array $holders): void
    {
        $page = new Page([
            'template' => $template,
        ]);

        $this->assertEquals($holders, $page->availableHolders());
    }

    public function holdersProvider(): array
    {
        return [
            [Page::TEMPLATE_MANUFACTURER_CARAVANS, ['Primary']],
            [Page::TEMPLATE_MANUFACTURER_MOTORHOMES, ['Primary']],
        ];
    }
}
