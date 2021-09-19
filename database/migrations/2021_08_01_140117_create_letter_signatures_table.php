<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('letter_id');
            $table->unsignedInteger('business_unit_id');
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
        Schema::dropIfExists('letter_signatures');
    }
}
