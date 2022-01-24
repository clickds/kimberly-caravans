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
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $this->fakeStorage();
        $data = $this->validData([
            'landscape_image' => UploadedFile::fake()->image('avatar.jpg', 960, 480),
            'square_image' => UploadedFile::fake()->image('avatar.jpg', 480, 480),
        ]);

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.special-offers.index'));
        $data = Arr::except($data, ['site_ids', 'square_image', 'landscape_image']);
        $this->assertDatabaseHas('special_offers', $data);
        $specialOffer = SpecialOffer::orderBy('id', 'desc')->first();
        $this->assertFileExists($specialOffer->getFirstMedia('squareImage')->getPath());
        $this->assertFileExists($specialOffer->getFirstMedia('landscapeImage')->getPath());
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
        $caravan = factory(Caravan::class)->create();
        $stockItem = factory(CaravanStockItem::class)->create([
            'caravan_id' => $caravan->id,
        ]);
        $data = $this->validData([
            'caravan_ids' => [$caravan->id],
        ]);

        $response = $this->submit($data);

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
        $motorhome = factory(Motorhome::class)->create();
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'motorhome_id' => $motorhome->id,
        ]);
        $data = $this->validData([
            'motorhome_ids' => [$motorhome->id],
        ]);

        $response = $this->submit($data);

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
        $stockItem = factory(CaravanStockItem::class)->create([
            'source' => StockItem::FEED_SOURCE,
        ]);
        $data = $this->validData([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
            'feed_caravan_stock_item_ids' => [$stockItem->id],
        ]);

        $response = $this->submit($data);
        $response->assertRedirect(route('admin.special-offers.index'));
        $specialOffer = SpecialOffer::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('caravan_stock_item_special_offer', [
            'caravan_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

    public function test_links_feed_motorhome_stock_items(): void
    {
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'source' => StockItem::FEED_SOURCE,
        ]);
        $data = $this->validData([
            'link_used_caravan_stock' => false,
            'link_used_motorhome_stock' => false,
            'link_managers_special_stock' => false,
            'link_on_sale_stock' => false,
            'feed_motorhome_stock_item_ids' => [$stockItem->id],
        ]);

        $response = $this->submit($data);
        $response->assertRedirect(route('admin.special-offers.index'));
        $specialOffer = SpecialOffer::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas('motorhome_stock_item_special_offer', [
            'motorhome_stock_item_id' => $stockItem->id,
            'special_offer_id' => $specialOffer->id,
        ]);
    }

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
            ['content'],
            ['name'],
            ['landscape_image'],
            ['square_image'],
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

    public function test_landscape_image_is_an_image()
    {
        $data = $this->validData([
            'landscape_image' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('landscape_image');
    }

    public function test_square_image_is_an_image()
    {
        $data = $this->validData([
            'square_image' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('square_image');
    }

    public function test_square_image_has_a_minimum_width_of_480_pixels()
    {
        $data = $this->validData([
            'square_image' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('square_image');
    }

    public function test_landscape_image_has_a_minimum_width_of_960_pixels()
    {
        $data = $this->validData([
            'landscape_image' => UploadedFile::fake()->image('avatar.jpg', 900, 900),
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('landscape_image');
    }

    public function test_type_is_in_types_array()
    {
        $data = $this->validData([
            'type' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('type');
    }

    public function test_offer_type_is_in_types_array()
    {
        $data = $this->validData([
            'offer_type' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('offer_type');
    }

    public function test_site_id_exists()
    {
        $data = $this->validData([
            'site_ids' => [0],
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('site_ids.0');
    }

    public function test_published_at_is_a_date()
    {
        $data = $this->validData([
            'published_at' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('published_at');
    }

    public function test_expired_at_is_a_date()
    {
        $data = $this->validData([
            'expired_at' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('expired_at');
    }

    public function test_caravan_ids_must_exist(): void
    {
        $data = $this->validData([
            'caravan_ids' => [1],
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('caravan_ids.*');
    }

    public function test_motorhome_ids_must_exist(): void
    {
        $data = $this->validData([
            'motorhome_ids' => [1],
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('motorhome_ids.*');
    }


    private function validData(array $overrides = [])
    {
        $data = [
            'live' => true,
            'name' => 'Some name',
            'content' => 'some content',
            'type' => SpecialOffer::TYPES[array_rand(SpecialOffer::TYPES)],
            'offer_type' => SpecialOffer::OFFER_TYPES[array_rand(SpecialOffer::OFFER_TYPES)],
            'landscape_image' => UploadedFile::fake()->image('avatar.jpg', 960, 480),
            'square_image' => UploadedFile::fake()->image('avatar.jpg', 480, 480),
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

    private function submit(array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function url()
    {
        return route('admin.special-offers.store');
    }
}
