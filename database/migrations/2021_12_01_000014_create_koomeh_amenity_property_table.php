<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehAmenityPropertyTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_amenity_property', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('koomeh_property_id')->constrained('koomeh_properties');  
            $table->foreignId('koomeh_amenity_id')->constrained('koomeh_amenities');
            $table->string('value')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_amenity_property');
    }
}
