<?php

namespace Tests\Unit\Models;

use App\Models\Button;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_invalid_colour(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $button = new Button([
            'colour' => 'blah',
        ]);
    }

    /**
     * @dataProvider coloursProvider
     */
    public function test_humanised_colour(string $colour, string $humanisedName): void
    {
        $button = new Button([
            'colour' => $colour,
        ]);

        $result = $button->humanisedColourName();

        $this->assertEquals($humanisedName, $result);
    }

    public function coloursProvider(): array
    {
        $data = [];
        foreach (Button::COLOURS as $colour => $humanisedName) {
            $data[] = [$colour, $humanisedName];
        }
        return $data;
    }
}
