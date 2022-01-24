<?php

namespace Tests\Feature\Admin\Dealers;

use App\Models\DealerLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Dealer;
use App\Models\Site;
use Illuminate\Foundation\Testing\WithFaker;

class UpdateTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_successful()
    {
        $dealer = $this->createDealer();

        $dealerData = $this->getValidDealerData();
        $locationData = $this->getValidLocationData();
        $data = array_merge($dealerData, $locationData);

        $response = $this->submit($dealer, $data);

        $response->assertRedirect(route('admin.dealers.index'));

        $this->assertDatabaseHas('dealers', $dealerData);

        $this->assertDatabaseHas('dealer_locations', $locationData);

        $updatedDealer = Dealer::orderBy('updated_at', 'desc')->firstOrFail();

        $this->assertEquals(
            $updatedDealer->id,
            $updatedDealer->pages()->first()->pageable_id
        );

        $this->assertEquals(
            $updatedDealer->name,
            $updatedDealer->pages()->first()->name
        );
    }

    /**
     * @dataProvider requiredFieldNamesProvider
     */
    public function test_required_validation(string $requiredFieldName)
    {
        $dealer = $this->createDealer();

        $dealerData = $this->getValidDealerData();
        $locationData = $this->getValidLocationData();
        $data = array_merge($dealerData, $locationData);

        $data[$requiredFieldName] = null;

        $response = $this->submit($dealer, $data);

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

    private function submit(Dealer $dealer, $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($dealer);

        return $this->actingAs($admin)->put($url, $data);
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
            'position' => $this->faker->randomDigit,
            'website_url' => $this->faker->url,
            'website_link_text' => $this->faker->company . 'website',
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

    private function url(Dealer $dealer): string
    {
        return route('admin.dealers.update', $dealer);
    }

    private function createDealer(): Dealer
    {
        $dealer = factory(Dealer::class)->create();

        $dealer->locations()->save(factory(DealerLocation::class)->make());

        return $dealer;
    }
}
