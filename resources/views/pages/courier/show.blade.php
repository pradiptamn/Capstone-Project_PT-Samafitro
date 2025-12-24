@extends('layouts.courier')

@section('title', 'Detail Pengiriman')

@section('content')
  <a href="{{ route('courier.dashboard') }}" class="text-gray-400 text-sm mb-4 inline-block">
    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
  </a>

  <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg mb-6">
    <h2 class="text-gray-400 text-xs font-bold uppercase mb-3">Informasi Penerima</h2>

    <div class="flex items-start gap-4 mb-4">
      <div class="bg-blue-600/20 p-3 rounded-full text-blue-400">
        <i class="fas fa-user text-xl"></i>
      </div>
      <div>
        <h3 class="text-lg font-bold text-white">{{ $order->user->name }}</h3>
        <p class="text-sm text-gray-400">{{ $order->shipping_phone }}</p>
      </div>
    </div>

    <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 mb-4">
      <p class="text-sm text-gray-300 leading-relaxed">
        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
        {{ $order->shipping_address }}
      </p>
      @if ($order->note)
        <p class="text-xs text-yellow-500 mt-2 border-t border-gray-700 pt-2">
          <i class="fas fa-sticky-note mr-1"></i> Catatan: {{ $order->note }}
        </p>
      @endif
    </div>

    <div class="grid grid-cols-2 gap-3">
      <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->shipping_phone) }}" target="_blank"
        class="bg-green-600 hover:bg-green-500 text-white py-2 rounded-lg text-center font-bold text-sm flex items-center justify-center gap-2">
        <i class="fab fa-whatsapp text-lg"></i> WhatsApp
      </a>
      <a href="tel:{{ $order->shipping_phone }}"
        class="bg-blue-600 hover:bg-blue-500 text-white py-2 rounded-lg text-center font-bold text-sm flex items-center justify-center gap-2">
        <i class="fas fa-phone text-lg"></i> Telepon
      </a>
    </div>
  </div>

  <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg mb-6">
    <h2 class="text-gray-400 text-xs font-bold uppercase mb-3">Barang yang Diantar</h2>
    <ul class="space-y-3">
      @foreach ($order->items as $item)
        <li class="flex justify-between items-center text-sm border-b border-gray-700 pb-2 last:border-0">
          <span class="text-white">{{ $item->product_name }}</span>
          <span class="text-gray-400 font-mono">x{{ $item->quantity }}</span>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg mb-20">
    <h2 class="text-gray-400 text-xs font-bold uppercase mb-4">Update Pengiriman</h2>

    @if ($order->status != 'completed')
      @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4 text-sm">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('courier.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
          <label class="block text-sm text-gray-400 mb-2">Status Pengiriman</label>
          <select name="status" id="statusSelect" onchange="toggleProofRequirement()"
            class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-blue-500">
            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Ambil Barang)
            </option>
            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Sedang Mengantar)
            </option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Barang Diterima)
            </option>
          </select>
        </div>

        <div class="mb-6">
          <label class="block text-sm text-gray-400 mb-2">
            Bukti Foto <span id="proofLabel" class="text-red-500 hidden">* (Wajib)</span>
          </label>

          <input type="file" name="proof_of_delivery" id="proofInput" accept="image/*"
            class="block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-700 file:text-blue-400
                        hover:file:bg-gray-600 cursor-pointer">
          <p class="text-[10px] text-gray-500 mt-1">*Foto penerima atau barang di lokasi.</p>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transition">
          Simpan Perubahan
        </button>
      </form>

      <script>
        function toggleProofRequirement() {
          const status = document.getElementById('statusSelect').value;
          const proofInput = document.getElementById('proofInput');
          const proofLabel = document.getElementById('proofLabel');

          if (status === 'completed') {
            proofInput.required = true; // Wajibkan di browser
            proofLabel.classList.remove('hidden'); // Munculkan tanda bintang merah
          } else {
            proofInput.required = false;
            proofLabel.classList.add('hidden');
          }
        }

        // Jalankan saat halaman dimuat (jika status awal sudah completed tapi belum save)
        document.addEventListener("DOMContentLoaded", toggleProofRequirement);
      </script>
    @endif
  </div>
@endsection
