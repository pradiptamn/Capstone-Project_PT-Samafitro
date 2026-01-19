@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')

  @php
    $role = auth()->user()->role;
    $prefix = $role;
  @endphp

  <div class="container mx-auto px-4 py-8">

    <h2 class="text-3xl font-bold text-white mb-8">Daftar Pesanan Masuk</h2>

    {{-- ===== BLOK NOTIFIKASI SUCCESS & ERROR START ===== --}}
    <div class="mb-8">
      @if (session('success'))
        <div
          class="p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-check-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
      @endif

      @if (session('error'))
        <div class="p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-times-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
      @endif
    </div>
    {{-- ===== BLOK NOTIFIKASI SUCCESS & ERROR END ===== --}}

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
        <p class="text-gray-400 text-sm font-medium uppercase">Total Pesanan</p>
        <p class="text-3xl font-bold text-white mt-2">{{ $totalOrders }}</p>
      </div>
      <div class="bg-blue-900/30 p-6 rounded-xl border border-blue-700 shadow-lg">
        <p class="text-blue-300 text-sm font-medium uppercase">Perlu Diproses</p>
        <p class="text-3xl font-bold text-white mt-2">{{ $pendingProcess }}</p>
      </div>
      <div class="bg-yellow-900/30 p-6 rounded-xl border border-yellow-700 shadow-lg">
        <p class="text-yellow-300 text-sm font-medium uppercase">Sedang Dikirim</p>
        <p class="text-3xl font-bold text-white mt-2">{{ $onDelivery }}</p>
      </div>
      <div class="bg-green-900/30 p-6 rounded-xl border border-green-700 shadow-lg">
        <p class="text-green-300 text-sm font-medium uppercase">Selesai</p>
        <p class="text-3xl font-bold text-white mt-2">{{ $completed }}</p>
      </div>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-400">
          <thead class="bg-gray-800 text-gray-200 uppercase font-bold">
            <tr>
              <th class="px-6 py-4">Order ID</th>
              <th class="px-6 py-4">Pelanggan</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Status Bayar</th>
              <th class="px-6 py-4">Status Pengiriman</th>
              <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            @forelse($orders as $order)
              <tr class="hover:bg-gray-800 transition">
                <td class="px-6 py-4 font-medium text-white">
                  {{ $order->order_number }}
                  <div class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y') }}</div>
                </td>
                <td class="px-6 py-4">
                  {{ $order->user->name }}
                  <div class="text-xs text-gray-500">{{ $order->shipping_phone }}</div>
                </td>
                <td class="px-6 py-4 text-white font-bold">
                  Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4">
                  @if ($order->payment_status == 'paid')
                    <span
                      class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold border border-green-500/30">Lunas</span>
                  @elseif($order->payment_status == 'unpaid')
                    <span
                      class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs font-bold border border-yellow-500/30">Belum
                      Bayar</span>
                  @else
                    <span
                      class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs font-bold border border-red-500/30">{{ ucfirst($order->payment_status) }}</span>
                  @endif
                </td>
                <td class="px-6 py-4">
                  @php
                    $statusColors = [
                        'pending' => 'bg-gray-600 text-white',
                        'processing' => 'bg-blue-600 text-white',
                        'shipped' => 'bg-purple-600 text-white',
                        'completed' => 'bg-green-600 text-white',
                        'cancelled' => 'bg-red-600 text-white',
                    ];
                    $color = $statusColors[$order->status] ?? 'bg-gray-600 text-white';
                  @endphp
                  <span class="{{ $color }} px-3 py-1 rounded text-xs font-bold uppercase">
                    {{ $order->status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <a href="{{ route($prefix . '.orders.show', $order->id) }}"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-lg">
                    Kelola
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                  Belum ada pesanan masuk.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-4 border-t border-gray-700">
        {{ $orders->links() }}
      </div>
    </div>
  </div>
@endsection
