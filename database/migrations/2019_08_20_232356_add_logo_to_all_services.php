<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoToAllServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('logo', 255)->nullable();
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('logo', 255)->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('logo', 255)->nullable();
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
            $table->dropColumn('logo', 255);
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('logo', 255);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('logo', 255);
        });
    }
}
