<?php

namespace App\Models\Traits;

use Illuminate\Http\RedirectResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Remove the specified MEDIA resource from storage.
 *
 * @param  Media  $id
 * @return \Illuminate\Http\Response
 */
trait ImageDeletable
{
    public function destroyImage(Media $image): RedirectResponse
    {
        $image->forceDelete();
        return back()->with('warning', 'Image has been removed');
    }
}
