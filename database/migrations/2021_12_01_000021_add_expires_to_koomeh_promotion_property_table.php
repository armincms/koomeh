<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiresToKoomehPromotionPropertyTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('koomeh_promotion_property', function (Blueprint $table) {  
            $table->timestamp('expires');  
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
        Schema::table('koomeh_promotion_property', function (Blueprint $table) {  
            $table->dropColumn('expires');  
            $table->dropTimestamps('expires');  
        });
    }
}
