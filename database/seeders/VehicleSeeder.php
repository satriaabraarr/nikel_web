<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        Vehicle::create([
            'name' => 'Toyota HiAce', 
            'type' => 'Passenger', 
            'owner' => 'Rental', 
            'fuel_consumption' => 12,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);

        Vehicle::create([
            'name' => 'Toyota Fortuner', 
            'type' => 'Passenger', 
            'owner' => 'Rental', 
            'fuel_consumption' => 16,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);

        Vehicle::create([
            'name' => 'Mitsubishi Pajero', 
            'type' => 'Passenger', 
            'owner' => 'Rental', 
            'fuel_consumption' => 16,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);

        Vehicle::create([
            'name' => 'Hino Dump Truck', 
            'type' => 'Goods', 
            'owner' => 'Company', 
            'fuel_consumption' => 18,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);

        Vehicle::create([
            'name' => 'Mitsubishi Fuso', 
            'type' => 'Goods', 
            'owner' => 'Company', 
            'fuel_consumption' => 18,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);

        Vehicle::create([
            'name' => 'Toyota Hilux', 
            'type' => 'Goods', 
            'owner' => 'Company', 
            'fuel_consumption' => 18,
            'service_schedule' => '2025-01-01',
            'status' => 'Available',
        ]);
    }
}
