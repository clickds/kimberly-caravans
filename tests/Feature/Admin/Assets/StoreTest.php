<?php

namespace Tests\Feature\Admin\Assets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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

    public function test_file_validation(): void
    {
        $data = $this->validData([
            'file' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('file');
    }

    public function success(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.assets.index'));
        $response->assertDatabaseHas('wysiwyg_uploads', [
            'name' => $data['name'],
        ]);
        $response->assertDatabaseHas('media', [
            'filename' => 'test.jpg',
        ]);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['file'],
        ];
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'file' => UploadedFile::fake()->image('test.jpg'),
            'name' => $this->faker->name,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.assets.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
