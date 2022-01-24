<?php

namespace Tests\Feature\Admin\SpecialOffers;

use App\Models\Caravan;
use App\Models\CaravanStockItem;
use App\Models\Interfaces\StockItem;
use App\Models\Motorhome;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\SpecialOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $this->fakeStorage();
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'square_image' => UploadedFile::fake()->image('avatar.jpg', 960, 960),
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $data = Arr::except($data, ['site_ids', 'square_image']);
        $this->assertDatabaseHas('special_offers', $data);
        $this->assertFileExists($specialOffer->getFirstMedia('squareImage')->getPath());
        foreach (Arr::only($data, 'site_ids') as $siteId) {
            $this->assertDatabaseHas('site_special_offer', [
                'site_id' => $siteId,
                'special_offer_id' => $specialOffer->id,
            ]);
            $this->assertDatabaseHas('pages', [
                "site_id" => $specialOffer->site_id,
                "pageable_type" => SpecialOffer::class,
                "pageable_id" => $specialOffer->id,
                "template" => Page::TEMPLATE_SPECIAL_OFFER_SHOW,
            ]);
        }
    }

    public function test_links_caravans_and_their_stock_item(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $caravan = factory(Caravan::class)->create();
        $stockItem = factory(CaravanStockItem::class)->create([
            'caravan_id' => $caravan->id,
        ]);
        $data = $this->validData($specialOffer, [
            'caravan_ids' => [$caravan->id],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $specialOffer = SpecialOffer::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('caravan_special_offer', [
            'caravan_id' => $caravan->id,
            'special_offer_id' => $specialOffer->id,
        ]);
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
            'caravan_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_motorhomes_and_their_stock_item(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $motorhome = factory(Motorhome::class)->create();
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'motorhome_id' => $motorhome->id,
        ]);
        $data = $this->validData($specialOffer, [
            'motorhome_ids' => [$motorhome->id],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $specialOffer = SpecialOffer::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('motorhome_special_offer', [
            'motorhome_id' => $motorhome->id,
            'special_offer_id' => $specialOffer->id,
        ]);
        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'motorhome_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_feed_caravan_stock_items(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $stockItem = factory(CaravanStockItem::class)->create([
            'source' => StockItem::FEED_SOURCE,
        ]);
        $data = $this->validData($specialOffer, [
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
            'feed_caravan_stock_item_ids' => [$stockItem->id],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
            'caravan_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_feed_motorhome_stock_items(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'source' => StockItem::FEED_SOURCE,
        ]);
        $data = $this->validData($specialOffer, [
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
            'feed_motorhome_stock_item_ids' => [$stockItem->id],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'motorhome_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_landscape_image_is_an_image()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'landscape_image' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('landscape_image');
    }

    public function test_square_image_is_an_image()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'square_image' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('square_image');
    }

    public function test_square_image_has_a_minimum_width_of_480_pixels()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'square_image' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('square_image');
    }

    public function test_landscape_image_has_a_minimum_width_of_960_pixels()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'landscape_image' => UploadedFile::fake()->image('avatar.jpg', 900, 900),
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('landscape_image');
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName)
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            $inputName => null,
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['content'],
            ['live'],
            ['type'],
            ['site_ids'],
            ['link_used_caravan_stock'],
            ['link_used_motorhome_stock'],
            ['link_managers_special_stock'],
            ['link_on_sale_stock'],
            ['stock_bar_colour'],
            ['stock_bar_text_colour'],
            ['icon'],
            ['position'],
        ];
    }

    public function test_type_is_in_types_array()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'type' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('type');
    }

    public function test_offer_type_is_in_types_array()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'offer_type' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('offer_type');
    }

    public function test_site_id_exists()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'site_ids' => [0],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('site_ids.0');
    }

    public function test_published_at_is_a_date()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'published_at' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('published_at');
    }

    public function test_expired_at_is_a_date()
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'expired_at' => 'abc',
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('expired_at');
    }

    public function test_caravan_ids_must_exist(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'caravan_ids' => [1],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('caravan_ids.0');
    }

    public function test_motorhome_ids_must_exist(): void
    {
        $specialOffer = $this->createSpecialOffer();
        $data = $this->validData($specialOffer, [
            'motorhome_ids' => [1],
        ]);

        $response = $this->submit($specialOffer, $data);

        $response->assertSessionHasErrors('motorhome_ids.0');
    }

    private function validData(SpecialOffer $specialOffer, array $overrides = [])
    {
        $data = [
            'live' => true,
            'name' => 'Some name',
            'content' => 'Some name',
            'type' => SpecialOffer::TYPES[array_rand(SpecialOffer::TYPES)],
            'offer_type' => SpecialOffer::OFFER_TYPES[array_rand(SpecialOffer::OFFER_TYPES)],
            'link_used_caravan_stock' => true,
            'link_used_motorhome_stock' => true,
            'link_managers_special_stock' => true,
            'link_on_sale_stock' => true,
            'stock_bar_colour' => $this->faker->randomElement(array_keys(SpecialOffer::STOCK_BAR_COLOURS)),
            'stock_bar_text_colour' => $this->faker->randomElement(array_keys(SpecialOffer::STOCK_BAR_TEXT_COLOURS)),
            'icon' => 'star.svg',
            'position' => 10,
        ];

        if (!array_key_exists('site_ids', $overrides)) {
            $data['site_ids'] = [
                $this->createSite()->id,
            ];
        }

        return array_merge($data, $overrides);
    }

    private function submit(SpecialOffer $specialOffer, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($specialOffer);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function url(SpecialOffer $specialOffer)
    {
        return route('admin.special-offers.update', $specialOffer);
    }

    private function createSpecialOffer()
    {
        return factory(SpecialOffer::class)->create();
    }
}
