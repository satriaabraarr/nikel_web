<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Passenger', 'Goods']);
            $table->enum('owner', ['Company', 'Rental']);
            $table->integer('fuel_consumption')->nullable();
            $table->date('service_schedule')->nullable();
            $table->enum('status', ['Available', 'Booked'])->default('Available'); //
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
