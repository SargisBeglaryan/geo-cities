<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cities;

class CitiesController extends Controller
{
    public function index() {
    	return view('cities');
    }

    public function getNearCities(Request $request) {
    	$citiesName = filter_var($request->get('citiesName'), FILTER_SANITIZE_STRING);
    	$latitude = filter_var($request->get('latitude'), FILTER_SANITIZE_STRING);
    	$longitude = filter_var($request->get('longitude'), FILTER_SANITIZE_STRING);
    	$nearCities = City::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
	    ->having('distance', '<', 25)
	    ->orderBy('distance')
	    ->limit(20)
	    ->get();

	    return response()->json($nearCities);
    }
}
