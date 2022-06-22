<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCardUserAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_card_user_admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('user_id');
            $table->unsignedTinyInteger('show')->nullable()->default(0);
            $table->unsignedTinyInteger('edit')->nullable()->default(0);
            $table->unsignedTinyInteger('delete')->nullable()->default(0);
            $table->unsignedTinyInteger('create')->nullable()->default(0);
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
        Schema::dropIfExists('business_card_user_admin_permissions');
    }
}
