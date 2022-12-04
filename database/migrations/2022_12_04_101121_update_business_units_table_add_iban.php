<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBusinessUnitsTableAddIban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_units', function (Blueprint $table) {
            $table->string('iban')->nullable();
            $table->string('iban2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_units', function (Blueprint $table) {
            $table->dropColumn('iban');
            $table->dropColumn('iban2');
        });
    }
}
