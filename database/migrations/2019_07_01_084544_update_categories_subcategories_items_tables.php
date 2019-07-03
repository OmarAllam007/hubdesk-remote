<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoriesSubcategoriesItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories',function (Blueprint $table){
            $table->tinyInteger('is_disabled');
            $table->integer('service_type');
        });

        Schema::table('subcategories',function (Blueprint $table){
            $table->tinyInteger('is_disabled');
            $table->integer('service_type');
        });

        Schema::table('items',function (Blueprint $table){
            $table->tinyInteger('is_disabled');
            $table->integer('service_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories',function (Blueprint $table){
            $table->dropColumn('is_disabled');
            $table->dropColumn('service_type');
        });

        Schema::table('subcategories',function (Blueprint $table){
            $table->dropColumn('is_disabled');
            $table->dropColumn('service_type');
        });

        Schema::table('items',function (Blueprint $table){
            $table->dropColumn('is_disabled');
            $table->dropColumn('service_type');
        });
    }
}
