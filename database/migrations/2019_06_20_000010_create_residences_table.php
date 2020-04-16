<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Armincms\Koomeh\Helper;
use Armincms\RawData\Common;

class CreateResidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');

            // location 
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('parish_id')->nullable();
            $table->coordinates();  
            $table->string('zipcode')->nullable();
 
            $table->hits();
            $table->duration();
            $table->boolean('reservable')->default(0); 
            $table->unsignedTinyInteger('guest')->default(1);
            $table->unsignedTinyInteger('adult')->nullable(1);
            $table->unsignedTinyInteger('child')->nullable(0);
            $table->unsignedTinyInteger('babe')->nullable(0);
            $table->auth('agent'); 
            $table->publication();
            $table->timestamps();
            $table->unsignedBigInteger('residences_type_id')->nullable(); 
            $table->unsignedBigInteger('residences_reservation_id')->nullable();

            $table
                ->foreign('residences_type_id')->references('id')
                ->on('residences_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('residences_reservation_id')->references('id')
                ->on('residences_reservations')
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
        Schema::dropIfExists('residences');
    }
}
