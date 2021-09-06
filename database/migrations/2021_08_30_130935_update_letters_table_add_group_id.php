<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLettersTableAddGroupId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /* @TODO I know this is wrong but I don't have a time */
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->unsignedInteger('auth_group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn('auth_group_id');
        });
    }
}
