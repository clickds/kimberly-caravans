<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $reviews = Review::orderBy('title', 'asc')->get();

        return ReviewResource::collection($reviews);
    }
}
