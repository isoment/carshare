<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('vehicle_model_id')->index();
            $table->string('year');
            $table->string('plate_num');
            $table->unsignedMediumInteger('price_day');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
