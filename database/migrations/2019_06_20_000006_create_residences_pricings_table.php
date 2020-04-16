<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('residences_pricings', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('floor_price_id')->nullable();
            $table->unsignedBigInteger('ceiling_price_id')->nullable();
            $table->boolean('default')->default(0); 
            $table->boolean('adaptive')->default(0); 
            $table->softDeletes();

            $table->foreign('floor_price_id')->references('id')
                ->on('residences_pricings')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ceiling_price_id')->references('id')
                ->on('residences_pricings')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('residences_pricings');
    }
}
