<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use App\Models\Page;
use App\Models\Site;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createSuperUser($attributes = [])
    {
        return factory(User::class)->state('super')->create($attributes);
    }

    protected function createSite($attributes = [])
    {
        return factory(Site::class)->create($attributes);
    }

    protected function createDefaultSite()
    {
        return factory(Site::class)->state('default')->create();
    }

    protected function createPageForPageable($pageable, Site $site, array $overrides = [])
    {
        $attributes = [
            'pageable_id' => $pageable->id,
            'pageable_type' => get_class($pageable),
            'site_id' => $site->id,
        ];

        $attributes = array_merge($attributes, $overrides);
        return factory(Page::class)->create($attributes);
    }

    protected function getTestFilesDirectory($suffix = "")
    {
        $pathParts = [
            "/tests/Support/Files",
            $suffix,
        ];
        $pathParts = array_filter($pathParts);
        $path = implode("/", $pathParts);

        return base_path($path);
    }

    protected function getTestJpg()
    {
        return $this->getTestFilesDirectory('test.jpg');
    }

    protected function fakeStorage($disk = 'public')
    {
        Storage::fake($disk);
        config()->set('filesystems.disks.' . $disk, [
            'driver' => 'local',
            'root' => Storage::disk($disk)->getAdapter()->getPathPrefix(),
        ]);
    }
}
