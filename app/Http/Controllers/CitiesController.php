<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Cities as Cities;
use Illuminate\Support\Facades\DB;


class CitiesController extends Controller
{
    public function index()
    {
        $allCities = Cities::select('latitude', 'longitude', 'name')->get();
        return view('cities')->with('allCities', $allCities);
    }

    public function getNearCities(Request $request)
    {

        $citiesName = filter_var($request->get('citiesName'), FILTER_SANITIZE_STRING);
        $latitude = filter_var($request->get('latitude'), FILTER_SANITIZE_STRING);
        $longitude = filter_var($request->get('longitude'), FILTER_SANITIZE_STRING);
        $nearCities = Cities::select(DB::raw('*, ( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->orderBy('distance')
            ->limit(20)
            ->get();

        return response()->json($nearCities);
    }
}
