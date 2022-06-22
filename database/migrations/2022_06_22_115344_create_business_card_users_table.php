<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCardUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_card_users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedTinyInteger('gender')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('business_card_users');
    }
}
