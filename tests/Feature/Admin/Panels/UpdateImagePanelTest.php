<?php

namespace Tests\Feature\Admin\Panels;

use App\Models\Panel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateImagePanelTest extends BaseUpdatePanelTestCase
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
                ['image'],
                ['image_alt_text'],
            ]
        );
    }

    public function test_image_not_required_if_the_panel_has_one()
    {
        factory(Media::class)->create([
            'model_id' => $this->panel->id,
            'model_type' => Panel::class,
            'collection_name' => 'image',
        ]);

        $data = $this->valid_fields(['image' => '']);
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());

        $this->assertDatabaseHas('panels', Arr::except($data, ['image']));
    }

    public function test_requires_valid_image()
    {
        $data = $this->valid_fields([
            'image' => UploadedFile::fake()->create('avatar.pdf', 10000),
        ]);

        $response = $this->submit($data);
        $response->assertSessionHasErrors('image');
    }

    public function test_successful(): void
    {
        $this->fakeStorage();

        $data = $this->valid_fields();
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());

        $this->assertDatabaseHas('panels', Arr::except($data, ['image']));
        $this->assertFileExists($this->panel->getFirstMedia('image')->getPath());
    }

    protected function valid_fields(array $overrides = []): array
    {
        return array_merge(
            $this->default_valid_fields(),
            [
                'type' => Panel::TYPE_IMAGE,
                'image' => UploadedFile::fake()->image('avatar.jpg'),
                'image_alt_text' => 'abc',
            ],
            $overrides
        );
    }
}

