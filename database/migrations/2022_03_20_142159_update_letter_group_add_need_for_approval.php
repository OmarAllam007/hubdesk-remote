<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLetterGroupAddNeedForApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letter_groups', function (Blueprint $table) {
            $table->boolean('need_for_automatic_approval')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letter_groups', function (Blueprint $table) {
            $table->dropColumn('need_for_automatic_approval');
        });
    }
}
