<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_reservations', function (Blueprint $table) {
            $table->id();
            $table->json('name');  
            $table->json('help')->nullable();  
            $table->boolean('admin_confirmation')->default(false);
            $table->boolean('user_confirmation')->default(false);
            $table->boolean('agent_confirmation')->default(false);
            $table->boolean('cancellable')->default(false);
            $table->boolean('online_payment')->default(false);
            $table->boolean('default')->default(false);
            $table->softDeletes(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koomeh_reservations');
    }
}
