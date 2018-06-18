<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class External_File extends Model
{
	protected $table = 'external_file';

    protected $fillable = [ 'last_modified' ];
}
