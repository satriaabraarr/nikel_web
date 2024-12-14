<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingsExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Booking::query()
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->select(
                'vehicles.name as vehicle_name',
                'bookings.driver',
                'bookings.rental_date',
                'bookings.return_date',
                'bookings.status'
            );
    }

    public function headings(): array
    {
        return [
            'Vehicle Name', 
            'Driver',      
            'Rental Date',  
            'Return Date',  
            'Status',       
        ];
    }
}
