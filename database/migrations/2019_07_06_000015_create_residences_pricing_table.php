<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_pricing', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('residences_pricing_id');
            $table->unsignedBigInteger('residence_id');
            $table->boolean('adaptive')->default(0); 
            $table->price();  

            $table
                ->foreign('residences_pricing_id')
                ->references('id')
                ->on('residences_pricings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table
                ->foreign('residence_id')
                ->references('id')
                ->on('residences')
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
        Schema::dropIfExists('residences_pricing');
    }
}
