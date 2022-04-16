<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalityToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('koomeh_properties', function (Blueprint $table) { 
            $table->foreignId('property_locality_id')->constrained('koomeh_property_localities');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('koomeh_properties', function (Blueprint $table) { 
            $table->dropConstrainedForeignId('property_locality_id');
        });
    }
}
