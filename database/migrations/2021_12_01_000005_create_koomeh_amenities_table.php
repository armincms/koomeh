<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_amenities', function (Blueprint $table) {
            $table->id();
            $table->json('name');   
            $table->json('help')->nullable();   
            $table->integer('order')->default(0); 
            $table->boolean('required')->default(false); 
            $table->string('icon')->nullable();
            $table->string('field')->nullable();
            $table->foreignId('group_id')->constrained('koomeh_amenity_groups');
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
        Schema::dropIfExists('koomeh_amenities');
    }
}
