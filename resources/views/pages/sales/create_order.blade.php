@extends('layouts.admin')

@section('title', 'Input Pesanan Sales | Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col"
    x-data="salesOrderForm()">

    <main class="flex-1 container mx-auto px-4 py-8">
      {{-- Header Halaman --}}
      <div class="max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-bold flex items-center gap-2">
          <i class="fas fa-cart-plus text-green-500"></i> Input Pesanan Baru (Sales)
        </h2>
        <div class="text-sm text-gray-400">
          Operator: <span class="text-indigo-400 font-semibold">{{ auth()->user()->name }}</span>
        </div>
      </div>

      {{-- Notifikasi --}}
      <div class="max-w-5xl mx-auto">
        @if (session('error'))
          <div class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg flex items-center gap-3">
            <i class="fas fa-times-circle text-xl"></i>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg">
            <ul class="list-disc list-inside text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      <form action="{{ route('sales.order.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">

          {{-- KOLOM KIRI: DATA PELANGGAN --}}
          <div class="lg:col-span-1 space-y-6">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-xl" x-data="customerAutofill()">
              <h3 class="text-lg font-semibold mb-4 border-b border-gray-700 pb-2 text-indigo-400">Data Pelanggan</h3>

              <div class="space-y-4 relative">
                {{-- Email dengan Auto-suggestion --}}
                <div>
                  <label class="block mb-2 text-xs font-medium text-gray-400 uppercase">Email Pelanggan</label>
                  <input type="email" name="email" required x-model="email"
                    @input.debounce.300ms="fetchUsers(); isExistingUser = false" {{-- Reset lock saat mengetik manual --}}
                    @click.away="suggestions = []"
                    class="w-full bg-gray-900 border border-gray-700 text-white text-sm rounded-lg p-2.5 focus:ring-green-500 @error('email') border-red-500 @enderror"
                    placeholder="email@pelanggan.com" autocomplete="off">

                  {{-- Dropdown Suggestion --}}
                  <div x-show="suggestions.length > 0" x-cloak
                    class="absolute z-50 w-full mt-1 bg-gray-700 border border-gray-600 rounded-lg shadow-2xl overflow-hidden">
                    <template x-for="user in suggestions" :key="user.id">
                      <button type="button" @click="selectUser(user)"
                        class="w-full text-left px-4 py-3 text-sm hover:bg-indigo-600 transition flex flex-col border-b border-gray-600 last:border-0">
                        <span class="font-bold text-white" x-text="user.email"></span>
                        <span class="text-gray-300 text-xs" x-text="user.name"></span>
                      </button>
                    </template>
                  </div>
                </div>

                {{-- Nama Lengkap (Diberi kondisi Readonly jika User Existing) --}}
                <div>
                  <label class="block mb-2 text-xs font-medium text-gray-400 uppercase">Nama Lengkap</label>
                  <input type="text" name="customer_name" required x-model="name" :readonly="isExistingUser"
                    {{-- Kunci input jika user existing --}}
                    :class="isExistingUser ? 'bg-gray-800 text-gray-400 cursor-not-allowed' : 'bg-gray-900 text-white'"
                    class="w-full border border-gray-700 text-sm rounded-lg p-2.5 focus:ring-green-500 @error('customer_name') border-red-500 @enderror"
                    placeholder="Nama Sesuai KTP">
                  <p x-show="isExistingUser" class="mt-1 text-[10px] text-yellow-500 italic">
                    *Nama pelanggan terdaftar tidak dapat diubah.
                  </p>
                </div>

                {{-- No WhatsApp --}}
                <div>
                  <label class="block mb-2 text-xs font-medium text-gray-400 uppercase">No. WhatsApp</label>
                  <input type="text" name="phone" required x-model="phone"
                    class="w-full bg-gray-900 border border-gray-700 text-white text-sm rounded-lg p-2.5 focus:ring-green-500 @error('phone') border-red-500 @enderror"
                    placeholder="0812xxxx">
                </div>
              </div>
            </div>
          </div>

          {{-- KOLOM KANAN: ITEM PESANAN --}}
          <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-xl">
              <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                <h3 class="text-lg font-semibold text-indigo-400">Item Pesanan</h3>
                <button type="button" @click="addItem()"
                  class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs px-3 py-1.5 rounded-lg transition flex items-center gap-2">
                  <i class="fas fa-plus"></i> Tambah Item
                </button>
              </div>

              {{-- List Item Dinamis --}}
              <div class="space-y-4 max-h-[450px] overflow-y-auto pr-2 custom-scrollbar">
                <template x-for="(item, index) in items" :key="index">
                  <div
                    class="grid grid-cols-12 gap-3 items-end bg-gray-900/50 p-4 rounded-xl border border-gray-700 relative group">
                    {{-- Pilih Produk --}}
                    <div class="col-span-12 sm:col-span-7">
                      <label class="block mb-1 text-[10px] text-gray-500 uppercase tracking-wider">Produk</label>
                      <select :name="'products[' + index + '][id]'" x-model="item.id" required
                        class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-lg p-2 focus:ring-green-500">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $p)
                          <option value="{{ $p->id }}">
                            {{ $p->nama_produk }} (Stok: {{ $p->stok }})
                          </option>
                        @endforeach
                      </select>
                    </div>

                    {{-- Quantity --}}
                    <div class="col-span-8 sm:col-span-3">
                      <label class="block mb-1 text-[10px] text-gray-500 uppercase tracking-wider">Qty</label>
                      <input type="number" :name="'products[' + index + '][qty]'" x-model="item.qty" min="1"
                        required class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-lg p-2">
                    </div>

                    {{-- Hapus Item --}}
                    <div class="col-span-4 sm:col-span-2">
                      <button type="button" @click="removeItem(index)"
                        class="w-full bg-red-600/10 text-red-500 p-2 rounded-lg border border-red-600/20 hover:bg-red-600 hover:text-white transition"
                        x-show="items.length > 1">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </template>
              </div>

              {{-- Footer Form --}}
              <div class="mt-8 pt-6 border-t border-gray-700">
                <div class="flex justify-between items-center mb-6">
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-500 uppercase">Metode Pembayaran</span>
                    <span class="text-sm font-bold text-gray-300">CASH / LUNAS DI TEMPAT</span>
                  </div>
                  <span
                    class="bg-green-600/20 text-green-500 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-green-600/30">
                    PAID (LUNAS)
                  </span>
                </div>
                <button type="submit"
                  class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                  <i class="fas fa-check-double mr-2"></i> Konfirmasi & Simpan Pesanan
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </main>
  </div>



  <script>
    /**
     * Manajemen Baris Item Dinamis
     */
    function salesOrderForm() {
      return {
        // Mengambil data lama produk jika terjadi error validasi server
        items: @json(old('products', [['id' => '', 'qty' => 1]])),

        addItem() {
          this.items.push({
            id: '',
            qty: 1
          });
        },

        removeItem(index) {
          if (this.items.length > 1) {
            this.items.splice(index, 1);
          }
        }
      }
    }

    /**
     * Manajemen Autofill Customer & Suggestions
     */
    function customerAutofill() {
      return {
        email: '{{ old('email', '') }}',
        name: '{{ old('customer_name', '') }}',
        phone: '{{ old('phone', '') }}',
        isExistingUser: false, // State untuk mengunci nama
        suggestions: [],

        async fetchUsers() {
          if (this.email.length < 3) {
            this.suggestions = [];
            return;
          }
          try {
            const response = await fetch(`{{ route('sales.users.search') }}?q=${this.email}`);
            this.suggestions = await response.json();
          } catch (error) {
            console.error("Gagal mengambil data user:", error);
          }
        },

        selectUser(user) {
          this.email = user.email;
          this.name = user.name;
          this.phone = user.phone;
          this.isExistingUser = true; // Kunci input Nama Lengkap
          this.suggestions = [];
        }
      }
    }
  </script>

  <style>
    [x-cloak] {
      display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: #111827;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #374151;
      border-radius: 10px;
    }
  </style>
@endsection
