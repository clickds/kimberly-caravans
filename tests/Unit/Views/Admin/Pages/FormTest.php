<?php

namespace Tests\Unit\Views\Admin\Pages;

use Tests\TestCase;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use App\Models\Page;

class FormTest extends TestCase
{
    public function test_on_standard_page_see_select_for_templates()
    {
        $page = new Page;
        $view = $this->buildView($page);

        $content = $view->render();

        $this->assertStringContainsString('select name="template"', $content);
    }

    public function test_on_pageable_page_template_is_a_hidden_input_field()
    {
        $page = new Page;
        $page->pageable = "Some Object";
        $view = $this->buildView($page);

        $content = $view->render();

        $this->assertStringContainsString('input type="hidden" name="template"', $content);
    }

    private function buildView(Page $page)
    {
        $data = $this->defaultData();
        $data['page'] = $page;

        return View::make('admin.pages._form', $data);
    }

    private function defaultData()
    {
        return [
            'errors' => new ViewErrorBag(),
            'url' => 'https://www.google.co.uk',
            'pages' => [],
            'sites' => collect([]),
            'templates' => Page::STANDARD_TEMPLATES,
            'varieties' => [],
            'videoBanners' => [],
            'imageBanners' => [],
        ];
    }
}
