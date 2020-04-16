<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesUsageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_usage_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource("usage"); 
            $table->unsignedBigInteger('residences_usage_id'); 

            $table
                ->foreign('residences_usage_id')->references('id')
                ->on('residences_usages')
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
        Schema::dropIfExists('residences_usage_translations');
    }
}
