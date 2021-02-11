<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    public function __invoke()
    {
        $filter = [
            'bedrooms' => config('filter.bedrooms'),
            'bathrooms' => config('filter.bathrooms'),
            'storeys' => config('filter.storeys'),
            'garages' => config('filter.garages'),
            'min_price' => DB::table('houses')->min('price'),
            'max_price' => DB::table('houses')->max('price'),
        ];

        return view('welcome', compact('filter'));
    }
}
