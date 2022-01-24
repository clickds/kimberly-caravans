<?php

namespace Tests\Feature\Admin\Panels;

use App\Models\Panel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateFeaturedImagePanelTest extends BaseUpdatePanelTestCase
{
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

    public function test_image_not_required_if_the_panel_has_one()
    {
        factory(Media::class)->create([
            'model_id' => $this->panel->id,
            'model_type' => Panel::class,
            'collection_name' => 'featuredImage',
        ]);

        $data = $this->valid_fields([
            'featured_image' => '',
        ]);

        $response = $this->submit($data);

        $response->assertRedirect($this->redirect_url());
        $data = Arr::except($data, ['featured_image']);
        $this->assertDatabaseHas('panels', $data);
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

    public function test_requires_valid_overlay_position()
    {
        $data = $this->valid_fields([
            'overlay_position' => 'abc',
        ]);

        $response = $this->submit($data);
        $response->assertSessionHasErrors('overlay_position');
    }

    public function test_successful(): void
    {
        $this->fakeStorage();

        $data = $this->valid_fields();
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());

        $this->assertDatabaseHas('panels', Arr::except($data, ['featured_image']));
        $this->assertFileExists($this->panel->getFirstMedia('featuredImage')->getPath());
    }

    protected function valid_fields(array $overrides = []): array
    {
        return array_merge(
            $this->default_valid_fields(),
            [
                'type' => Panel::TYPE_FEATURED_IMAGE,
                'featured_image' => UploadedFile::fake()->image('avatar.jpg', 1980, 700),
                'featured_image_content' => 'abc',
                'featured_image_alt_text' => 'def',
                'overlay_position' => PANEL::OVERLAY_LEFT,
            ],
            $overrides
        );
    }
}
