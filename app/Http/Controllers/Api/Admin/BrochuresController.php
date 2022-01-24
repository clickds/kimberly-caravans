<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\BrochureResource;
use App\Models\Brochure;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrochuresController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $brochures = Brochure::orderBy('title', 'asc')->get();

        return BrochureResource::collection($brochures);
    }
}
