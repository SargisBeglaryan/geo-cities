<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('asciiname', 200)->nullable();
            $table->string('alternatenames', 200)->nullable();
            // convenience attribute from alternatename table, varchar(10000)
            $table->float('latitude', 10, 6);
            $table->float('longitude', 10, 6);
            $table->string('feature', 1)->nullable();;
            $table->string('feature_code', 1)->nullable();;
            $table->string('country_code', 10);
            //cc2               : alternate country codes, comma separated, ISO-3166 2-letter country code, 200 characters
            $table->string('admin1', 20)->nullable();;
            $table->string('admin2', 80)->nullable();;
            $table->string('admin3', 20)->nullable();;
            $table->string('admin4', 20)->nullable();;
            $table->integer('population')->nullable();;
            $table->integer('elevation')->nullable();;
            //dem               : digital elevation model, srtm3 or gtopo30, average elevation of 3''x3'' (ca 90mx90m) or 30''x30'' (ca 900mx900m) area in meters, integer. srtm processed by cgiar/ciat.
            $table->string('timezone', 40);
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
