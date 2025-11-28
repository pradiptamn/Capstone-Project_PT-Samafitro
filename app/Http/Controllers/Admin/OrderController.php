<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

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
        ]);

        // Update Data
        $order->status = $request->status;

        // Jika admin memilih kurir, simpan ID-nya
        if ($request->filled('courier_id')) {
            $order->courier_id = $request->courier_id;
        }

        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
