<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesConditionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_condition_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource('condition');  
            $table->unsignedBigInteger('residences_condition_id');  

            $table
                ->foreign('residences_condition_id', 'residences_ct_id')
                ->on('residences_conditions')
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
        Schema::dropIfExists('residences_condition_translations');
    }
}
