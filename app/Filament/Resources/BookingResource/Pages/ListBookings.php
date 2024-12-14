<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()->role === 'admin') {
            return [
                Actions\CreateAction::make(),
                Actions\Action::make('export')
                ->label('Vehicle Booking Report')
                ->color('success')
                ->action(fn () => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BookingsExport, 'Vehicle Booking Report.xlsx')),
            ];
        }

        return [];
    }
}
