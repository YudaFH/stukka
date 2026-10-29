<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    // 1. Menampilkan Daftar Booking
    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->get();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();

        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings'
        ));
    }

    // ==========================================
    // BAGIAN INI YANG DIPERBAIKI
    // ==========================================
    // Ubah dari 'update' menjadi 'updateStatus' agar sesuai dengan Route kamu
    public function updateStatus(Request $request, $id) 
    {
        // Cari data booking berdasarkan ID
        $booking = Booking::findOrFail($id);

        // Validasi input
        $request->validate([
            'status' => 'required|in:approved,rejected', 
            'admin_notes' => 'nullable|string',          
        ]);

        // Simpan Perubahan
        $booking->status = $request->status;
        $booking->admin_notes = $request->admin_notes; 
        $booking->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Status booking dan catatan berhasil diperbarui!');
    }
}