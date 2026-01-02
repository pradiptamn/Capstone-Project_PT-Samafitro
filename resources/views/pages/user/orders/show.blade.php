@extends('layouts.loggedin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
  <div class="min-h-screen bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4 max-w-6xl"> {{-- Lebar container diperbesar sedikit agar muat 5 step --}}

      {{-- Header --}}
      <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 transition">
          <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
        <div class="flex items-center gap-3">
          <span class="text-gray-400 text-sm">Order ID:</span>
          <span
            class="bg-blue-600/20 text-blue-400 px-3 py-1 rounded-full text-sm font-mono font-bold border border-blue-600/50">
            {{ $order->order_number }}
          </span>
        </div>
      </div>

      {{-- LOGIKA STATUS STEPPER --}}
      @php
        $step = 0;
        $progressWidth = 0;

        if ($order->status == 'cancelled') {
            $step = -1;
        } elseif ($order->status == 'completed') {
            $step = 4;
            $progressWidth = 100;
        } elseif ($order->status == 'shipped') {
            $step = 3;
            $progressWidth = 75;
        } elseif ($order->status == 'processing') {
            $step = 2;
            $progressWidth = 50;
        } elseif ($order->payment_status == 'paid') {
            $step = 1;
            $progressWidth = 25;
        } else {
            $step = 0; // Unpaid / Menunggu
            $progressWidth = 0;
        }
      @endphp

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: TIMELINE & DETAIL --}}
        <div class="md:col-span-2 space-y-6">

          {{-- 1. ORDER TIMELINE (DENGAN STATUS 'DIKIRIM') --}}
          @if ($step == -1)
            <div class="bg-red-900/20 border border-red-700 p-6 rounded-xl text-center">
              <i class="fas fa-times-circle text-4xl text-red-500 mb-2"></i>
              <h3 class="text-xl font-bold text-red-400">Pesanan Dibatalkan</h3>
              <p class="text-gray-400 text-sm">Pesanan ini telah dibatalkan dan tidak akan diproses.</p>
            </div>
          @else
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg overflow-x-auto">
              <h3 class="font-bold text-white mb-8">Lacak Pesanan</h3>

              {{-- Container Timeline (min-width agar tidak gepeng di HP) --}}
              <div class="relative flex items-center justify-between w-full min-w-[500px] px-4">

                {{-- Garis Background --}}
                <div class="absolute top-4 left-0 w-full h-1 bg-gray-700 -z-0 rounded"></div>

                {{-- Garis Progress (Hijau) --}}
                <div class="absolute top-4 left-0 h-1 bg-green-500 -z-0 rounded transition-all duration-1000 ease-out"
                  style="width: {{ $progressWidth }}%;"></div>

                {{-- Step 1: Dibuat --}}
                <div class="relative z-10 flex flex-col items-center w-20">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300
                            {{ $step >= 0 ? 'bg-green-500 border-green-500 text-white' : 'bg-gray-800 border-gray-500 text-gray-500' }}">
                    <i class="fas fa-file-invoice"></i>
                  </div>
                  <span
                    class="text-xs mt-3 font-medium text-center {{ $step >= 0 ? 'text-green-400' : 'text-gray-500' }}">Dibuat</span>
                </div>

                {{-- Step 2: Bayar --}}
                <div class="relative z-10 flex flex-col items-center w-20">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300
                            {{ $step >= 1 ? 'bg-green-500 border-green-500 text-white' : 'bg-gray-800 border-gray-500 text-gray-500' }}">
                    @if ($step >= 1)
                      <i class="fas fa-check"></i>
                    @else
                      2
                    @endif
                  </div>
                  <span
                    class="text-xs mt-3 font-medium text-center {{ $step >= 1 ? 'text-green-400' : 'text-gray-500' }}">Dibayar</span>
                </div>

                {{-- Step 3: Diproses --}}
                <div class="relative z-10 flex flex-col items-center w-20">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300
                            {{ $step >= 2 ? 'bg-green-500 border-green-500 text-white' : 'bg-gray-800 border-gray-500 text-gray-500' }}">
                    @if ($step >= 2)
                      <i class="fas fa-box-open"></i>
                    @else
                      3
                    @endif
                  </div>
                  <span
                    class="text-xs mt-3 font-medium text-center {{ $step >= 2 ? 'text-green-400' : 'text-gray-500' }}">Diproses</span>
                </div>

                {{-- Step 4: Dikirim (BARU) --}}
                <div class="relative z-10 flex flex-col items-center w-20">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300
                            {{ $step >= 3 ? 'bg-green-500 border-green-500 text-white' : 'bg-gray-800 border-gray-500 text-gray-500' }}">
                    @if ($step >= 3)
                      <i class="fas fa-shipping-fast"></i>
                    @else
                      4
                    @endif
                  </div>
                  <span
                    class="text-xs mt-3 font-medium text-center {{ $step >= 3 ? 'text-green-400' : 'text-gray-500' }}">Dikirim</span>
                </div>

                {{-- Step 5: Selesai --}}
                <div class="relative z-10 flex flex-col items-center w-20">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300
                            {{ $step >= 4 ? 'bg-green-500 border-green-500 text-white' : 'bg-gray-800 border-gray-500 text-gray-500' }}">
                    @if ($step >= 4)
                      <i class="fas fa-flag-checkered"></i>
                    @else
                      5
                    @endif
                  </div>
                  <span
                    class="text-xs mt-3 font-medium text-center {{ $step >= 4 ? 'text-green-400' : 'text-gray-500' }}">Selesai</span>
                </div>

              </div>

              {{-- Status Text Update --}}
              <div class="mt-8 p-3 bg-gray-900 rounded-lg border border-gray-700 text-sm text-center text-gray-300">
                Status Terkini:
                <span class="font-bold text-blue-400 block sm:inline mt-1 sm:mt-0">
                  @if ($step == 0)
                    Menunggu Pembayaran
                  @elseif($step == 1)
                    Pembayaran Berhasil, Menunggu Konfirmasi
                  @elseif($step == 2)
                    Pesanan Sedang Dikemas di Gudang
                  @elseif($step == 3)
                    Paket Sedang Dalam Perjalanan (Kurir)
                  @elseif($step == 4)
                    Paket Telah Diterima (Transaksi Selesai)
                  @endif
                </span>
              </div>
            </div>
          @endif

          {{-- INFO PENGIRIMAN & KURIR (Penting untuk status 'Dikirim') --}}
          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
            <h3 class="font-bold text-white mb-4 border-b border-gray-700 pb-2 flex justify-between items-center"> Info
              Pengiriman
              {{-- TAMBAHAN: Badge Status Asuransi --}}
              @if ($order->insurance_fee > 0)
                <span
                  class="text-[10px] bg-blue-900/50 text-blue-300 px-2 py-1 rounded border border-blue-700 flex items-center gap-1">
                  <i class="fas fa-shield-alt"></i> Diasuransikan
                </span>
              @else
                <span
                  class="text-[10px] bg-gray-700 text-gray-400 px-2 py-1 rounded border border-gray-600 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i> Tanpa Asuransi
                </span>
              @endif
            </h3>
            <div class="text-sm text-gray-300 space-y-3">
              <div class="flex flex-col sm:flex-row">
                <span class="text-gray-500 w-32 flex-shrink-0"><i class="fas fa-user w-5"></i> Penerima</span>
                <span>{{ $order->user->name }}</span>
              </div>
              <div class="flex flex-col sm:flex-row">
                <span class="text-gray-500 w-32 flex-shrink-0"><i class="fas fa-map-marker-alt w-5"></i> Alamat</span>
                <span>{{ $order->shipping_address }}</span>
              </div>

              {{-- JIKA STATUS >= DIKIRIM (Step 3), TAMPILKAN INFO KURIR --}}
              {{-- JIKA STATUS >= DIKIRIM (Step 3), TAMPILKAN INFO KURIR --}}
              @if ($step >= 3 && $order->courier)
                <div class="mt-4 pt-4 border-t border-gray-700 bg-gray-900/50 p-4 rounded-xl border border-blue-500/20">
                  <p class="text-blue-400 text-xs font-bold mb-3 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-truck-loading"></i> Kurir Pengantar
                  </p>
                  <div class="flex items-center gap-4">
                    {{-- FOTO KURIR ASLI --}}
                    <div class="relative shrink-0">
                      <img
                        src="{{ $order->courier->photo ? asset('storage/' . $order->courier->photo) : asset('images/profile.png') }}"
                        alt="Foto Kurir"
                        class="w-14 h-14 rounded-full object-cover border-2 border-blue-500 shadow-lg shadow-blue-900/20">
                    </div>

                    <div class="flex-1">
                      <p class="text-white font-bold text-base leading-tight">{{ $order->courier->name }}</p>
                      <p class="text-gray-500 text-[10px] mb-2">Staf Lapangan Samafitro</p>

                      <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->courier->phone) }}" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-600/10 text-green-400 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-300 border border-green-600/30">
                        <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                      </a>
                    </div>
                  </div>
                </div>
              @endif

              {{-- JIKA SELESAI, TAMPILKAN BUKTI FOTO --}}
              @if ($order->status == 'completed' && $order->proof_of_delivery)
                <div class="mt-4 pt-4 border-t border-gray-700">
                  <p class="text-green-400 font-bold mb-2"><i class="fas fa-camera w-5"></i> Bukti Penerimaan:</p>
                  <a href="{{ asset('storage/' . $order->proof_of_delivery) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->proof_of_delivery) }}"
                      class="w-full sm:w-48 h-32 object-cover rounded-lg border border-gray-600 hover:opacity-80 transition">
                  </a>
                </div>
              @endif
            </div>
          </div>

          {{-- RINCIAN BARANG --}}
          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
            <h3 class="font-bold text-white mb-4 border-b border-gray-700 pb-2">Rincian Barang</h3>
            <div class="space-y-4">
              @foreach ($order->items as $item)
                <div class="flex justify-between items-start">
                  <div class="flex gap-4">
                    {{-- LOGIKA GAMBAR PRODUK --}}
                    <div
                      class="w-14 h-14 bg-gray-700 rounded-md flex items-center justify-center overflow-hidden shrink-0 border border-gray-600">
                      @if ($item->product && $item->product->gambar)
                        <img src="{{ asset('storage/' . $item->product->gambar) }}" alt="{{ $item->product_name }}"
                          class="w-full h-full object-contain p-1">
                      @else
                        {{-- FALLBACK KE IKON JIKA KOSONG --}}
                        <i class="fas fa-box text-gray-500"></i>
                      @endif
                    </div>

                    <div>
                      <p class="font-medium text-white leading-tight mb-1">{{ $item->product_name }}</p>
                      <p class="text-xs text-gray-400">
                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                      </p>
                    </div>
                  </div>
                  <p class="font-bold text-gray-300 text-right whitespace-nowrap">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                  </p>
                </div>
              @endforeach
            </div>
          </div>

        </div>

        {{-- KOLOM KANAN: TAGIHAN & PEMBAYARAN --}}
        <div class="md:col-span-1">
          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg sticky top-24">
            <h3 class="font-bold text-white mb-6">Rincian Tagihan</h3>

            <div class="space-y-3 text-sm mb-6">
              <div class="flex justify-between text-gray-400">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
              </div>
              @if ($order->insurance_fee > 0)
                <div class="flex justify-between text-blue-400">
                  <span>Biaya Asuransi Pengiriman</span>
                  <span>Rp {{ number_format($order->insurance_fee, 0, ',', '.') }}</span>
                </div>
              @endif
              <div class="border-t border-gray-700 pt-3 flex justify-between text-white font-bold text-lg">
                <span>Total</span>
                <span class="text-blue-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
              </div>
            </div>

            {{-- Bagian Tombol Aksi di Kolom Kanan --}}
            @if ($order->payment_status == 'unpaid' && $order->status != 'cancelled')
              {{-- Tombol Bayar --}}
              <button id="pay-button"
                class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 mb-3 flex items-center justify-center gap-2">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
              </button>

              {{-- Tombol Cek Status --}}
              <a href="{{ route('orders.check', $order->id) }}"
                class="block w-full bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold py-3 rounded-lg text-center transition border border-gray-600 mb-3">
                <i class="fas fa-sync-alt mr-2"></i> Cek Status
              </a>

              {{-- TOMBOL BATALKAN PESANAN (BARU) --}}
              <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Jika ingin mengganti metode pembayaran, silakan batalkan lalu pesan ulang.');">
                @csrf
                @method('PUT')
                <button type="submit"
                  class="w-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-semibold py-3 rounded-lg text-center transition">
                  <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                </button>
              </form>
            @elseif($order->status == 'cancelled')
              {{-- Tampilan Jika Sudah Dibatalkan --}}
              <div
                class="w-full bg-red-900/20 border border-red-600 text-red-400 font-bold py-3 rounded-lg text-center cursor-default">
                <i class="fas fa-ban mr-2"></i> Pesanan Dibatalkan
              </div>
            @else
              <div
                class="w-full bg-gray-700/50 border border-gray-600 text-gray-400 py-3 rounded-lg text-center text-sm font-medium">
                Pembayaran Selesai
              </div>
            @endif
            {{-- LOGIKA TOMBOL DOWNLOAD INVOICE --}}
            <a href="{{ route('orders.invoice', $order->id) }}"
              class="mt-4 w-full border border-gray-500 text-gray-300 hover:bg-gray-700 hover:text-white py-3 rounded-lg flex items-center justify-center gap-2 transition">
              <i class="fas fa-file-invoice"></i>
              {{ $order->status == 'cancelled' ? 'Download Bukti Pembatalan' : 'Download Invoice' }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Script Midtrans --}}
  @if ($order->payment_status == 'unpaid')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      if (payButton) {
        payButton.addEventListener('click', function() {
          window.snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result) {
              window.location.href = '/user/payment/finish?order_id=' + result.order_id;
            },
            onPending: function(result) {
              alert("Menunggu pembayaran Anda!");
              window.location.reload();
            },
            onError: function(result) {
              alert("Pembayaran Gagal!");
              window.location.reload();
            },
            onClose: function() {
              alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
          });
        });
      }
    </script>
  @endif
@endsection
