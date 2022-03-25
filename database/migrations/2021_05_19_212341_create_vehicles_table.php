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
            $table->string('featured_image');
            $table->string('year');
            $table->string('plate_num');
            $table->decimal('price_day', 9, 2);
            $table->text('description');
            $table->integer('doors');
            $table->integer('seats');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->decimal('latitude', 8, 6);
            $table->decimal('longitude', 9, 6);
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
        Schema::dropIfExists('vehicles');
    }
}
