<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $fillable = [ 'id', 'name', 'asciiname', 'alternatenames',
							'latitude', 'longitude', 'feature', 'feature_code', 'feature_code_2',
							'country_code', 'admin1', 'admin2', 'admin3', 'admin4',
							'population',	'elevation', 'dem', 'timezone', 'date'
						];
}
