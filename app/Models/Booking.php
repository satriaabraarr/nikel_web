<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'admin_id',
        'driver',
        'rental_date',
        'return_date',
        'approve_level_1_id',
        'approve_level_1_status',
        'approve_level_2_id',
        'approve_level_2_status',
        'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function approveLevel1()
    {
        return $this->belongsTo(User::class, 'approve_level_1_id');
    }

    public function approveLevel2()
    {
        return $this->belongsTo(User::class, 'approve_level_2_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // Validasi apakah kendaraan tersedia
            $vehicle = Vehicle::find($booking->vehicle_id);
            if ($vehicle->status !== 'Available') {
                throw new \Exception("The selected vehicle is not available for booking.");
            }

            // Kendaraan tetap tersedia sampai proses approval selesai
        });

        static::deleting(function ($booking) {
            // Ubah status kendaraan menjadi 'Available' setelah pemesanan dihapus
            $vehicle = $booking->vehicle;
            if ($booking->status === 'Approved') {
                $vehicle->update(['status' => 'Available']);
            }
        });
    }

    public function setStatus()
    {
        if ($this->approve_level_1_status === 'Approved' && $this->approve_level_2_status === 'Approved') {
            $this->status = 'Approved';

            // Ubah status kendaraan menjadi 'Booked'
            $this->vehicle->update(['status' => 'Booked']);
        } elseif ($this->approve_level_1_status === 'Rejected' || $this->approve_level_2_status === 'Rejected') {
            $this->status = 'Rejected';

            // Pastikan kendaraan tetap 'Available'
            $this->vehicle->update(['status' => 'Available']);
        } else {
            $this->status = 'Pending';

            // Pastikan kendaraan tetap 'Available'
            $this->vehicle->update(['status' => 'Available']);
        }

        $this->save();
    }

}