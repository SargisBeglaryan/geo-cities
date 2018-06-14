<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Cities as Cities;
use Illuminate\Support\Facades\DB;
use Zip;


class CitiesController extends Controller
{
    public function index()
    {
        $allCities = Cities::select('id', 'latitude', 'longitude', 'name')->limit(1000)->get();
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

    public function test(){
    	$file = "http://download.geonames.org/export/dump/RU.zip";
		$newfile = 'cities.zip';

		if (!copy($file, $newfile)) {
		    echo "failed to copy ...";
		}
		$zip = Zip::open($newfile);
		$citiesFile = '';
		if ($zip) {
		 	$zip->extract(public_path(), 'RU.txt');
		 	ini_set('memory_limit', '-1');
		 	ini_set('max_execution_time', 1500);
			$file = fopen(public_path('RU.txt'), "r");
			while(!feof($file)){
				$oneLine = fgets($file);
				$citiesData = explode("\t",$oneLine);
				$citiesData[18] = substr($citiesData[18], 0, -1);
				Cities::insert([
					'id'=> $citiesData[0], 'name' => $citiesData[1],
					'latitude'=> $citiesData[4], 'longitude' => $citiesData[5],
					'country_code' => $citiesData[8], 'timezone' => $citiesData[17],
					'date' => $citiesData[18]
					]);
			}
			unlink(public_path('RU.txt'));
			unlink(public_path($newfile));
			fclose($file);
		    $zip->close();
		 }
    }
}
