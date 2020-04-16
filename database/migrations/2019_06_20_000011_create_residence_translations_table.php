<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidenceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residence_translations', function (Blueprint $table) {
            $table->bigIncrements('id');    
            $table->description();  
            $table->string("address")->nullable();
            $table->unsignedBigInteger('residence_id');  

            $table
                ->foreign('residence_id')->references('id')->on('residences')
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
        Schema::dropIfExists('residence_translations');
    }
}
