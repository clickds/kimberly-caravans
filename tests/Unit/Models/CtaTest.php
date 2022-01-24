<?php

namespace Tests\Unit\Models;

use App\Models\Cta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UnexpectedValueException;

class CtaTest extends TestCase
{
    use RefreshDatabase;

    public function test_unexpected_value_thrown_when_invalid_type(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $cta = new Cta();

        $cta->type = 'blah';
    }
}
