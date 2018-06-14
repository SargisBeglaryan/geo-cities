<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Cities as Cities;
use Illuminate\Support\Facades\DB;


class CitiesController extends Controller
{
    public function index()
    {
        $allCities = Cities::select('id', 'latitude', 'longitude', 'name')->limit(10)->get();
        return view('cities')->with('allCities', $allCities);
    }

    public function getNearCities(Request $request)
    {

        $cityId = filter_var($request->get('cityId'), FILTER_SANITIZE_STRING);
        $latitude = filter_var($request->get('latitude'), FILTER_SANITIZE_STRING);
        $longitude = filter_var($request->get('longitude'), FILTER_SANITIZE_STRING);
        $nearCities = Cities::select(DB::raw('*, ( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->orderBy('distance')
            ->where('id', '<>', $cityId)
            ->limit(20)
            ->get();

        return response()->json($nearCities);
    }
}
