<?php

namespace Tests\Feature\Admin\SiteSettings;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            ['value'],
        ];
    }

    public function test_name_must_be_a_valid_setting(): void
    {
        $data = $this->validData(['name' => 'An invalid setting name']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_be_unique(): void
    {
        $settingName = SiteSetting::SETTING_LATEST_OFFERS_ADDED_TIME_FRAME;

        factory(SiteSetting::class)->create(['name' => $settingName]);

        $data = $this->validData(['name' => $settingName]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successful(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.site-settings.index'));
        $this->assertDatabaseHas('site_settings', $data);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();

        $url = route('admin.site-settings.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->randomElement(SiteSetting::VALID_SETTINGS),
            'description' => $this->faker->paragraph(1),
            'value' => $this->faker->randomNumber(),
        ];

        return array_merge($defaults, $overrides);
    }
}
