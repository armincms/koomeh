<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPricingPropertyTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_pricing_property', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('koomeh_property_id')->constrained('koomeh_properties');  
            $table->foreignId('koomeh_pricing_id')->constrained('koomeh_pricings');  
            $table->price('amount');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_pricing_property');
    }
}
