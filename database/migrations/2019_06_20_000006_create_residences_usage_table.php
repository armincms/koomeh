<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_usage', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('residence_id'); 
            $table->unsignedBigInteger('residences_usage_id');  

            $table
                ->foreign('residence_id')
                ->references('id')
                ->on('residences')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table
                ->foreign('residences_usage_id', 'residences_ru_id')
                ->references('id')
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
        Schema::dropIfExists('residence_usage');
    }
}
