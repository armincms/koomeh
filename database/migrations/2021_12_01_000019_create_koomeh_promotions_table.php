<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_promotions', function (Blueprint $table) {
            $table->id();
            $table->json('name'); 
            $table->json('label')->nullable(); 
            $table->json('help')->nullable(); 
            $table->boolean('tagged')->default(false);
            $table->boolean('marked_as')->default(false);
            $table->string('icon')->nullable(); 
            $table->price();
            $table->configuration();
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
        Schema::dropIfExists('koomeh_promotions');
    }
}
