<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->date('from');
            $table->date('to');
            $table->decimal('price_total', 9, 2);
            $table->decimal('price_day', 9, 2);
            $table->uuid('renter_review_key');
            $table->uuid('host_review_key');
            $table->boolean('demo')->default(0);
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            // $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
