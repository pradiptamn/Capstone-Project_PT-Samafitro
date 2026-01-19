<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Halaman Dashboard / List Tugas Kurir
     */
    public function index()
    {
        // Ambil order yang ditugaskan ke kurir yang sedang login
        // Urutkan: Yang belum selesai di atas
        $orders = Order::where('courier_id', Auth::id())
            ->orderByRaw("FIELD(status, 'processing', 'shipped', 'completed', 'cancelled')")
            ->latest()
            ->get();

        return view('pages.courier.index', compact('orders'));
    }

    /**
     * Halaman Detail Order (Untuk Aksi)
     */
    public function show(Order $order)
    {
        // Pastikan order ini milik kurir yang login (Security)
        if ($order->courier_id != Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        return view('pages.courier.show', compact('order'));
    }

    /**
     * Update Status & Upload Bukti
     */
    public function update(Request $request, Order $order)
    {
        if ($order->courier_id != Auth::id()) {
            abort(403);
        }

        // --- PERBAIKAN VALIDASI ---
        $request->validate([
            'status' => 'required|in:shipped,completed',

            // WAJIB upload foto JIKA status == completed
            'proof_of_delivery' => 'required_if:status,completed|image|max:4096',
        ], [
            // Pesan Error Kustom agar kurir paham
            'proof_of_delivery.required_if' => 'Anda wajib mengunggah foto bukti jika pesanan sudah selesai.',
            'proof_of_delivery.image' => 'File bukti harus berupa gambar.',
            'proof_of_delivery.max' => 'Ukuran foto maksimal 4MB.',
        ]);

        // Update status
        $order->status = $request->status;

        // Handle File Upload
        if ($request->hasFile('proof_of_delivery')) {
            if ($order->proof_of_delivery) {
                Storage::disk('public')->delete($order->proof_of_delivery);
            }
            $order->proof_of_delivery = $request->file('proof_of_delivery')->store('proofs', 'public');
        }

        $order->save();

        return back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
