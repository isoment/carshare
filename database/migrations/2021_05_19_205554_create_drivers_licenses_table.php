<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->unique();
            $table->string('number');
            $table->string('state');
            $table->date('issued');
            $table->date('expiration');
            $table->date('dob');
            $table->string('street');
            $table->string('city');
            $table->string('zip');
            $table->string('license_image')->nullable();
            $table->boolean('verified')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers_licenses');
    }
}
