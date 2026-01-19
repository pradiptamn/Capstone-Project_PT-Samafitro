@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
  @php
    $role = auth()->user()->role;
    $prefix = $role;
  @endphp

  <div class="container mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <a href="{{ route($prefix . '.orders.index') }}"
          class="text-gray-400 hover:text-white flex items-center gap-2 transition mb-2 text-sm">
          <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
          Pesanan #{{ $order->order_number }}
        </h2>
      </div>

      {{-- STATUS UTAMA DI HEADER --}}
      <div class="flex flex-wrap gap-3">
        <div
          class="px-4 py-2 rounded-lg border bg-gray-900 flex flex-col items-start gap-1 
          @if ($order->status == 'completed') border-green-500 @elseif($order->status == 'cancelled') border-red-500 @else border-blue-500 @endif">
          <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Status Pesanan</span>
          <span
            class="text-sm font-bold @if ($order->status == 'completed') text-green-400 @elseif($order->status == 'cancelled') text-red-400 @else text-blue-400 @endif uppercase">
            {{ $order->status }}
          </span>
        </div>

        <div
          class="px-4 py-2 rounded-lg border bg-gray-900 flex flex-col items-start gap-1 
          @if ($order->payment_status == 'paid') border-emerald-500 @else border-yellow-500 @endif">
          <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Pembayaran</span>
          <span
            class="text-sm font-bold @if ($order->payment_status == 'paid') text-emerald-400 @else text-yellow-500 @endif uppercase">
            {{ $order->payment_status ?? 'UNPAID' }}
          </span>
        </div>
      </div>
    </div>

    {{-- ALERT MESSAGES --}}
    @if (session('success'))
      <div
        class="mb-6 bg-green-500/10 border border-green-500 text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-6 bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
        <strong class="font-bold flex items-center gap-2 mb-1"><i class="fas fa-exclamation-triangle"></i> Kesalahan
          Validasi:</strong>
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">

        {{-- Item Pesanan --}}
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

        {{-- Data Pengiriman & Catatan --}}
        <div class="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
          <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="font-bold text-white">Informasi Distribusi</h3>
            @if ($order->delivery_note)
              <a href="{{ asset('storage/' . $order->delivery_note) }}" target="_blank"
                class="text-xs bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-md flex items-center gap-2 transition">
                <i class="fas fa-file-invoice"></i> Lihat Surat Jalan
              </a>
            @endif
          </div>
          <div class="p-6 text-gray-300 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <p><span class="text-gray-500 w-32 inline-block">Nama Penerima:</span> {{ $order->user->name }}</p>
              <p><span class="text-gray-500 w-32 inline-block">No HP:</span> {{ $order->shipping_phone }}</p>
              <p><span class="text-gray-500 w-32 inline-block">Alamat:</span> {{ $order->shipping_address }}</p>
              <p><span class="text-gray-500 w-32 inline-block">Catatan:</span> {{ $order->note ?? '-' }}</p>
            </div>
            <div class="space-y-2 border-t md:border-t-0 md:border-l border-gray-800 md:pl-6 pt-4 md:pt-0 text-sm">
              <p><span class="text-gray-500 w-32 inline-block">Status Bayar:</span>
                <span class="font-bold {{ $order->payment_status == 'paid' ? 'text-emerald-400' : 'text-yellow-500' }}">
                  {{ $order->payment_status == 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                </span>
              </p>
              <p><span class="text-gray-500 w-32 inline-block">Surat Jalan:</span>
                @if ($order->delivery_note)
                  <span class="text-green-400 font-medium">Sudah Terbit</span>
                @else
                  <span class="text-yellow-500 font-medium">Belum Diunggah</span>
                @endif
              </p>
              <p><span class="text-gray-500 w-32 inline-block">Kurir:</span>
                @if ($order->courier_id)
                  <span class="text-green-400 font-medium">{{ $order->courier->name }}</span>
                @else
                  <span class="text-yellow-500 font-medium">-</span>
                @endif
              </p>
            </div>
          </div>
        </div>

        {{-- Bukti Pengiriman --}}
        @if ($order->proof_of_delivery)
          <div class="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
              <h3 class="font-bold text-white">Bukti Pengiriman (Dari Kurir)</h3>
            </div>
            <div class="p-6">
              <img src="{{ asset('storage/' . $order->proof_of_delivery) }}" alt="Bukti Kirim"
                class="w-full h-auto max-h-96 object-contain rounded-lg border border-gray-600">
            </div>
          </div>
        @endif
      </div>

      {{-- Sidebar Kontrol --}}
      <div class="lg:col-span-1">
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg sticky top-6">
          <div class="bg-blue-900/20 px-6 py-4 border-b border-blue-800/30">
            <h3 class="font-bold text-blue-400">Kontrol Operasional</h3>
          </div>

          <form action="{{ route($prefix . '.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
              <label class="block text-sm font-medium text-gray-400 mb-2">Ubah Status Pesanan</label>
              <select name="status"
                class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 text-sm">
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
                class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">-- Pilih Kurir Internal --</option>
                @foreach ($couriers as $courier)
                  <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                    {{ $courier->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="pt-4 border-t border-gray-700">
              <label class="block text-sm font-medium text-gray-400 mb-2">Update Surat Jalan</label>
              <input type="file" name="delivery_note"
                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
              @error('delivery_note')
                <p class="text-red-500 text-[10px] mt-2 italic">{{ $message }}</p>
              @enderror
              <p class="text-[10px] text-gray-500 mt-2">*Format: PDF, JPG, PNG (Max 2MB). Wajib diunggah saat menugaskan
                kurir.</p>
            </div>

            <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transition text-sm">
              Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
