<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomFieldsTableWithEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->string('event_name')->nullable();
            $table->string('listen_for')->nullable();
            $table->unsignedInteger('event_type')->nullable();
            $table->string('event_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('event_name');
            $table->dropColumn('listen_for');
            $table->dropColumn('event_type');
        });
    }
}
