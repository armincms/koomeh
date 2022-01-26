<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPricingVacationTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_pricing_vacation', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('koomeh_vacation_id')->constrained('koomeh_vacations');  
            $table->foreignId('koomeh_pricing_id')->constrained('koomeh_pricings'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_pricing_vacation');
    }
}
