<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Caravan;
use UnexpectedValueException;

class CaravanTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_axles_to_value_not_in_axles_constant(): void
    {
        $item = new Caravan;

        $this->expectException(UnexpectedValueException::class);
        $item->axles = 'blah';
    }
}
