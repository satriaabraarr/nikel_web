<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;

class ReturnVehicles extends Command
{
    protected $signature = 'vehicles:return';
    protected $description = 'Return vehicles to available status after rental ends';

    public function handle()
    {
        $bookings = Booking::where('return_date', '<', Carbon::now())
            ->where('status', 'Approved')
            ->get();

        foreach ($bookings as $booking) {
            $booking->vehicle->update(['status' => 'Available']);
            $booking->delete(); // Optional, jika ingin menghapus booking
        }

        $this->info('Vehicles status updated.');
    }
}
