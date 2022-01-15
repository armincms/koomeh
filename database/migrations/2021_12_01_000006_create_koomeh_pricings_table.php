<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_pricings', function (Blueprint $table) {
            $table->id();
            $table->json('name');       
            $table->boolean('monday')->default(false);  
            $table->boolean('tuesday')->default(false);  
            $table->boolean('wednesday')->default(false);  
            $table->boolean('thursday')->default(false);  
            $table->boolean('friday')->default(false);  
            $table->boolean('saturday')->default(false);  
            $table->boolean('sunday')->default(false);  
            $table->boolean('vacation')->default(false);  
            $table->softDeletes(); 
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
        Schema::dropIfExists('koomeh_pricings');
    }
}
