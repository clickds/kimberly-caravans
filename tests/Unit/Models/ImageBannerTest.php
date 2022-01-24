<?php

namespace Tests\Unit\Models;

use App\Models\ImageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ImageBannerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validTextColours
     */
    public function test_setting_title_text_colour_to_valid_colour($colour): void
    {
        $imageBanner = new ImageBanner();
        $imageBanner->title_text_colour = $colour;

        $this->assertEquals($colour, $imageBanner->title_text_colour);
    }

    public function test_setting_title_text_colour_to_invalid_colour(): void
    {
        $imageBanner = new ImageBanner();
        $this->expectException(InvalidArgumentException::class);

        $imageBanner->title_text_colour = 'blah';
    }

    /**
     * @dataProvider validTextColours
     */
    public function test_setting_content_text_colour_to_valid_colour($colour): void
    {
        $imageBanner = new ImageBanner();
        $imageBanner->content_text_colour = $colour;

        $this->assertEquals($colour, $imageBanner->content_text_colour);
    }

    public function test_setting_content_text_colour_to_invalid_colour(): void
    {
        $imageBanner = new ImageBanner();
        $this->expectException(InvalidArgumentException::class);

        $imageBanner->content_text_colour = 'blah';
    }

    public function validTextColours(): array
    {
        $values = array_keys(ImageBanner::TEXT_COLOURS);
        $data = [];
        foreach ($values as $value) {
            $data[] = [$value];
        }
        return $data;
    }

    /**
     * @dataProvider validBackgroundColours
     */
    public function test_setting_title_background_colour_to_valid_colour($colour): void
    {
        $imageBanner = new ImageBanner();
        $imageBanner->title_background_colour = $colour;

        $this->assertEquals($colour, $imageBanner->title_background_colour);
    }

    public function test_setting_title_background_colour_to_invalid_colour(): void
    {
        $imageBanner = new ImageBanner();
        $this->expectException(InvalidArgumentException::class);

        $imageBanner->title_background_colour = 'blah';
    }

    /**
     * @dataProvider validBackgroundColours
     */
    public function test_setting_content_background_colour_to_valid_colour($colour): void
    {
        $imageBanner = new ImageBanner();
        $imageBanner->content_background_colour = $colour;

        $this->assertEquals($colour, $imageBanner->content_background_colour);
    }

    public function test_setting_content_background_colour_to_invalid_colour(): void
    {
        $imageBanner = new ImageBanner();
        $this->expectException(InvalidArgumentException::class);

        $imageBanner->content_background_colour = 'blah';
    }

    public function validBackgroundColours(): array
    {
        $values = array_keys(ImageBanner::BACKGROUND_COLOURS);
        $data = [];
        foreach ($values as $value) {
            $data[] = [$value];
        }
        return $data;
    }
}
