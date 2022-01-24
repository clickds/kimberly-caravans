<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\CaravanStockItem;
use UnexpectedValueException;

class CaravanStockItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_axles_to_value_not_in_caravan_axles_constant()
    {
        $item = new CaravanStockItem;

        $this->expectException(UnexpectedValueException::class);
        $item->axles = 'blah';
    }

    public function test_cannot_set_condition_to_value_not_in_conditions_constant()
    {
        $item = new CaravanStockItem;

        $this->expectException(UnexpectedValueException::class);
        $item->condition = 'blah';
    }

    public function test_cannot_set_source_to_value_not_in_sources_constant()
    {
        $item = new CaravanStockItem;

        $this->expectException(UnexpectedValueException::class);
        $item->source = 'blah';
    }

    public function test_on_sale_if_recommended_price_is_higher_than_price()
    {
        $item = new CaravanStockItem([
            'price' => 14,
            'recommended_price' => 16,
        ]);

        $result = $item->isOnSale();

        $this->assertTrue($result);
    }

    public function test_not_on_sale_if_recommended_price_is_the_same_as_price()
    {
        $item = new CaravanStockItem([
            'price' => 14,
            'recommended_price' => 14,
        ]);

        $result = $item->isOnSale();

        $this->assertFalse($result);
    }

    public function test_price_reduction()
    {
        $item = new CaravanStockItem([
            'price' => 14,
            'recommended_price' => 15,
        ]);

        $result = $item->priceReduction();

        $this->assertEquals(1, $result);
    }
}
