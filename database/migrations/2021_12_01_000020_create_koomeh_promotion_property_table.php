<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPromotionPropertyTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_promotion_property', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('koomeh_property_id')->constrained('koomeh_properties');  
            $table->foreignId('koomeh_promotion_id')->constrained('koomeh_promotions');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_promotion_property');
    }
}
