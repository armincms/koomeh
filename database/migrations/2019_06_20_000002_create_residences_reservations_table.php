<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences_reservations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource(); 
            $table->boolean('active')->default(1);
            $table->boolean('default')->default(0); 
            $table->boolean('admin_confirmation')->default(0);
            $table->boolean('user_confirmation')->default(0);
            $table->boolean('force_payment')->default(0);
            $table->boolean('cancelable')->default(0); 
            $table->boolean('force_reserve')->default(1); 
            $table->string('help')->nullable(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('residences_reservations');
    }
}
