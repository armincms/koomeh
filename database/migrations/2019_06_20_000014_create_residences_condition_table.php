<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_condition', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('residence_id'); 
            $table->unsignedBigInteger('residences_condition_id');  

            $table
                ->foreign('residence_id')
                ->references('id')
                ->on('residences')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table
                ->foreign('residences_condition_id', 'residence_rc_id')
                ->references('id')
                ->on('residences_conditions')
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
        Schema::dropIfExists('residences_condition');
    }
}
