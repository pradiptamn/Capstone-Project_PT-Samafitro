@extends('layouts.loggedin')

@section('title', 'Riwayat Pesanan')

@section('content')
  <div class="min-h-screen bg-gray-900 text-white py-10">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-bold mb-6">Riwayat Pesanan Saya</h1>

      <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        @if ($orders->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
              <thead class="bg-gray-700 text-gray-200 uppercase">
                <tr>
                  <th class="px-6 py-3">Order ID</th>
                  <th class="px-6 py-3">Tanggal</th>
                  <th class="px-6 py-3">Total Harga</th>
                  <th class="px-6 py-3">Status</th>
                  <th class="px-6 py-3">Pembayaran</th>
                  <th class="px-6 py-3">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                @foreach ($orders as $order)
                  <tr class="hover:bg-gray-750 transition">
                    <td class="px-6 py-4 font-medium text-white">
                      {{ $order->order_number }}
                    </td>
                    <td class="px-6 py-4">
                      {{ $order->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-blue-400 font-bold">
                      Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $order->status == 'completed'
                                            ? 'bg-green-600 text-white'
                                            : ($order->status == 'pending'
                                                ? 'bg-yellow-600 text-black'
                                                : 'bg-gray-600 text-white') }}">
                        {{ strtoupper($order->status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $order->payment_status == 'paid' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                        {{ strtoupper($order->payment_status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <a href="{{ route('orders.show', $order->id) }}"
                        class="text-blue-400 hover:text-blue-300 hover:underline">
                        Detail & Bayar
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="p-4">
            {{ $orders->links() }}
          </div>
        @else
          <div class="p-10 text-center text-gray-500">
            <p class="mb-4">Belum ada pesanan.</p>
            <a href="{{ route('produk.user') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
              Mulai Belanja
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
