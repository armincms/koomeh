<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesPricingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('residences_pricing_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource('label'); 
            $table->unsignedBigInteger('residences_pricing_id');

            $table
                ->foreign('residences_pricing_id', 'residences_pt_id')
                ->on('residences_pricings')
                ->references('id')
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
        Schema::dropIfExists('residences_pricing_translations');
    }
}
