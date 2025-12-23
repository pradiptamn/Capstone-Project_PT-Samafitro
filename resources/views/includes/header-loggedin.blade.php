{{-- Parent Container dengan Alpine.js Logic --}}
<div x-data="shoppingCart()" x-init="initCart()" class="relative">

  {{-- Navbar --}}
  <nav class="bg-gray-950 shadow fixed top-0 left-0 w-full z-40 transition-all duration-300 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

      {{-- Logo --}}
      <div class="flex items-center space-x-3">
        <a href="{{ route('dashboard') }}">
          <img src="{{ asset('images/logo-samafitro.png') }}" alt="Samafitro" class="h-10 md:h-14 w-auto object-contain">
        </a>
      </div>

      {{-- Desktop Menu --}}
      <ul class="hidden md:flex flex-wrap space-x-6 lg:space-x-8 text-sm font-medium text-white">
        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition">Beranda</a></li>
        <li><a href="{{ route('produk.user') }}" class="hover:text-blue-400 transition">Produk</a></li>
        <li><a href="{{ route('promo.index') }}" class="hover:text-blue-400 transition">Promo</a></li>
        <li><a href="{{ route('article.index') }}" class="hover:text-blue-400 transition">Artikel & Berita</a></li>

        {{-- MENU BARU: PESANAN SAYA --}}
        <li><a href="{{ route('orders.index') }}" class="hover:text-blue-400 transition text-yellow-400">Pesanan
            Saya</a></li>

        <li><a href="{{ route('contact-us') }}" class="hover:text-blue-400 transition">Hubungi Kami</a></li>
      </ul>

      {{-- Desktop Icons --}}
      <div class="hidden md:flex space-x-5 items-center">
        {{-- Cart Button --}}
        <button @click="cartOpen = true" class="relative group focus:outline-none">
          <img src="{{ asset('images/cart.png') }}" alt="Cart" class="h-6 w-6 group-hover:opacity-80 transition" />
          <span x-show="totalItems > 0" x-transition.scale
            class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-gray-950"
            x-text="totalItems">
          </span>
        </button>

        {{-- Profile --}}
        <a href="{{ route('profile.index') }}" class="hover:opacity-80 transition">
          <img src="{{ asset('images/profile.png') }}" alt="User" class="h-6 w-6" />
        </a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">
            Logout
          </button>
        </form>
      </div>

      {{-- Mobile Hamburger --}}
      <div class="md:hidden flex items-center space-x-4">
        {{-- Mobile Cart --}}
        <button @click="cartOpen = true" class="relative focus:outline-none">
          <img src="{{ asset('images/cart.png') }}" alt="Cart" class="h-6 w-6" />
          <span x-show="totalItems > 0"
            class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-gray-950"
            x-text="totalItems">
          </span>
        </button>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none ml-2">
          <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
        </button>
      </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
      class="md:hidden absolute top-full left-0 w-full bg-gray-950 border-t border-gray-800 shadow-xl"
      style="display: none;">
      <ul class="flex flex-col space-y-1 px-4 py-4 text-white text-sm">
        <li><a href="{{ route('dashboard') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Beranda</a></li>
        <li><a href="{{ route('produk.user') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Produk</a></li>
        <li><a href="{{ route('promo.index') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Promo</a></li>
        <li><a href="{{ route('article.index') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Artikel & Berita</a></li>
        <li><a href="{{ route('orders.index') }}"
            class="block py-2 text-yellow-400 hover:text-yellow-300 hover:bg-gray-900 rounded px-2">Pesanan Saya</a>
        </li>
        <li><a href="{{ route('contact-us') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Hubungi Kami</a></li>
        <li class="border-t border-gray-800 mt-2 pt-2"><a href="{{ route('profile.index') }}"
            class="block py-2 hover:text-blue-400 hover:bg-gray-900 rounded px-2">Profil Saya</a></li>
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-400 hover:text-red-300 w-full text-left py-2 px-2">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>

  {{-- Spacer --}}
  <div class="h-20"></div>

  {{-- CART SLIDE-OVER (DARK MODE) --}}
  <div x-show="cartOpen" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true"
    style="display: none;">

    {{-- Backdrop --}}
    <div x-show="cartOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
      x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
      class="fixed inset-0 bg-black bg-opacity-80 transition-opacity backdrop-blur-sm" @click="cartOpen = false"></div>

    <div class="fixed inset-0 overflow-hidden">
      <div class="absolute inset-0 overflow-hidden">
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

          {{-- Cart Panel --}}
          <div x-show="cartOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="pointer-events-auto w-screen max-w-md">

            <div
              class="flex h-full flex-col overflow-y-scroll bg-gray-900 shadow-2xl border-l border-gray-800 text-white">
              {{-- Cart Header --}}
              <div class="flex items-start justify-between px-4 py-6 sm:px-6 border-b border-gray-800 bg-gray-900">
                <h2 class="text-lg font-medium text-white" id="slide-over-title">
                  Keranjang Belanja (<span x-text="totalItems"></span>)
                </h2>
                <div class="ml-3 flex h-7 items-center">
                  <button @click="cartOpen = false" type="button"
                    class="-m-2 p-2 text-gray-400 hover:text-white transition">
                    <span class="sr-only">Close panel</span>
                    <i class="fas fa-times text-xl"></i>
                  </button>
                </div>
              </div>

              {{-- Cart Body --}}
              <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">

                {{-- Loading State --}}
                <div x-show="isLoading" class="flex flex-col items-center justify-center py-10">
                  <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
                  <p class="text-gray-400 text-sm">Memuat keranjang...</p>
                </div>

                {{-- Empty State --}}
                <div x-show="!isLoading && cartItems.length === 0"
                  class="flex flex-col items-center justify-center py-10 text-center h-full">
                  <div class="bg-gray-800 p-4 rounded-full mb-4 border border-gray-700">
                    <img src="{{ asset('images/cart.png') }}" alt="Empty" class="h-10 w-10 opacity-50 grayscale">
                  </div>
                  <h3 class="text-white font-medium">Keranjang Kosong</h3>
                  <p class="text-gray-400 text-sm mt-1 mb-6">Anda belum menambahkan produk apapun.</p>
                  <button @click="cartOpen = false"
                    class="text-blue-400 hover:text-blue-300 font-medium text-sm underline">
                    Lanjut Belanja &rarr;
                  </button>
                </div>

                {{-- Cart Items List --}}
                <ul x-show="!isLoading && cartItems.length > 0" role="list"
                  class="-my-6 divide-y divide-gray-800">
                  <template x-for="item in cartItems" :key="item.product_id">
                    <li class="flex py-6">
                      <div
                        class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-700 bg-gray-800 p-1">
                        <img :src="item.product.gambar" :alt="item.product.nama_produk"
                          class="h-full w-full object-contain object-center">
                      </div>

                      <div class="ml-4 flex flex-1 flex-col justify-between">
                        <div>
                          <div class="flex justify-between text-base font-medium text-white">
                            <h3 class="line-clamp-1 mr-2" x-text="item.product.nama_produk"></h3>
                            <p class="text-blue-400 shrink-0 font-bold"
                              x-text="formatRupiah(item.product.harga * item.quantity)"></p>
                          </div>
                          <p class="mt-1 text-xs text-gray-500" x-text="'@ ' + formatRupiah(item.product.harga)"></p>
                        </div>
                        <div class="flex flex-1 items-end justify-between text-sm">
                          <div class="flex items-center border border-gray-700 rounded bg-gray-800">
                            <button @click="updateQuantity(item.product_id, item.quantity - 1)"
                              class="px-2 py-1 text-gray-400 hover:text-white hover:bg-gray-700 disabled:opacity-30"
                              :disabled="item.quantity <= 1">-</button>
                            <span class="px-2 py-1 font-medium text-white min-w-[30px] text-center"
                              x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.product_id, item.quantity + 1)"
                              class="px-2 py-1 text-gray-400 hover:text-white hover:bg-gray-700">+</button>
                          </div>

                          <button type="button" @click="removeItem(item.product_id)"
                            class="font-medium text-red-500 hover:text-red-400 text-xs">
                            Hapus
                          </button>
                        </div>
                      </div>
                    </li>
                  </template>
                </ul>
              </div>

              {{-- Cart Footer --}}
              <div x-show="!isLoading && cartItems.length > 0"
                class="border-t border-gray-800 px-4 py-6 sm:px-6 bg-gray-900">
                <div class="flex justify-between text-lg font-bold text-white mb-4">
                  <p>Total</p>
                  <p class="text-blue-400" x-text="formatRupiah(totalPrice)"></p>
                </div>
                <p class="mt-0.5 text-xs text-gray-500 mb-4">Belum termasuk asuransi pengiriman.</p>
                <div class="flex gap-3">
                  <button @click="clearCart()"
                    class="flex-1 items-center justify-center rounded-md border border-gray-600 bg-gray-800 px-6 py-3 text-base font-medium text-gray-300 shadow-sm hover:bg-gray-700 hover:text-white transition">
                    Kosongkan
                  </button>

                  {{-- TOMBOL CHECKOUT BERFUNGSI --}}
                  <a href="{{ route('checkout.index') }}"
                    class="flex-[2] items-center justify-center rounded-md border border-transparent bg-green-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-green-700 transition text-center">
                    Checkout
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Alpine.js Logic --}}
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingCart', () => ({
      mobileMenuOpen: false,
      cartOpen: false,
      cartItems: [],
      totalItems: 0,
      isLoading: false,

      get totalPrice() {
        return this.cartItems.reduce((acc, item) => {
          return acc + (Number(item.product.harga) * item.quantity);
        }, 0);
      },

      initCart() {
        this.loadCart();
        window.addEventListener('cart-updated', () => this.loadCart());
      },

      async loadCart() {
        this.isLoading = true;
        try {
          const response = await fetch('/user/cart');
          const data = await response.json();
          this.cartItems = data.cart_items || [];
          this.totalItems = data.total_items || 0;
        } catch (error) {
          console.error('Error loading cart:', error);
        } finally {
          this.isLoading = false;
        }
      },

      async updateQuantity(productId, newQty) {
        if (newQty < 1) return;
        try {
          const response = await fetch('/user/cart/update', {
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
          if (data.success) this.loadCart();
        } catch (e) {
          console.error(e);
        }
      },

      async removeItem(productId) {
        if (!confirm('Hapus produk ini?')) return;
        try {
          const response = await fetch('/user/cart/remove', {
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
          if (data.success) this.loadCart();
        } catch (e) {
          console.error(e);
        }
      },

      async clearCart() {
        if (!confirm('Kosongkan semua keranjang?')) return;
        try {
          const response = await fetch('/user/cart/clear', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          const data = await response.json();
          if (data.success) this.loadCart();
        } catch (e) {
          console.error(e);
        }
      },

      formatRupiah(angka) {
        let val = Number(angka);
        if (isNaN(val)) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(val);
      }
    }));
  });
</script>
