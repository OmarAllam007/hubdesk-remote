<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessUnitIdToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
//            $table->integer('business_unit_id')->unsigned();
//            $table->foreign('business_unit_id')->references('id')->on('business_units');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
//            $table->dropColumn('business_unit_id');
//            $table->foreign('business_unit_id')->references('id')->on('business_units');
        });
    }
}
