<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_unit_id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_approvals');
    }
}
