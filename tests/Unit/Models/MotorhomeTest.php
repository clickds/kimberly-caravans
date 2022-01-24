<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Motorhome;
use UnexpectedValueException;

class MotorhomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_conversion_to_value_not_in_types_constant()
    {
        $item = new Motorhome();

        $this->expectException(UnexpectedValueException::class);
        $item->conversion = 'blah';
    }

    public function test_cannot_set_fuel_to_value_not_in_fuels_constant()
    {
        $item = new Motorhome();

        $this->expectException(UnexpectedValueException::class);
        $item->fuel = 'blah';
    }

    public function test_cannot_set_transmission_to_value_not_in_transmissions_constant()
    {
        $item = new Motorhome();

        $this->expectException(UnexpectedValueException::class);
        $item->transmission = 'blah';
    }
}
