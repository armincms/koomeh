<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoomehPropertyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koomeh_property_translations', function (Blueprint $table) {
            $table->id(); 
            $table->multilingualContent(); 
            $table->text('condition')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('koomeh_property_id')->constrained('koomeh_properties'); 
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
        Schema::dropIfExists('koomeh_property_translations');
    }
}
