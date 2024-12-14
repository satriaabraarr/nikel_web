<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        // Hanya admin yang dapat membuat booking
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('bookings.create'); // Tampilkan form pembuatan booking
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver' => 'required|string|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        // Simpan booking dengan admin sebagai pembuat
        Booking::create([
            'vehicle_id' => $validated['vehicle_id'],
            'admin_id' => auth()->id(),
            'driver' => $validated['driver'],
            'booking_date' => $validated['booking_date'],
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function approve(Request $request, $id)
    {
        // Ambil booking
        $booking = Booking::findOrFail($id);

        // Pastikan hanya approve level 1 atau 2 yang bisa approve
        $userRole = auth()->user()->role;
        if ($userRole === 'approve_level_1') {
            $booking->update([
                'approve_level_1_id' => auth()->id(),
                'approve_level_1_status' => 'Approved',
            ]);
        } elseif ($userRole === 'approve_level_2') {
            $booking->update([
                'approve_level_2_id' => auth()->id(),
                'approve_level_2_status' => 'Approved',
                'status' => 'Approved',
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('bookings.index')->with('success', 'Booking approved successfully.');
    }
}
