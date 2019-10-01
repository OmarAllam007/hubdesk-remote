<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->integer('type');
            $table->integer('core_report_id')->nullable(true)->change();
        });

        Schema::table('scheduled_reports' , function (Blueprint $table){
            $table->unsignedTinyInteger('format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->integer('core_report_id')->nullable(false)->change();
        });

        Schema::table('scheduled_reports' , function (Blueprint $table){
            $table->dropColumn('format');
        });
    }
}
