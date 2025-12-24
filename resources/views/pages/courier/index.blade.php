@extends('layouts.courier')

@section('title', 'Tugas Pengiriman')

@section('content')
  <h2 class="text-lg font-bold text-white mb-4">Daftar Pengiriman</h2>

  <div class="space-y-4">
    @forelse($orders as $order)
      <a href="{{ route('courier.orders.show', $order->id) }}"
        class="block bg-gray-800 p-4 rounded-xl border border-gray-700 shadow-lg hover:border-blue-500 transition relative overflow-hidden">

        <div
          class="absolute top-0 right-0 px-3 py-1 text-xs font-bold rounded-bl-xl
                    @if ($order->status == 'completed') bg-green-600 text-white
                    @elseif($order->status == 'shipped') bg-blue-600 text-white
                    @else bg-yellow-600 text-white @endif">
          {{ strtoupper($order->status) }}
        </div>

        <div class="mt-2">
          <p class="text-xs text-gray-400">Invoice: {{ $order->order_number }}</p>
          <h3 class="text-lg font-bold text-white mt-1">{{ $order->user->name }}</h3>
          <p class="text-sm text-gray-300 mt-1 line-clamp-1">
            <i class="fas fa-map-marker-alt text-red-400 mr-1"></i> {{ $order->shipping_address }}
          </p>
          <p class="text-xs text-gray-500 mt-3 flex justify-between">
            <span>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            <span>{{ $order->created_at->format('d M Y') }}</span>
          </p>
        </div>
      </a>
    @empty
      <div class="text-center py-10">
        <div class="bg-gray-800 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-check text-3xl text-green-500"></i>
        </div>
        <p class="text-gray-400">Tidak ada tugas pengiriman saat ini.</p>
        <p class="text-xs text-gray-600">Nikmati istirahatmu!</p>
      </div>
    @endforelse
  </div>
@endsection
