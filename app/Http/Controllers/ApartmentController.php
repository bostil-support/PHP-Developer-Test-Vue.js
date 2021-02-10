<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentListRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApartmentController extends Controller
{

    /**
     * @param  \App\Http\Requests\ApartmentListRequest  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ApartmentListRequest $request): AnonymousResourceCollection
    {
        $apartments = Apartment::paginate();

        return ApartmentResource::collection($apartments);
    }
}
