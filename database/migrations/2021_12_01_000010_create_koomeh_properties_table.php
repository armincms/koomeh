<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_properties', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedTinyInteger('minimum_reservation')->default(1); 
            $table->unsignedTinyInteger('accommodation')->default(1);
            $table->unsignedTinyInteger('max_accommodation')->default(1); 
            $table->integer('max_accommodation_payment')->default(100); 
            $table->string('code')->unique()->index();
            $table->string('marked_as')->index()->default('draft');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->foreignId('property_type_id')->constrained('koomeh_property_types');
            $table->foreignId('room_type_id')->constrained('koomeh_room_types');
            $table->foreignId('payment_basis_id')->constrained('koomeh_payment_bases');
            $table->foreignId('reservation_id')->constrained('koomeh_reservations');
            $table->foreignId('auth_id')->constrained('users');
            $table->state();
            $table->city();
            $table->zone(); 
            $table->resourceHits();  
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
        Schema::dropIfExists('koomeh_properties');
    }
}
