@extends('layouts.loggedin')

@section('title', 'Pembayaran Pesanan #' . $order->order_number)

@section('content')
  <div class="min-h-screen bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4 max-w-4xl">

      {{-- Header --}}
      <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 transition">
          <i class="fas fa-arrow-left"></i> Riwayat Pesanan
        </a>
        <span class="bg-blue-600/20 text-blue-400 px-3 py-1 rounded-full text-xs font-bold border border-blue-600/50">
          {{ $order->order_number }}
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: DETAIL --}}
        <div class="md:col-span-2 space-y-6">

          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
            <h2 class="text-sm text-gray-400 uppercase font-bold mb-2">Status Pembayaran</h2>
            @if ($order->payment_status == 'unpaid')
              <div class="flex items-center gap-3 text-yellow-400">
                <i class="fas fa-clock text-2xl"></i>
                <span class="text-xl font-bold">Menunggu Pembayaran</span>
              </div>
              <p class="text-sm text-gray-500 mt-2">Selesaikan pembayaran agar pesanan segera diproses.</p>
            @elseif($order->payment_status == 'paid')
              <div class="flex items-center gap-3 text-green-400">
                <i class="fas fa-check-circle text-2xl"></i>
                <span class="text-xl font-bold">Lunas / Paid</span>
              </div>
            @else
              <div class="flex items-center gap-3 text-red-400">
                <i class="fas fa-times-circle text-2xl"></i>
                <span class="text-xl font-bold">{{ ucfirst($order->payment_status) }}</span>
              </div>
            @endif
          </div>

          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
            <h3 class="font-bold text-white mb-4 border-b border-gray-700 pb-2">Rincian Barang</h3>
            <div class="space-y-4">
              @foreach ($order->items as $item)
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-medium text-white">{{ $item->product_name }}</p>
                    <p class="text-xs text-gray-400">{{ $item->quantity }} x Rp
                      {{ number_format($item->price, 0, ',', '.') }}</p>
                  </div>
                  <p class="font-bold text-gray-300">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
              @endforeach
            </div>
          </div>

          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
            <h3 class="font-bold text-white mb-4 border-b border-gray-700 pb-2">Info Pengiriman</h3>
            <div class="text-sm text-gray-300 space-y-2">
              <p><span class="text-gray-500 w-24 inline-block">Penerima:</span> {{ $order->user->name }}</p>
              <p><span class="text-gray-500 w-24 inline-block">No HP:</span> {{ $order->shipping_phone }}</p>
              <p><span class="text-gray-500 w-24 inline-block">Alamat:</span> {{ $order->shipping_address }}</p>
              <p><span class="text-gray-500 w-24 inline-block">Catatan:</span> {{ $order->note ?? '-' }}</p>
            </div>
          </div>
        </div>

        {{-- KOLOM KANAN: TAGIHAN & TOMBOL --}}
        <div class="md:col-span-1">
          <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg sticky top-24">
            <h3 class="font-bold text-white mb-6">Rincian Tagihan</h3>

            <div class="space-y-3 text-sm mb-6">
              <div class="flex justify-between text-gray-400">
                <span>Subtotal Produk</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
              </div>
              <div class="flex justify-between text-gray-400">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($order->shipping_price, 0, ',', '.') }}</span>
              </div>
              <div class="border-t border-gray-700 pt-3 flex justify-between text-white font-bold text-lg">
                <span>Total Bayar</span>
                <span class="text-blue-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
              </div>
            </div>

            @if ($order->payment_status == 'unpaid')
              <button id="pay-button"
                class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 mb-3">
                Bayar Sekarang
              </button>

              <a href="{{ route('orders.check', $order->id) }}"
                class="block w-full bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold py-3 rounded-lg text-center transition border border-gray-600">
                <i class="fas fa-sync-alt mr-2"></i> Cek Status Pembayaran
              </a>
              <p class="text-xs text-gray-500 mt-2 text-center">
                Klik "Cek Status" jika Anda sudah mentransfer tapi status belum berubah.
              </p>
            @elseif($order->payment_status == 'paid')
              <div
                class="w-full bg-green-600/20 border border-green-600 text-green-400 font-bold py-3 rounded-lg text-center cursor-default">
                <i class="fas fa-check-circle mr-2"></i> Lunas / Paid
              </div>
            @else
              <div
                class="w-full bg-red-600/20 border border-red-600 text-red-400 font-bold py-3 rounded-lg text-center cursor-default">
                {{ ucfirst($order->payment_status) }}
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
  </script>

  <script type="text/javascript">
    var payButton = document.getElementById('pay-button');

    if (payButton) {
      payButton.addEventListener('click', function() {
        window.snap.pay('{{ $order->snap_token }}', {
          // KETIKA SUKSES
          onSuccess: function(result) {
            // Redirect ke Controller 'paymentFinish' bawa data order_id
            // result.order_id adalah Order Number kita (INV-...)
            window.location.href = '/user/payment/finish?order_id=' + result.order_id;
          },
          // KETIKA PENDING (User tutup popup tapi blm bayar di ATM)
          onPending: function(result) {
            alert("Menunggu pembayaran Anda!");
            window.location.reload();
          },
          // KETIKA ERROR
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
@endsection
