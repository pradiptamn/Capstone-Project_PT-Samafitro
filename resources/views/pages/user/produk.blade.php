@extends('layouts.loggedin')

@section('title', 'Produk Kami | Samafitro')

@section('content')
  <style>
    /* ====== TATA LETAK & DASAR ====== */
    [x-cloak] {
      display: none !important;
    }

    .katalog-header {
      text-align: center;
      margin-top: 20px;
      margin-bottom: 30px;
    }

    .katalog-header h1 {
      font-size: 2.2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    .katalog-header p {
      color: #ccc;
      font-size: 1.1rem;
      margin-bottom: 20px;
    }

    .search-container {
      margin: 20px auto;
      max-width: 400px;
      position: relative;
      width: 100%;
      padding: 0 16px;
      box-sizing: border-box;
    }

    .input-with-icon {
      position: relative;
      width: 100%;
    }

    .input-with-icon i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      pointer-events: none;
      z-index: 2;
    }

    .input-with-icon input {
      width: 100%;
      padding: 12px 12px 12px 36px;
      border-radius: 8px;
      border: 1px solid #444;
      background: #333;
      color: white;
      outline: none;
      transition: border-color 0.2s;
    }

    .input-with-icon input:focus {
      border-color: #7377e3;
      background: #3a3a3a;
      box-shadow: 0 0 0 2px rgba(115, 119, 227, 0.2);
    }

    .category-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin: 20px auto;
      max-width: 800px;
      justify-content: center;
    }

    .category-tab {
      padding: 10px 20px;
      border-radius: 25px;
      background: #444;
      color: #ccc;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .category-tab.active {
      background: #7377e3;
      color: white;
      font-weight: 500;
      box-shadow: 0 2px 6px rgba(115, 119, 227, 0.3);
    }

    /* ====== PRODUCT CARD REDESIGN ====== */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .product-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 20px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-8px);
      border-color: #7377e3;
      background: rgba(255, 255, 255, 0.08);
    }

    .product-card img {
      width: 100%;
      height: 140px;
      object-fit: contain;
      margin-bottom: 20px;
      filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
    }

    .product-card h3 {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 8px;
      line-height: 1.3;
      height: 2.8rem;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .product-price {
      color: #4caf50;
      font-size: 1.2rem;
      font-weight: 800;
      margin-bottom: 12px;
    }

    /* Action Buttons */
    .card-actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: auto;
    }

    .secondary-actions {
      display: flex;
      gap: 8px;
      width: 100%;
    }

    .specs-button {
      background: #7377e3;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .brochure-btn {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 10px;
      border-radius: 8px;
      font-size: 0.85rem;
      text-decoration: none;
      transition: 0.2s;
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .brochure-btn:hover {
      background: rgba(115, 119, 227, 0.2);
      border-color: #7377e3;
      color: #7377e3;
    }

    .add-to-cart-btn {
      background: #28a745;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s;
      width: 100%;
    }

    .add-to-cart-btn:hover:not(:disabled) {
      background: #218838;
    }

    .pagination-controls {
      text-align: center;
      margin: 30px 0;
      color: #ccc;
    }

    .pagination-controls a {
      display: inline-block;
      margin: 0 4px;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s;
    }

    .pagination-controls .active {
      background: #7377e3;
      color: white;
      font-weight: bold;
    }

    @media screen and (max-width: 480px) {
      .product-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <div x-data="produkPage()" x-init="init()" x-cloak>

    {{-- NOTIFIKASI TOAST --}}
    <div class="fixed top-20 right-5 z-[100] flex flex-col gap-3 pointer-events-none">
      <template x-for="notif in notifications" :key="notif.id">
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10"
          x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
          class="pointer-events-auto px-6 py-4 rounded-lg shadow-xl text-white font-medium flex items-center gap-3 min-w-[300px]"
          :class="notif.type === 'success' ? 'bg-green-600 border-l-4 border-green-400' :
              'bg-red-600 border-l-4 border-red-400'">
          <i class="fas text-lg" :class="notif.type === 'success' ? 'fa-check-circle' : 'fa-times-circle'"></i>
          <span x-text="notif.message"></span>
        </div>
      </template>
    </div>

    <div class="katalog-header">
      <h1 style="margin-top: 40px;">Produk Kami</h1>
      <p>Cari produk berdasarkan nama atau spesifikasi teknis yang Anda butuhkan</p>
      <div class="search-container">
        <div class="input-with-icon"><i class="fas fa-search"></i><input type="text" x-model="searchQuery"
            placeholder="Cari nama atau spesifikasi..."></div>
      </div>
    </div>

    {{-- TABS KATEGORI --}}
    <div class="category-tabs">
      <button class="category-tab" :class="{ 'active': selectedCategory === null }" @click="selectedCategory = null">Semua
        Kategori</button>
      <template x-for="cat in categories" :key="cat.id">
        <button class="category-tab" :class="{ 'active': selectedCategory === cat.id }" @click="selectedCategory = cat.id"
          x-text="cat.name"></button>
      </template>
    </div>

    {{-- PAGINASI --}}
    <div class="pagination-controls" x-show="totalPages > 1">
      <span x-show="currentPage > 1"><a @click="currentPage = 1" style="color: #888;">&laquo; First</a></span>
      <template x-for="page in totalPages" :key="page"><a @click="currentPage = page"
          :class="{ 'active': currentPage === page }" x-text="page"></a></template>
      <span x-show="currentPage < totalPages"><a @click="currentPage = totalPages" style="color: #888;">Last
          &raquo;</a></span>
    </div>

    {{-- PRODUCT GRID --}}
    <div class="product-grid">
      <template x-for="prod in paginatedProducts" :key="prod.id">
        <div class="product-card">
          <img
            :src="prod.gambar ? (prod.gambar.startsWith('http') ? prod.gambar : '/storage/' + prod.gambar) :
                '/images/no-image.png'"
            :alt="prod.nama_produk" loading="lazy">
          <h3 x-text="prod.nama_produk"></h3>
          <p class="product-price" x-text="formatRupiah(prod.harga)"></p>

          <p class="text-[11px] mb-4 flex items-center gap-1.5"
            :class="prod.stok <= 5 ? 'text-orange-500 font-bold' : 'text-gray-400'">
            <i class="fas fa-warehouse"></i>
            <span x-text="prod.stok > 0 ? 'Tersedia: ' + prod.stok + ' Unit' : 'Stok Habis'"></span>
          </p>

          <div class="card-actions">
            <div class="secondary-actions">
              <button class="specs-button" @click="openSpecsModal(prod)"><i class="fas fa-info-circle"></i> Spek</button>
              <template x-if="prod.link_brosur">
                <a :href="prod.link_brosur" target="_blank" class="brochure-btn"><i class="fas fa-external-link-alt"></i>
                  Lihat Brosur</a>
              </template>
            </div>
            <button class="add-to-cart-btn" @click="addToCart(prod)"
              :disabled="addingToCart === prod.id || prod.stok <= 0"
              :class="{ 'opacity-50 cursor-not-allowed bg-gray-600': addingToCart === prod.id || prod.stok <= 0 }">
              <span x-show="addingToCart !== prod.id && prod.stok > 0">Add to Cart</span>
              <span x-show="prod.stok <= 0">Habis</span>
              <span x-show="addingToCart === prod.id">Adding...</span>
            </button>
          </div>
        </div>
      </template>
    </div>

    {{-- PESAN JIKA TIDAK ADA PRODUK (FITUR ASLI DIPERTAHANKAN) --}}
    <template x-if="filteredProducts.length === 0">
      <div style="text-align: center; color: #ccc; margin: 60px 0;">
        <i class="fas fa-box-open" style="font-size: 3.5rem; opacity: 0.3; margin-bottom: 15px; display: block;"></i>
        <p class="text-lg">Tidak ada produk yang cocok dengan pencarian Anda</p>
      </div>
    </template>

    {{-- MODAL (PERBAIKAN BUG NaN) --}}
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
      <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        class="fixed inset-0 bg-gray-900 bg-opacity-80 backdrop-blur-sm" @click="closeModal()"></div>
      <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
          x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
          class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl transition-all sm:w-full sm:max-w-lg overflow-hidden">

          <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-gray-900">
            <h3 class="text-lg font-bold text-white" x-text="activeProduct?.nama_produk"></h3>
            <button @click="closeModal()" class="text-gray-500 hover:text-white transition"><i
                class="fas fa-times"></i></button>
          </div>

          <div class="px-6 py-6 max-h-[65vh] overflow-y-auto bg-gray-900/50">
            <div class="flex justify-center mb-6">
              <div class="bg-white p-3 rounded-lg shadow-lg inline-block">
                <img
                  :src="activeProduct?.gambar ? (activeProduct.gambar.startsWith('http') ? activeProduct.gambar :
                      '/storage/' + activeProduct.gambar) : '/images/no-image.png'"
                  class="h-40 object-contain mx-auto">
              </div>
            </div>

            <div class="text-center mb-8 space-y-3">
              <h4 class="text-3xl font-black text-green-400" x-text="formatRupiah(activeProduct?.harga)"></h4>
              <div class="flex flex-col items-center gap-2">
                <span class="px-4 py-1 rounded-full border text-xs font-bold"
                  :class="activeProduct?.stok <= 5 ? 'bg-orange-500/10 border-orange-500 text-orange-500' :
                      'bg-gray-800 border-gray-700 text-gray-400'"
                  x-text="activeProduct?.stok > 0 ? 'Sisa Stok: ' + activeProduct?.stok : 'Stok Habis'"></span>

                {{-- LINK BROSUR MODAL --}}
                <template x-if="activeProduct?.link_brosur">
                  <a :href="activeProduct.link_brosur" target="_blank"
                    class="text-blue-400 hover:text-blue-300 text-xs font-bold flex items-center gap-2 underline">
                    <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Brosur
                  </a>
                </template>
              </div>
            </div>

            <div x-show="activeProduct && activeProduct.deskripsi">
              <div
                class="text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] mb-3 border-b border-gray-800 pb-1">
                Spesifikasi Teknis</div>
              <ul class="divide-y divide-gray-800/50">
                <template x-for="(item, index) in activeProduct?.deskripsi" :key="index">
                  <li class="flex justify-between items-start py-3 text-sm">
                    <span class="font-bold text-blue-400 w-1/3 text-left" x-text="item.label"></span>
                    <span class="text-gray-300 w-2/3 text-right" x-text="item.value"></span>
                  </li>
                </template>
              </ul>
              {{-- JIKA DESKRIPSI ADALAH STRING --}}
              <div x-show="typeof activeProduct?.deskripsi === 'string'" class="text-gray-300 text-sm italic mt-2"
                x-text="activeProduct?.deskripsi"></div>
            </div>
          </div>

          <div class="bg-gray-900 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-800">
            <button @click="addToCart(activeProduct); closeModal()" :disabled="activeProduct?.stok <= 0"
              class="inline-flex w-full justify-center rounded-xl bg-green-600 px-6 py-3 text-sm font-black text-white shadow-lg hover:bg-green-500 transition sm:w-auto disabled:bg-gray-800 disabled:cursor-not-allowed">Add
              to Cart</button>
            <button @click="closeModal()"
              class="inline-flex w-full justify-center rounded-xl bg-gray-800 px-6 py-3 text-sm font-bold text-gray-400 border border-gray-700 hover:bg-gray-700 transition sm:w-auto">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function produkPage() {
      return {
        categories: [],
        products: [],
        selectedCategory: null,
        searchQuery: '',
        activeProduct: null,
        isModalOpen: false, // State visibility modal
        currentPage: 1,
        productsPerPage: 12,
        addingToCart: null,
        notifications: [],

        showNotification(message, type = 'success') {
          const id = Date.now();
          this.notifications.push({
            id,
            message,
            type
          });
          setTimeout(() => {
            this.notifications = this.notifications.filter(n => n.id !== id);
          }, 3000);
        },

        formatRupiah(angka) {
          if (!angka) return 'Rp 0';
          return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
          }).format(angka);
        },

        // FITUR ASLI: PENCARIAN BERDASARKAN NAMA DAN DESKRIPSI
        get filteredProducts() {
          let result = this.products;
          if (this.selectedCategory) {
            result = result.filter(p => p.kategori_id == this.selectedCategory);
          }
          const query = this.searchQuery.trim().toLowerCase();
          if (query) {
            result = result.filter(p => {
              const namaMatch = p.nama_produk.toLowerCase().includes(query);
              let deskripsiMatch = false;
              if (typeof p.deskripsi === 'string') {
                deskripsiMatch = p.deskripsi.toLowerCase().includes(query);
              } else if (Array.isArray(p.deskripsi)) {
                deskripsiMatch = p.deskripsi.some(item =>
                  (item.label && item.label.toLowerCase().includes(query)) ||
                  (item.value && item.value.toLowerCase().includes(query))
                );
              }
              return namaMatch || deskripsiMatch;
            });
          }
          return result;
        },

        get totalPages() {
          return Math.ceil(this.filteredProducts.length / this.productsPerPage) || 1;
        },
        get paginatedProducts() {
          const start = (this.currentPage - 1) * this.productsPerPage;
          return this.filteredProducts.slice(start, start + this.productsPerPage);
        },

        openSpecsModal(product) {
          this.activeProduct = product;
          this.isModalOpen = true; // Buka modal secara visual
          document.body.style.overflow = 'hidden';
        },

        closeModal() {
          this.isModalOpen = false; // Tutup secara visual (memicu transisi Alpine)
          document.body.style.overflow = 'auto';
          // Jangan hapus activeProduct di sini agar transisi keluar tetap memiliki data (cegah RpNaN)
        },

        async addToCart(product) {
          this.addingToCart = product.id;
          try {
            const response = await fetch('/user/cart/add', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({
                product_id: product.id,
                quantity: 1
              })
            });
            const data = await response.json();
            if (data.success) {
              window.dispatchEvent(new CustomEvent('cart-updated'));
              this.showNotification('Produk berhasil masuk keranjang');
            } else {
              this.showNotification(data.message, 'error');
            }
          } catch (error) {
            this.showNotification('Koneksi bermasalah', 'error');
          } finally {
            this.addingToCart = null;
          }
        },

        async init() {
          try {
            const res = await fetch('/produk/json');
            const data = await res.json();
            this.categories = data.categories || [];
            this.products = data.products || [];
            this.$watch('selectedCategory', () => {
              this.currentPage = 1;
            });
            this.$watch('searchQuery', () => {
              this.currentPage = 1;
            });
          } catch (err) {
            console.error('Error:', err);
          }
        }
      }
    }
  </script>
@endsection
