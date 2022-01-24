<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\FeaturedImagePanelPresenter as PanelPresenter;
use App\Models\Panel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FeaturedImagePanelPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_featured_image_content(): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_FEATURED_IMAGE,
            'featured_image_content' => 'abc',
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->getFeaturedImageContent();

        $this->assertEquals('abc', $result);
    }

    public function test_get_featured_image_content_when_null(): void
    {
        $panel = factory(Panel::class)->make([
            'type' => Panel::TYPE_FEATURED_IMAGE,
            'featured_image_content' => null,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->getFeaturedImageContent();

        $this->assertEquals("", $result);
    }

    public function test_get_image_when_image_exists(): void
    {
        $panel = factory(Panel::class)->create([
            'type' => Panel::TYPE_FEATURED_IMAGE,
        ]);
        $media = factory(Media::class)->create([
            'collection_name' => 'featuredImage',
            'model_id' => $panel->id,
            'model_type' => Panel::class,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->getFeaturedImage();

        $this->assertNotNull($result);
        $this->assertEquals($media->id, $result->id);
    }

    public function test_get_image_when_no_image(): void
    {
        $panel = factory(Panel::class)->create([
            'type' => Panel::TYPE_FEATURED_IMAGE,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertNull($presenter->getFeaturedImage());
    }
}
