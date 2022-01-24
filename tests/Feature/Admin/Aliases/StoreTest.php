<?php

namespace Tests\Feature\Admin\Aliases;

use App\Models\Alias;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            ['capture_path'],
            ['site_id'],
            ['page_id'],
        ];
    }

    /**
     * @dataProvider existsProvider
     */
    public function test_exists_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => 0,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function existsProvider(): array
    {
        return [
            ['site_id'],
            ['page_id'],
        ];
    }

    public function test_capture_path_must_start_with_a_slash(): void
    {
        $data = $this->validData([
            'capture_path' => 'no-slash',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('capture_path');
    }

    public function test_capture_path_is_unique(): void
    {
        $otherAlias = factory(Alias::class)->create();
        $data = $this->validData([
            'capture_path' => $otherAlias->capture_path,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('capture_path');
    }

    public function test_creates_alias(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.aliases.index'));
        $this->assertDatabaseHas('aliases', $data);
    }

    private function validData(array $overrides = []): array
    {
        $siteId = factory(Site::class)->create()->id;
        $pageId = factory(Page::class)->create([
            'site_id' => $siteId,
        ])->id;
        $defaults = [
            'site_id' => $siteId,
            'page_id' => $pageId,
            'capture_path' => '/' . $this->faker->unique()->slug,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.aliases.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
