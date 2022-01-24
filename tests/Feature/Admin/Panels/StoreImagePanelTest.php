<?php

namespace Tests\Feature\Admin\Panels;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use App\Models\Panel;

class StoreImagePanelTest extends BaseStorePanelTestCase
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

    public function test_it_image_must_be_an_image()
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
        $data = Arr::except($data, ['image']);
        $this->assertDatabaseHas('panels', $data);
        $panel = $this->area->panels()->first();
        $this->assertFileExists($panel->getFirstMedia('image')->getPath());
    }

    protected function valid_fields($overrides = []): array
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
