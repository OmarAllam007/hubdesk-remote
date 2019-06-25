<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToCustomFieldsCategoriesSubcategoriesItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
