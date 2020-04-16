<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_type_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource();    
            $table->unsignedBigInteger('residences_type_id'); 

            $table
                ->foreign('residences_type_id')->references('id')
                ->on('residences_types')
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
        Schema::dropIfExists('residences_type_translations');
    }
}
