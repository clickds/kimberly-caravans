<?php

namespace Tests\Feature\Api\Admin\WysiwygFileUploads;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
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

    public function test_successful(): void
    {
        $this->fakeStorage();
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertStatus(201);
        $file = $data['upload'];
        $this->assertDatabaseHas('wysiwyg_uploads', [
            'name' => $file->getClientOriginalName(),
        ]);
        $this->assertDatabaseHas('media', [
            'file_name' => $file->getClientOriginalName(),
        ]);
    }


    public function requiredProvider(): array
    {
        return [
            ['upload'],
        ];
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('api.admin.wysiwyg-file-uploads.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'upload' => UploadedFile::fake()->image('image.jpg'),
        ];

        return array_merge($defaults, $overrides);
    }
}
