<?php

namespace Tests\Feature\Admin\Manufacturers;

use App\Models\CaravanStockItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Manufacturer;
use App\Models\MotorhomeStockItem;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful(): void
    {
        $manufacturer = $this->createManufacturer();

        $response = $this->submit($manufacturer);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $this->assertDatabaseMissing('manufacturers', [
            'id' => $manufacturer->id,
        ]);
    }

    public function test_cannot_destroy_manufacturer_with_motorhome_stock_items(): void
    {
        $manufacturer = $this->createManufacturer();
        $stockItem = factory(MotorhomeStockItem::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $response = $this->submit($manufacturer);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $this->assertDatabaseHas('manufacturers', $manufacturer->getAttributes());
    }

    public function test_cannot_destroy_manufacturer_with_caravan_stock_items(): void
    {
        $manufacturer = $this->createManufacturer();
        $stockItem = factory(CaravanStockItem::class)->create([
            'manufacturer_id' => $manufacturer->id,
        ]);

        $response = $this->submit($manufacturer);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $this->assertDatabaseHas('manufacturers', $manufacturer->getAttributes());
    }

    public function test_removes_site_pages(): void
    {
        $site = $this->createSite();
        $manufacturer = $this->createManufacturer();
        $manufacturer->sites()->sync($site);
        $page = $this->createPageForPageable($manufacturer, $site);

        $response = $this->submit($manufacturer);

        $response->assertRedirect(route('admin.manufacturers.index'));
        $this->assertDatabaseMissing('manufacturers', [
            'id' => $manufacturer->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Manufacturer::class,
            'pageable_id' => $manufacturer->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(Manufacturer $manufacturer)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($manufacturer);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Manufacturer $manufacturer)
    {
        return route('admin.manufacturers.destroy', $manufacturer);
    }

    private function createManufacturer($attributes = [])
    {
        return factory(Manufacturer::class)->create($attributes);
    }
}
