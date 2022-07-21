<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomServicePathField extends Migration
{

    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('custom_path')->nullable();
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('custom_path')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('custom_path')->nullable();
        });

        Schema::table('sub_items', function (Blueprint $table) {
            $table->string('custom_path')->nullable();
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
            $table->dropColumn('custom_path');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('custom_path');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('custom_path');
        });

        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropColumn('custom_path');
        });
    }
}
