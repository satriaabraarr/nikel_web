<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Vehicle;

class VehicleChart extends ChartWidget
{
    protected static ?string $heading = 'Vehicle Available Status Chart';

    protected function getData(): array
    {
        // Hitung jumlah kendaraan berdasarkan status
        $availableCount = Vehicle::where('status', 'Available')->count();
        $bookedCount = Vehicle::where('status', 'Booked')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Vehicle Available Status',
                    'data' => [$availableCount, $bookedCount],
                ],
            ],
            'labels' => ['Available', 'Booked'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Anda juga bisa mengganti jenis grafik (line, pie, dll.)
    }
}
