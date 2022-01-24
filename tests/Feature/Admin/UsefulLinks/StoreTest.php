<?php

namespace Tests\Feature\Admin\UsefulLinks;

use App\Models\UsefulLinkCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['image'],
            ['url'],
            ['useful_link_category_id'],
        ];
    }

    public function test_exists_validation(): void
    {
        $data = $this->validData([
            'useful_link_category_id' => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('useful_link_category_id');
    }

    public function test_updates_useful_link(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.useful-links.index'));
        $usefulLinkData = Arr::except($data, 'image');
        $this->assertDatabaseHas('useful_links', $usefulLinkData);
        $image = Arr::get($data, 'image');
        $this->assertDatabaseHas('media', [
            'file_name' => $image->getClientOriginalName(),
        ]);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'url' => $this->faker->url,
            'image' => UploadedFile::fake()->image('test.jpg'),
            'position' => 0,
        ];

        if (!array_key_exists('useful_link_category_id', $overrides)) {
            $defaults['useful_link_category_id'] = factory(UsefulLinkCategory::class)->create()->id;
        }

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.useful-links.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
