<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessServiceTypeToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('business_service_type');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->unsignedInteger('business_service_type');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('business_service_type');
        });

        Schema::table('sub_items', function (Blueprint $table) {
            $table->unsignedInteger('business_service_type');
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
            $table->dropColumn('business_service_type');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('business_service_type');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('business_service_type');
        });

        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropColumn('business_service_type');
        });
    }
}
