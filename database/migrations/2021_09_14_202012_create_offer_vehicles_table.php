<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_vehicles', function (Blueprint $table) {
            $table->bigInteger('offer_id')->unsigned();
            $table->bigInteger('vehicle_id')->unsigned();
            $table->integer('quantity');
            $table->foreign('offer_id')->references('id')->on('offers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_vehicles');
    }
}
