<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;
use UnexpectedValueException;

class AreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_background_colour_to_value_not_in_background_colours_constant()
    {
        $item = new Area;

        $this->expectException(UnexpectedValueException::class);
        $item->background_colour = 'blah';
    }

    public function test_cannot_set_holder_to_invalid_value()
    {
        $this->markTestIncomplete('Need some validation for holders');
    }

    public function test_cannot_set_columns_colour_to_value_not_in_columns_constant()
    {
        $item = new Area;

        $this->expectException(UnexpectedValueException::class);
        $item->columns = 5;
    }

    public function test_cannot_set_width_to_value_not_in_widths_constant()
    {
        $item = new Area;

        $this->expectException(UnexpectedValueException::class);
        $item->width = 'blah';
    }
}
