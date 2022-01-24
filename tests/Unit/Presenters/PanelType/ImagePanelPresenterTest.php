<?php

namespace Tests\Unit\Presenters\PanelType;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Presenters\PanelType\ImagePanelPresenter as PanelPresenter;
use App\Models\Panel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImagePanelPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_image_when_image_exists(): void
    {
        $panel = factory(Panel::class)->create([
            'type' => Panel::TYPE_IMAGE,
        ]);
        $media = factory(Media::class)->create([
            'collection_name' => 'image',
            'model_id' => $panel->id,
            'model_type' => Panel::class,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $result = $presenter->getImage();

        $this->assertNotNull($result);
        $this->assertEquals($media->id, $result->id);
    }

    public function test_get_image_when_no_image(): void
    {
        $panel = factory(Panel::class)->create([
            'type' => Panel::TYPE_IMAGE,
        ]);
        $presenter = (new PanelPresenter())->setWrappedObject($panel);

        $this->assertNull($presenter->getImage());
    }
}
