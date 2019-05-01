<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentNotificationLeves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_notification_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_unit_id');
            $table->integer('level');
            $table->integer('days');
            $table->text('users');
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
        Schema::dropIfExists('document_notification_levels');
    }
}
