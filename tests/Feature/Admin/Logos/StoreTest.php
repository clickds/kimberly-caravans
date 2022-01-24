<?php

namespace Tests\Feature\Admin\Logos;

use App\Models\Logo;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

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
            ['display_location'],
            ['image'],
        ];
    }

    /**
     * @dataProvider requiredIfOtherBlankProvider
     */
    public function test_required_if_other_blank(string $inputName, string $otherInputName): void
    {
        $data = $this->validData([
            $inputName => null,
            $otherInputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
        $response->assertSessionHasErrors($otherInputName);
    }

    public function requiredIfOtherBlankProvider(): array
    {
        return [
            ['external_url', 'page_id'],
        ];
    }

    public function test_external_url_is_a_url(): void
    {
        $data = $this->validData([
            'external_url' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('external_url');
    }

    public function test_linked_page_must_exist(): void
    {
        $data = $this->validData([
            'page_id' => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('page_id');
    }

    public function test_successful(): void
    {
        $this->fakeStorage();

        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.logos.index'));

        $this->assertDatabaseHas('logos', Arr::except($data, ['image']));

        /** @var Logo */
        $logo = Logo::firstOrFail();
        $this->assertTrue($logo->hasMedia('image'));
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.logos.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'external_url' => 'https://www.google.co.uk',
            'display_location' => $this->faker->randomElement(Logo::VALID_DISPLAY_LOCATIONS),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        if (!array_key_exists('page_id', $overrides)) {
            $page = factory(Page::class)->create();
            $defaults['page_id'] = $page->id;
        }

        return array_merge($defaults, $overrides);
    }
}
