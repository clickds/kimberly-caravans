<?php

namespace Tests\Feature\Admin\Sites;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $data = $this->validFields();

        $response = $this->submit($data);

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

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['country'],
            ['flag'],
            ['subdomain'],
            ['timezone'],
            ['show_opening_times_and_telephone_number'],
            ['display_exclusive_manufacturers_separately'],
            ['show_buy_tab_on_new_model_pages'],
            ['show_offers_tab_on_new_model_pages'],
            ['show_dealer_ranges'],
            ['show_live_chat'],
            ['show_social_icons'],
            ['show_accreditation_icons'],
            ['show_footer_content'],
        ];
    }

    /**
     * @dataProvider uniqueProvider
     */
    public function test_unique_validation(string $inputName): void
    {
        $site = $this->createSite();
        $data = $this->validFields([
            $inputName => $site->$inputName,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function uniqueProvider(): array
    {
        return [
            ['country'],
            ['subdomain'],
        ];
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'country' => 'some country',
            'flag' => 'england.svg',
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
            'phone_number' => '01234 567890',
            'timezone' => 'UTC',
        ];

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.sites.store');
    }
}
