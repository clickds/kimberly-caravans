<?php

namespace Tests\Feature\Admin\Dealers;

use App\Models\Dealer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Site;
use Illuminate\Foundation\Testing\WithFaker;

class StoreTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_successful()
    {
        $dealerData = $this->getValidDealerData();
        $locationData = $this->getValidLocationData();
        $data = array_merge($dealerData, $locationData);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.dealers.index'));

        $this->assertDatabaseHas('dealers', $dealerData);
        $this->assertDatabaseHas('dealer_locations', $locationData);

        $createdDealer = Dealer::orderBy('created_at', 'desc')->firstOrFail();

        $this->assertEquals(
            $createdDealer->id,
            $createdDealer->pages()->first()->pageable_id
        );

        $this->assertEquals(
            $createdDealer->name,
            $createdDealer->pages()->first()->name
        );
    }

    /**
     * @dataProvider requiredFieldNamesProvider
     */
    public function test_required_validation(string $requiredFieldName)
    {
        $dealerData = $this->getValidDealerData();
        $locationData = $this->getValidLocationData();
        $data = array_merge($dealerData, $locationData);

        $data[$requiredFieldName] = null;

        $response = $this->submit($data);

        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function requiredFieldNamesProvider(): array
    {
        return [
            ['name'],
            ['is_branch'],
            ['is_servicing_center'],
            ['can_view_motorhomes'],
            ['can_view_caravans'],
            ['position'],
            ['line_1'],
            ['google_maps_url'],
            ['latitude'],
            ['longitude'],
            ['site_id'],
        ];
    }

    private function submit($data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function getValidDealerData(array $overrides = []): array
    {
        $site = factory(Site::class)->create();

        $defaults = [
            'name' => $this->faker->company,
            'is_branch' => $this->faker->boolean(),
            'is_servicing_center' => $this->faker->boolean(),
            'can_view_motorhomes' => $this->faker->boolean(),
            'can_view_caravans' => $this->faker->boolean(),
            'website_url' => $this->faker->url,
            'website_link_text' => $this->faker->company . 'website',
            'position' => $this->faker->randomDigit,
            'site_id' => $site->id,
        ];

        return array_merge($defaults, $overrides);
    }

    private function getValidLocationData(array $overrides = []): array
    {
        $defaults = [
            'line_1' => $this->faker->streetName,
            'postcode' => $this->faker->postcode,
            'google_maps_url' => $this->faker->url,
            'latitude' => 45.428459,
            'longitude' => -75.710358,
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(): string
    {
        return route('admin.dealers.store');
    }
}
