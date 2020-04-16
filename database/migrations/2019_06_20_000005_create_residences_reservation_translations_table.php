<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesReservationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_reservation_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource(); 
            $table->string('help')->nullable();  
            $table->unsignedBigInteger('residences_reservation_id'); 

            $table
                ->foreign('residences_reservation_id', 'residences_rtid')
                ->references('id')->on('residences_reservations')
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
        Schema::dropIfExists('residences_reservation_translations');
    }
}
