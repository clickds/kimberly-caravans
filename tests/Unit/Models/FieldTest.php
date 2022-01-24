<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Field;
use UnexpectedValueException;


class FieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_type_to_value_not_in_types_constant()
    {
        $item = new Field;

        $this->expectException(UnexpectedValueException::class);
        $item->type = 'blah';
    }

    /**
     * @dataProvider typesProvider
     */
    public function test_humanised_type($type)
    {
        $field = new Field([
            'type' => $type,
        ]);

        $result = $field->humanisedType();

        $expected = Field::TYPES[$type];
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider typesProvider
     */
    public function test_requires_options($type)
    {
        $field = new Field([
            'type' => $type,
        ]);

        $result = $field->requiresOptions();

        $expected = in_array($type, Field::TYPES_REQUIRING_OPTIONS);
        $this->assertEquals($expected, $result);
    }

    public function typesProvider()
    {
        return [
            array_keys(Field::TYPES),
        ];
    }
}
