<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBusinessCardUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_card_users', function (Blueprint $table) {
            $table->unsignedInteger('business_unit_id')->nullable()->default(0);
            $table->string('personal_linkedin_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_card_users', function (Blueprint $table) {
            $table->dropColumn('business_unit_id');
            $table->dropColumn('personal_linkedin_url');
        });
    }
}
