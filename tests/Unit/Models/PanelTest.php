<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Panel;
use UnexpectedValueException;


class PanelTest extends TestCase
{
    use RefreshDatabase;
    public function test_cannot_set_type_to_value_not_in_types_constant()
    {
        $item = new Panel();

        $this->expectException(UnexpectedValueException::class);
        $item->type = 'blah';
    }

    public function test_can_set_type_to_value_in_types_constant()
    {
        $item = new Panel();
        $type = array_keys(Panel::TYPES)[0];

        $item->type = $type;

        $this->assertEquals($type, $item->type);
    }

    public function test_cannot_set_vertical_positioning_to_value_not_in_vertical_positions_constant()
    {
        $item = new Panel();

        $this->expectException(UnexpectedValueException::class);
        $item->vertical_positioning = 'blah';
    }

    public function test_can_set_vertical_positioning_to_value_in_vertical_positions_constant()
    {
        $item = new Panel();
        $verticalPosition = array_keys(Panel::VERTICAL_POSITIONS)[0];

        $item->vertical_positioning = $verticalPosition;

        $this->assertEquals($verticalPosition, $item->vertical_positioning);
    }
}
