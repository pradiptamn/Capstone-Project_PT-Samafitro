@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
  @php
    $role = auth()->user()->role;
    $prefix = $role;
  @endphp

  <div class="container mx-auto px-4 py-8">

    {{-- Header / Tombol Kembali --}}
    <div class="mb-6">
      <a href="{{ route($prefix . '.orders.index') }}"
        class="text-gray-400 hover:text-white flex items-center gap-2 transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
      </a>
    </div>

    {{-- ========================================== --}}
    {{-- TAMBAHAN: ALERT MESSAGES (SUCCESS / ERROR) --}}
    {{-- ========================================== --}}

    {{-- 1. Pesan Sukses --}}
    @if (session('success'))
      <div
        class="mb-6 bg-green-500/10 border border-green-500 text-green-400 px-4 py-3 rounded-lg relative flex items-center gap-3"
        role="alert">
        <i class="fas fa-check-circle text-xl"></i>
        <div>
          <strong class="font-bold">Berhasil!</strong>
          <span class="block sm:inline">{{ session('success') }}</span>
        </div>
      </div>
    @endif

    {{-- 2. Pesan Error (Dari Controller) --}}
    @if (session('error'))
      <div
        class="mb-6 bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg relative flex items-center gap-3"
        role="alert">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <div>
          <strong class="font-bold">Gagal!</strong>
          <span class="block sm:inline">{{ session('error') }}</span>
        </div>
      </div>
    @endif

    {{-- 3. Pesan Error Validasi (Misal form tidak lengkap) --}}
    @if ($errors->any())
      <div class="mb-6 bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg relative">
        <strong class="font-bold flex items-center gap-2 mb-1">
          <i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:
        </strong>
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    {{-- ========================================== --}}


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <div class="lg:col-span-2 space-y-6">

        <div class="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
          <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
            <h3 class="font-bold text-white">Item Pesanan</h3>
          </div>
          <div class="p-6">
            @foreach ($order->items as $item)
              <div class="flex justify-between items-center py-3 border-b border-gray-800 last:border-0">
                <div>
                  <p class="font-medium text-white">{{ $item->product_name }}</p>
                  <p class="text-sm text-gray-400">{{ $item->quantity }} x Rp
                    {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <p class="font-bold text-gray-300">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
              </div>
            @endforeach

            <div class="mt-4 flex justify-between text-white font-bold text-lg pt-4 border-t border-gray-700">
              <span>Total Transaksi</span>
              <span class="text-blue-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
          </div>
        </div>

        <div class="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
          <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
            <h3 class="font-bold text-white">Data Pengiriman</h3>
          </div>
          <div class="p-6 text-gray-300 space-y-2">
            <p><span class="text-gray-500 w-32 inline-block">Nama Penerima:</span> {{ $order->user->name }}</p>
            <p><span class="text-gray-500 w-32 inline-block">No HP:</span> {{ $order->shipping_phone }}</p>
            <p><span class="text-gray-500 w-32 inline-block">Alamat:</span> {{ $order->shipping_address }}</p>
            <p><span class="text-gray-500 w-32 inline-block">Catatan:</span> {{ $order->note ?? '-' }}</p>
          </div>
        </div>

        @if ($order->proof_of_delivery)
          <div class="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
              <h3 class="font-bold text-white">Bukti Pengiriman (Dari Kurir)</h3>
            </div>
            <div class="p-6">
              <img src="{{ asset('storage/' . $order->proof_of_delivery) }}" alt="Bukti Kirim"
                class="w-full h-auto rounded-lg border border-gray-600">
            </div>
          </div>
        @endif

      </div>

      <div class="lg:col-span-1">
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg sticky top-6">
          <div class="bg-blue-900/20 px-6 py-4 border-b border-blue-800/30">
            <h3 class="font-bold text-blue-400">Kontrol Admin</h3>
          </div>

          <form action="{{ route($prefix . '.orders.update', $order->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
              <label class="block text-sm font-medium text-gray-400 mb-2">Status Pesanan</label>
              <select name="status"
                class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)
                </option>
                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid (Sudah Bayar)</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Sedang
                  Disiapkan)</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Sedang Dikirim)
                </option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)
                </option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-400 mb-2">Tugaskan Kurir</label>
              <select name="courier_id"
                class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Kurir Internal --</option>
                @foreach ($couriers as $courier)
                  <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                    {{ $courier->name }} ({{ $courier->phone }})
                  </option>
                @endforeach
              </select>
              <p class="text-xs text-gray-500 mt-2">*Pilih kurir yang akan mengantar barang ini.</p>
            </div>

            <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transition">
              Simpan Perubahan
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
@endsection
