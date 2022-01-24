<?php

namespace Tests\Feature\Admin\Panels;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Panel;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

abstract class BaseUpdatePanelTestCase extends TestCase
{
    use RefreshDatabase;

    abstract public function test_successful(): void;
    abstract public function test_required_fields(string $requiredFieldName): void;
    abstract public function required_fields_provider(): array;
    abstract protected function valid_fields(array $overrides = []): array;

    public function test_requires_valid_heading_type()
    {
        $data = $this->valid_fields(['heading_type' => 'invalid heading type']);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('heading_type');
    }

    public function test_requires_valid_type()
    {
        $data = $this->valid_fields(['type' => 'invalid type']);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('type');
    }

    public function test_requires_valid_vertical_positioning()
    {
        $data = $this->valid_fields(['vertical_positioning' => 'invalid vertical positioning']);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('vertical_positioning');
    }

    public function test_requires_valid_text_alignment()
    {
        $data = $this->valid_fields(['text_alignment' => 'invalid alignment']);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('text_alignment');
    }

    public function test_redirects_to_redirect_url()
    {
        $redirect_url = route('site', [
            'page' => $this->panel->area->page->slug,
        ]);
        $data = $this->valid_fields(['redirect_url' => $redirect_url]);
        $response = $this->submit($data);
        $response->assertRedirect($redirect_url);
    }

    protected function default_required_fields(): array
    {
        return [
            ['heading_type'],
            ['live'],
            ['name'],
            ['text_alignment'],
            ['type'],
            ['vertical_positioning'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->area = factory(Area::class)->create();
        $this->panel = factory(Panel::class)->create();
    }

    protected function submit(array $data): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url();
        $data['area_id'] = $this->area->id;

        return $this->actingAs($admin)->put($url, $data);
    }

    protected function redirect_url(): string
    {
        return route('admin.areas.panels.index', $this->area);
    }

    protected function default_valid_fields(): array
    {
        return [
            'name' => 'some name',
            'type' => Panel::TYPE_STANDARD,
            'vertical_positioning' => array_keys(Panel::VERTICAL_POSITIONS)[0],
            'live' => true,
            'heading_type' => Panel::HEADING_TYPES[0],
            'text_alignment' => array_keys(Panel::TEXT_ALIGNMENTS)[0],
        ];
    }

    private function url(): string
    {
        return route('admin.areas.panels.update', ['area' => $this->area, 'panel' => $this->panel]);
    }
}
