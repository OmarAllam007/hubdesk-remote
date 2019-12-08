<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKgsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kgs_logs', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->default(0);
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kgs_logs', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('old_data');
            $table->dropColumn('new_data');
        });
    }
}
