<?php

namespace Tests\Feature\Admin\Sites;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Site;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $site = $this->createSite();
        $data = $this->validFields([
            'country' => $site->country,
            'subdomain' => $site->subdomain,
        ]);

        $response = $this->submit($site, $data);

        $response->assertRedirect(route('admin.sites.index'));
        $this->assertDatabaseHas('sites', $data);
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validFields([
            $inputName => null,
        ]);
        $site = $this->createSite();

        $response = $this->submit($site, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['country'],
            ['subdomain'],
            ['timezone'],
            ['display_exclusive_manufacturers_separately'],
            ['show_opening_times_and_telephone_number'],
            ['show_buy_tab_on_new_model_pages'],
            ['show_offers_tab_on_new_model_pages'],
            ['show_dealer_ranges'],
            ['show_live_chat'],
            ['show_social_icons'],
            ['show_accreditation_icons'],
            ['show_footer_content'],
        ];
    }

    public function test_country_is_unique()
    {
        $site = $this->createSite();
        $other_site = $this->createSite();
        $data = $this->validFields([
            'country' => $other_site->country,
        ]);

        $response = $this->submit($site, $data);

        $response->assertSessionHasErrors('country');
    }

    public function test_subdomain_is_unique()
    {
        $other_site = $this->createSite();
        $site = $this->createSite();
        $data = $this->validFields([
            'subdomain' => $other_site->subdomain,
        ]);

        $response = $this->submit($site, $data);

        $response->assertSessionHasErrors('subdomain');
    }

    private function submit(Site $site, $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($site);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'country' => 'some country',
            'subdomain' => 'uk',
            'has_stock' => false,
            'is_default' => false,
            'show_opening_times_and_telephone_number' => false,
            'display_exclusive_manufacturers_separately' => false,
            'show_buy_tab_on_new_model_pages' => false,
            'show_offers_tab_on_new_model_pages' => false,
            'show_dealer_ranges' => false,
            'show_live_chat' => false,
            'show_social_icons' => false,
            'show_accreditation_icons' => false,
            'show_footer_content' => false,
            'flag' => 'england.svg',
            'phone_number' => '01234 567890',
            'timezone' => 'UTC',
        ];

        return array_merge($defaults, $overrides);
    }

    private function url($site)
    {
        return route('admin.sites.update', $site);
    }
}
