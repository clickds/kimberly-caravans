<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\SpecialOfferResource;
use App\Models\SpecialOffer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecialOffersController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $specialOffers = SpecialOffer::orderedByPosition()->orderBy('name', 'asc')->get();

        return SpecialOfferResource::collection($specialOffers);
    }
}
