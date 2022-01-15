<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehConditionPropertyTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_condition_property', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('koomeh_property_id')->constrained('koomeh_properties');  
            $table->foreignId('koomeh_condition_id')->constrained('koomeh_conditions');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_condition_property');
    }
}
