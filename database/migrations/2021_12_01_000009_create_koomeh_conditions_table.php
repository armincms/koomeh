<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_conditions', function (Blueprint $table) {
            $table->id();
            $table->json('name');  
            $table->json('help')->nullable();  
            $table->foreignId('group_id')->constrained('koomeh_condition_groups');  
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
        Schema::dropIfExists('koomeh_conditions');
    }
}
