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
            $table->text('alternatenames')->nullable();
            // convenience attribute from alternatename table, varchar(10000)
            $table->float('latitude', 10, 6);
            $table->float('longitude', 10, 6);
            $table->string('feature', 1)->nullable();
            $table->string('feature_code', 10)->nullable();
            $table->string('country_code', 10);
            $table->string('country_code_2', 200);
            $table->string('admin1', 20)->nullable();
            $table->string('admin2', 80)->nullable();
            $table->string('admin3', 20)->nullable();
            $table->string('admin4', 20)->nullable();
            $table->integer('population')->nullable();
            $table->integer('elevation')->nullable();
            $table->string('dem', 40)->nullable();
            $table->string('timezone', 40);
            $table->date('date');
            $table->timestamps();
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
