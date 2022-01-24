<?php

namespace Tests\Feature\Admin\Ctas;

use App\Models\Cta;
use App\Models\Page;
use App\Models\Site;
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
    public function test_required_validation(string $inputKey): void
    {
        $data = $this->validData([
            $inputKey => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputKey);
    }

    public function requiredProvider(): array
    {
        return [
            ['title'],
            ['excerpt_text'],
            ['site_id'],
            ['image'],
            ['type'],
        ];
    }

    public function test_image_is_an_image(): void
    {
        $data = $this->validData([
            'image' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('image');
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputKey): void
    {
        $data = $this->validData([
            $inputKey => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputKey);
    }

    public function test_page_id_must_belong_to_passed_site_id(): void
    {
        $site = factory(Site::class)->create();
        $page = factory(Page::class)->create();
        $data = $this->validData([
            'page_id' => $page->id,
            'site_id' => $site->id,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('page_id');
    }

    public function test_type_in_types_array(): void
    {
        $data = $this->validData([
            'type' => 'blah',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('type');
    }

    public function test_successful(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.ctas.index'));
        $ctaData = Arr::except($data, ['image']);
        $this->assertDatabaseHas('ctas', $ctaData);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'image',
            'model_type' => Cta::class,
            'file_name' => $data['image']->getClientOriginalName(),
        ]);
    }

    public function test_excerpt_text_is_not_required_when_type_is_event(): void
    {
        $this->fakeStorage();
        $data = $this->validData([
            'type' => Cta::TYPE_EVENT,
            'excerpt_text' => null,
        ]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.ctas.index'));
        $ctaData = Arr::except($data, ['image']);
        $this->assertDatabaseHas('ctas', $ctaData);
        $this->assertDatabaseHas('media', [
            'collection_name' => 'image',
            'model_type' => Cta::class,
            'file_name' => $data['image']->getClientOriginalName(),
        ]);
    }

    public function existsProvider(): array
    {
        return [
            ['site_id'],
            ['page_id'],
        ];
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'title' => $this->faker->company,
            'type' => Cta::TYPE_STANDARD,
            'excerpt_text' => $this->faker->text(),
            'image' => UploadedFile::fake()->image('test.jpg'),
            'position' => 0,
            'link_text' => 'abc',
        ];

        if (!array_key_exists('page_id', $overrides)) {
            $page = factory(Page::class)->create();
            $defaults['page_id'] = $page->id;
            $defaults['site_id'] = $page->site_id;
        }

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data = []): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.ctas.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
