<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLetterTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letter_tickets', function (Blueprint $table) {
            $table->unsignedInteger('to_user_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letter_tickets', function (Blueprint $table) {
            $table->dropColumn('to_user_id');
            $table->dropSoftDeletes();
        });
    }
}
