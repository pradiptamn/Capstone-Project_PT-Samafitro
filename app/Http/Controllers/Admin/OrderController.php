<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil semua order, urutkan dari yang terbaru
        // Eager load 'user' agar hemat query
        $orders = Order::with('user')->latest()->paginate(10);

        // Data Ringkasan untuk Kartu Atas
        $totalOrders = Order::count();
        $pendingProcess = Order::where('status', 'paid')->count(); // Sudah bayar, butuh diproses
        $onDelivery = Order::where('status', 'shipped')->count();
        $completed = Order::where('status', 'completed')->count();

        return view('pages.admin.orders.index', compact('orders', 'totalOrders', 'pendingProcess', 'onDelivery', 'completed'));
    }

    public function show(Order $order)
    {
        // Ambil daftar kurir untuk dropdown "Assign Courier"
        $couriers = User::where('role', 'courier')->get();

        return view('pages.admin.orders.show', compact('order', 'couriers'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required',
            'courier_id' => 'nullable|exists:users,id',
            'delivery_note' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'delivery_note.max' => 'File Surat Jalan terlalu besar! Maksimal ukuran adalah 2MB.',
        ]);

        if ($request->filled('courier_id')) {
            // Cek: Apakah di database belum ada file DAN di request juga tidak ada file baru?
            if (!$order->delivery_note && !$request->hasFile('delivery_note')) {
                return back()->withErrors([
                    'delivery_note' => 'Gagal menugaskan kurir! Surat Jalan wajib ada sebelum kurir dipilih.'
                ])->withInput();
            }
        }

        // Update Data
        $order->status = $request->status;

        // Jika admin memilih kurir, simpan ID-nya
        if ($request->filled('courier_id')) {
            $order->courier_id = $request->courier_id;
        }

        if ($request->hasFile('delivery_note')) {
            // Hapus file lama jika ada penggantian
            if ($order->delivery_note) {
                Storage::disk('public')->delete($order->delivery_note);
            }

            $path = $request->file('delivery_note')->store('delivery_notes', 'public');
            $order->delivery_note = $path;
        }

        $order->save();

        return back()->with('success', 'Data pesanan berhasil diperbarui.');
    }
}
