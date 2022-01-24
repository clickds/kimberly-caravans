<?php

namespace Tests\Unit\Services\Wysiwyg;

use App\Services\Wysiwyg\ResponsiveImageUrlsGenerator;
use App\Services\Wysiwyg\UploadUrlReplacer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UploadUrlReplacerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_replaces_html()
    {
        $media = factory(Media::class)->state('wysiwyg-upload')->create([
            'id' => 6343,
        ]);
        $mockedUrlGenerator = Mockery::mock('overload:' . ResponsiveImageUrlsGenerator::class);
        $mockedUrlGenerator->shouldReceive('call')->andReturn([
            "default" => "/storage/6343/conversions/Adria-logo-550-x-200-responsive.png",
            550 => "http://new-marquis.white-agency.co.uk/storage/6343/responsive-images/Adria-logo-550-x-200___responsive_550_200.png",
        ]);
        $oldContent = '<figure class="image image-style-align-left"><img src="/storage/6343/conversions/Adria-logo-550-x-200-responsive.jpg" srcset="http://new-marquis.white-agency.co.uk/storage/6343/responsive-images/Adria-logo-550-x-200___responsive_460_167.jpg 460w, http://new-marquis.white-agency.co.uk/storage/6343/responsive-images/Adria-logo-550-x-200___responsive_550_200.jpg 550w" sizes="100vw" width="550"></figure><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>For total peace of mind all new Adria models&nbsp;are supplied with:</p><ul><li>2 Year Adria Warranty</li><li>10 Year Water Ingress Warranty*</li></ul>';

        $urlReplacer = new UploadUrlReplacer($oldContent);
        $result = $urlReplacer->call();

        $newContent = '<figure class="image image-style-align-left"><img src="/storage/6343/conversions/Adria-logo-550-x-200-responsive.png" srcset="http://new-marquis.white-agency.co.uk/storage/6343/responsive-images/Adria-logo-550-x-200___responsive_550_200.png 550w" sizes="100vw" width="550"></figure><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>For total peace of mind all new Adria models&nbsp;are supplied with:</p><ul><li>2 Year Adria Warranty</li><li>10 Year Water Ingress Warranty*</li></ul>';

        $this->assertEquals($newContent, $result);
    }
}
