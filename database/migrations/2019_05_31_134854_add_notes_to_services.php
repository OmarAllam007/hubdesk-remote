<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text("notes")->nullable();
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->text("notes")->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->text("notes")->nullable();
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
            $table->dropColumn("notes");
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn("notes");
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn("notes");
        });
    }
}
