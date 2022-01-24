<?php

namespace Tests\Feature\Admin\Panels;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use App\Models\Panel;

class StoreFeaturedImagePanelTest extends BaseStorePanelTestCase
{
    public function test_successful(): void
    {
        $this->fakeStorage();
        $data = $this->valid_fields();
        $response = $this->submit($data);

        $response->assertRedirect($this->redirect_url());
        $data = Arr::except($data, ['featured_image']);
        $this->assertDatabaseHas('panels', $data);
        $panel = $this->area->panels()->first();
        $this->assertFileExists($panel->getFirstMedia('featuredImage')->getPath());
    }

    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_fields(string $requiredFieldName): void
    {
        $data = $this->valid_fields([$requiredFieldName => null]);
        $response = $this->submit($data);
        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return array_merge(
            $this->default_required_fields(),
            [
                ['featured_image'],
                ['featured_image_content'],
                ['featured_image_alt_text'],
                ['overlay_position'],
            ]
        );
    }

    public function test_featured_image_must_be_an_image()
    {
        $data = $this->valid_fields([
            'featured_image' => UploadedFile::fake()->create('avatar.pdf', 10000),
        ]);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('featured_image');
    }

    public function test_featured_image_must_have_a_min_width_of_1980(): void
    {
        $data = $this->valid_fields([
            'featured_image' => UploadedFile::fake()->image('avatar.jpg', 200),
        ]);

        $response = $this->submit($data);
        $response->assertSessionHasErrors('featured_image');
    }

    public function test_it_overlay_position_is_in_overlay_positions_constant()
    {
        $data = $this->valid_fields([
            'overlay_position' => 'abc',
        ]);

        $response = $this->submit($data);
        $response->assertSessionHasErrors('overlay_position');
    }

    protected function valid_fields($overrides = []): array
    {
        return array_merge(
            $this->default_valid_fields(),
            [
                'type' => Panel::TYPE_FEATURED_IMAGE,
                'featured_image_content' => 'abc',
                'featured_image_alt_text' => 'def',
                'overlay_position' => PANEL::OVERLAY_LEFT,
                'featured_image' => UploadedFile::fake()->image('avatar.jpg', 1980, 700),
            ],
            $overrides
        );
    }
}
