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
                  <th class="px-6 py-3">Status Pengiriman</th>
                  <th class="px-6 py-3">Status Pembayaran</th>
                  <th class="px-6 py-3">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                @foreach ($orders as $order)
                  {{-- LOGIKA WARNA STATUS (Supaya Rapi) --}}
                  @php
                    // 1. Warna Status Pengiriman
                    $statusClass = '';
                    $statusLabel = '';

                    switch ($order->status) {
                        case 'pending':
                            $statusClass = 'bg-yellow-600 text-white';
                            $statusLabel = 'Menunggu';
                            break;
                        case 'processing':
                            $statusClass = 'bg-blue-600 text-white';
                            $statusLabel = 'Diproses';
                            break;
                        case 'shipped':
                            $statusClass = 'bg-indigo-600 text-white';
                            $statusLabel = 'Dikirim';
                            break;
                        case 'completed':
                            $statusClass = 'bg-green-600 text-white';
                            $statusLabel = 'Selesai';
                            break;
                        case 'cancelled':
                            $statusClass = 'bg-red-600 text-white';
                            $statusLabel = 'Dibatalkan';
                            break;
                        default:
                            $statusClass = 'bg-gray-600 text-white';
                            $statusLabel = $order->status;
                    }

                    // 2. Warna Status Pembayaran
                    $paymentClass = '';
                    if ($order->payment_status == 'paid') {
                        $paymentClass = 'bg-green-900 text-green-300 border border-green-700';
                    } elseif ($order->status == 'cancelled') {
                        $paymentClass = 'bg-gray-700 text-gray-400 border border-gray-600';
                    } else {
                        $paymentClass = 'bg-red-900 text-red-300 border border-red-700';
                    }
                  @endphp

                  <tr class="hover:bg-gray-750 transition">
                    {{-- Order ID --}}
                    <td class="px-6 py-4 font-medium text-white">
                      {{ $order->order_number }}
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-6 py-4">
                      {{ $order->created_at->format('d M Y H:i') }}
                    </td>

                    {{-- Total Harga --}}
                    <td class="px-6 py-4 text-blue-400 font-bold">
                      Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    {{-- Status Pengiriman (Warna-warni) --}}
                    <td class="px-6 py-4">
                      <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                        {{ strtoupper($statusLabel) }}
                      </span>
                    </td>

                    {{-- Status Pembayaran --}}
                    <td class="px-6 py-4">
                      <span class="px-3 py-1 rounded-full text-xs font-bold {{ $paymentClass }}">
                        {{ strtoupper($order->payment_status) }}
                      </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                      <a href="{{ route('orders.show', $order->id) }}"
                        class="text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1">
                        <i class="fas fa-eye"></i> Detail
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
            <div class="mb-4">
              <i class="fas fa-shopping-bag text-6xl text-gray-700"></i>
            </div>
            <p class="mb-4 text-lg">Belum ada pesanan.</p>
            <a href="{{ route('produk.user') }}"
              class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition">
              Mulai Belanja
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
