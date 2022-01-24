<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\VideoResource;
use App\Models\Video;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideosController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $videos = Video::orderBy('title', 'asc')->get();

        return VideoResource::collection($videos);
    }
}
