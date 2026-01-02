@extends('layouts.checkout')

@section('title', 'Pengiriman & Pembayaran')

@section('content')
  <div class="min-h-screen py-10 px-4">
    <div class="container mx-auto max-w-6xl">

      {{-- 1. Pesan Gagal Stok (Business Logic Error) --}}
      @if (session('error'))
        <div class="mb-6 bg-red-950/40 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md">
          <div class="flex items-start gap-3">
            <div class="text-red-500 mt-0.5">
              <i class="fas fa-exclamation-circle text-lg"></i>
            </div>
            <div>
              <h3 class="text-red-500 font-bold text-sm uppercase tracking-wider">Kesalahan Transaksi</h3>
              <p class="text-gray-300 text-sm mt-1">
                {{ session('error') }}
              </p>
            </div>
          </div>
        </div>
      @endif

      {{-- 2. Pesan Error Validasi Form (Input Error) --}}
      @if ($errors->any())
        <div class="mb-6 bg-amber-950/30 border-l-4 border-amber-500 p-4 rounded-r-lg shadow-md">
          <div class="flex items-start gap-3">
            <div class="text-amber-500 mt-0.5">
              <i class="fas fa-info-circle text-lg"></i>
            </div>
            <div>
              <h3 class="text-amber-500 font-bold text-sm uppercase tracking-wider">Lengkapi Data Pengiriman</h3>
              <ul class="text-gray-300 text-xs mt-1 list-disc list-inside opacity-90">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      {{-- Tombol Kembali --}}
      <div class="mb-8">
        <a href="{{ route('produk.user') }}"
          class="text-gray-400 hover:text-white flex items-center gap-2 transition w-fit group">
          <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
          Kembali Belanja
        </a>
      </div>

      <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          {{-- KOLOM KIRI: FORM DATA (Tidak Berubah) --}}
          <div class="lg:col-span-2 space-y-6">
            <h1 class="text-2xl font-bold text-white mb-2">Informasi Pengiriman</h1>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Nama Penerima</label>
                  <input type="text" value="{{ $user->name }}" readonly
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-gray-300 cursor-not-allowed">
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Nomor Telepon (WhatsApp)</label>
                  <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Alamat Lengkap</label>
                  <textarea name="address" rows="3" required placeholder="Nama Jalan, No. Rumah, RT/RW, Kecamatan, Kota, Kode Pos"
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">{{ old('address', $user->address) }}</textarea>
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Catatan (Opsional)</label>
                  <input type="text" name="note" placeholder="Contoh: Pagar hitam, titip satpam"
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-blue-500 outline-none">
                </div>
              </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
              <h2 class="text-lg font-bold mb-4 text-white">Metode Pengiriman</h2>
              <div class="p-4 border border-blue-500 bg-blue-500/10 rounded-lg flex justify-between items-center">
                <div class="flex items-center gap-3">
                  <i class="fas fa-truck text-blue-400 text-xl"></i>
                  <div>
                    <p class="font-bold text-white">Kurir Internal Samafitro</p>
                    <p class="text-xs text-gray-400">Pengiriman aman oleh tim kami sendiri</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-blue-400 bg-blue-900/50 px-2 py-1 rounded">Official</span>
              </div>
            </div>
          </div>

          {{-- KOLOM KANAN: RINGKASAN --}}
          <div class="lg:col-span-1">
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg sticky top-6">
              <h2 class="text-xl font-bold mb-4 text-white">Ringkasan Pesanan</h2>

              <div class="max-h-96 overflow-y-auto mb-6 pr-1 space-y-4 custom-scrollbar">
                @foreach ($cartItems as $item)
                  <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700 relative group">
                    <div class="flex gap-3 mb-3">
                      <img src="{{ asset('storage/' . $item->product->gambar) }}"
                        class="w-16 h-16 object-cover rounded bg-white shrink-0">
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white line-clamp-2 leading-tight"
                          title="{{ $item->product->nama_produk }}">
                          {{ $item->product->nama_produk }}
                        </p>

                        {{-- INFO HARGA --}}
                        <p class="text-xs text-gray-400 mt-1">
                          @ Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                        </p>

                        {{-- INFO SISA STOK (BARU) --}}
                        <p
                          class="text-[10px] mt-1 flex items-center gap-1 {{ $item->product->stok <= 5 ? 'text-orange-400 font-bold' : 'text-gray-500' }}">
                          <i class="fas fa-warehouse text-[9px]"></i>
                          Sisa Stok: {{ $item->product->stok }} Unit
                          @if ($item->product->stok <= 5)
                            <span class="animate-pulse">(Stok Terbatas!)</span>
                          @endif
                        </p>
                      </div>
                    </div>

                    {{-- Bagian tombol update Qty tetap sama di bawahnya --}}
                    <div class="flex items-center justify-between border-t border-gray-700 pt-3">
                      <div class="flex items-center border border-gray-600 rounded bg-gray-800">
                        <button type="button"
                          onclick="updateCheckoutQty('{{ $item->product_id }}', {{ $item->quantity - 1 }})"
                          class="px-2 py-1 text-gray-400 hover:text-white hover:bg-gray-700 transition disabled:opacity-30"
                          {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                          <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="px-2 text-sm font-bold text-white min-w-[30px] text-center">
                          {{ $item->quantity }}
                        </span>
                        <button type="button"
                          onclick="updateCheckoutQty('{{ $item->product_id }}', {{ $item->quantity + 1 }})"
                          class="px-2 py-1 text-gray-400 hover:text-white hover:bg-gray-700 transition">
                          <i class="fas fa-plus text-xs"></i>
                        </button>
                      </div>
                      <div class="text-right">
                        <p class="text-sm font-bold text-blue-400">
                          Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}
                        </p>
                      </div>
                    </div>

                    {{-- Tombol Hapus --}}
                    <button type="button" onclick="removeCartItem('{{ $item->product_id }}')"
                      class="absolute top-2 right-2 text-gray-600 hover:text-red-500 p-1 transition">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                @endforeach
              </div>

              {{-- --- BAGIAN OPSI INPUT (CHECKBOX) --- --}}
              <div class="mb-6">
                <div
                  class="flex justify-between items-start p-3 bg-gray-700/30 rounded-lg border border-gray-600/50 hover:bg-gray-700/50 transition cursor-pointer"
                  onclick="document.getElementById('insurance-checkbox').click()">
                  <div class="flex items-start gap-2">
                    <input id="insurance-checkbox" name="use_insurance" type="checkbox" value="1"
                      class="mt-1 w-4 h-4 text-blue-600 bg-gray-700 border-gray-500 rounded focus:ring-blue-500 cursor-pointer"
                      onclick="event.stopPropagation()">
                    <label for="insurance-checkbox" class="text-gray-300 cursor-pointer select-none text-sm">
                      Biaya Asuransi Pengiriman
                      <span class="text-[11px] text-gray-400 block mt-0.5">
                        Lindungi paket dari kerusakan & kehilangan<br>
                        <span class="text-yellow-500 font-bold ml-1">
                          (+ Rp {{ number_format($insuranceFee, 0, ',', '.') }})
                        </span>
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              {{-- --- BAGIAN RINCIAN BIAYA (OUTPUT) --- --}}
              <div class="border-t border-gray-700 pt-4 space-y-3 text-sm">

                <div class="flex justify-between text-gray-400">
                  <span>Subtotal</span>
                  <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                {{-- Baris Asuransi (Default: Hidden) --}}
                <div id="insurance-row" class="justify-between text-blue-400" style="display: none;">
                  <span>Biaya Asuransi</span>
                  <span>Rp {{ number_format($insuranceFee, 0, ',', '.') }}</span>
                </div>

                {{-- Total Bayar --}}
                <div class="flex justify-between text-white font-bold text-xl pt-3 border-t border-gray-700 mt-2">
                  <span>Total Bayar</span>
                  <span id="grand-total-display" class="text-green-400">
                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                  </span>
                </div>
              </div>

              <button type="submit"
                class="w-full mt-6 bg-green-600 hover:bg-green-500 text-white font-bold py-3.5 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-900">
                <i class="fas fa-lock mr-2"></i> Lanjut Pembayaran
              </button>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- LOGIKA ASURANSI ---
      const insuranceCheckbox = document.getElementById('insurance-checkbox');
      const insuranceRow = document.getElementById('insurance-row');
      const grandTotalDisplay = document.getElementById('grand-total-display');

      const subtotal = {{ $subtotal }};
      const insuranceFee = {{ $insuranceFee }};

      const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(number).replace('IDR', 'Rp').trim();
      };

      function updateTotal() {
        let total = subtotal;

        if (insuranceCheckbox.checked) {
          total += insuranceFee;
          // Tampilkan baris "Biaya Asuransi"
          insuranceRow.style.display = 'flex';
        } else {
          // Sembunyikan sepenuhnya
          insuranceRow.style.display = 'none';
        }

        grandTotalDisplay.innerText = formatRupiah(total);
      }

      insuranceCheckbox.addEventListener('change', updateTotal);
      updateTotal();
    });

    // --- FUNGSI UPDATE KERANJANG (BAWAAN) ---
    function showLoading() {
      document.body.innerHTML +=
        `<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; justify-content: center; align-items: center; color: white; flex-direction: column;"><i class="fas fa-spinner fa-spin fa-3x mb-3 text-blue-500"></i><p class="font-medium text-lg">Memperbarui pesanan...</p></div>`;
    }

    async function updateCheckoutQty(productId, newQty) {
      if (newQty < 1) return;
      showLoading();
      try {
        const response = await fetch("{{ route('cart.update') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: newQty
          })
        });
        const data = await response.json();
        if (data.success) window.location.reload();
        else {
          alert(data.message);
          window.location.reload();
        }
      } catch (error) {
        console.error(error);
        window.location.reload();
      }
    }

    async function removeCartItem(productId) {
      if (!confirm('Hapus produk ini dari pesanan?')) return;
      showLoading();
      try {
        const response = await fetch("{{ route('cart.remove') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            product_id: productId
          })
        });
        const data = await response.json();
        if (data.success) window.location.reload();
        else {
          alert('Gagal hapus');
          window.location.reload();
        }
      } catch (error) {
        console.error(error);
        window.location.reload();
      }
    }
  </script>
@endsection
