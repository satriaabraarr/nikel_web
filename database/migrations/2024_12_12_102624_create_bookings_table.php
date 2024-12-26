<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('admin_id')->constrained('users');
            $table->string('driver');
            $table->date('rental_date');
            $table->date('return_date');
            $table->enum('approve_level_1_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('approve_level_2_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
