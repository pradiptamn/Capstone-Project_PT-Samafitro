@extends('layouts.loggedin')

@section('title', 'Produk Kami | Samafitro')

@section('content')
  <style>
    /* ====== STYLE ASLI ====== */
    [x-cloak] {
      display: none !important;
    }

    /* ... (Semua style CSS Asli Anda Tetap Disini - Tidak Berubah) ... */
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
      font-size: 1rem;
      width: 20px;
      text-align: center;
      z-index: 2;
    }

    .input-with-icon input {
      width: 100%;
      padding: 12px 12px 12px 36px;
      border-radius: 8px;
      border: 1px solid #444;
      background: #333;
      color: white;
      font-size: 16px;
      outline: none;
      box-sizing: border-box;
      transition: border-color 0.2s;
    }

    .input-with-icon input:focus {
      border-color: #555;
      background: #3a3a3a;
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
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
      white-space: nowrap;
      font-size: 0.9rem;
    }

    .category-tab.active {
      background: #7377e3;
      color: white;
      font-weight: 500;
      box-shadow: 0 2px 6px rgba(115, 119, 227, 0.3);
    }

    .category-tab:hover:not(.active) {
      background: #555;
      color: white;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .product-card {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 20px;
      transition: box-shadow 0.2s, transform 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .product-card:hover {
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
      transform: translateY(-4px) scale(1.02);
    }

    .product-card img {
      width: 200px;
      height: 120px;
      object-fit: contain;
      margin-bottom: 16px;
      background: none;
    }

    .product-card h3 {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 500;
      margin: 0 0 8px 0;
      letter-spacing: 0.5px;
    }

    .product-price {
      color: #4caf50;
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 16px;
      letter-spacing: 0.5px;
    }

    .specs-button {
      background: #7377e3;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.2s;
    }

    .specs-button:hover {
      background: #5a5fd8;
    }

    .add-to-cart-btn {
      background: #28a745;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.2s;
    }

    .add-to-cart-btn:hover {
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

    .pagination-controls a:hover {
      background: #555;
    }

    .pagination-controls .active {
      background: #7377e3;
      color: white;
      font-weight: bold;
    }

    @media screen and (max-width: 768px) {
      .katalog-header h1 {
        font-size: 1.8rem;
      }

      .category-tabs {
        justify-content: start;
        overflow-x: auto;
        padding: 10px 0;
      }

      .product-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        padding: 16px;
      }

      .product-card img {
        width: 150px;
        height: 90px;
      }
    }

    @media screen and (max-width: 480px) {
      .product-grid {
        grid-template-columns: 1fr;
        padding: 12px;
      }

      .search-container {
        padding: 0 12px;
      }
    }
  </style>

  <div x-data="produkPage()" x-init="init()" x-cloak>

    <div class="fixed top-20 right-5 z-[100] flex flex-col gap-3 pointer-events-none">
      <template x-for="(notif, index) in notifications" :key="notif.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
          x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
          x-transition:leave-end="opacity-0 translate-x-10"
          class="pointer-events-auto px-6 py-4 rounded-lg shadow-xl text-white font-medium flex items-center gap-3 min-w-[300px]"
          :class="{
              'bg-green-600 border-l-4 border-green-400': notif.type === 'success',
              'bg-red-600 border-l-4 border-red-400': notif.type === 'error',
              'bg-blue-600 border-l-4 border-blue-400': notif.type === 'info'
          }">

          <i class="fas text-lg"
            :class="{
                'fa-check-circle': notif.type === 'success',
                'fa-times-circle': notif.type === 'error',
                'fa-info-circle': notif.type === 'info'
            }"></i>

          <span x-text="notif.message"></span>

          <button @click="removeNotification(notif.id)" class="ml-auto text-white/70 hover:text-white">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </template>
    </div>
    <div class="katalog-header">
      <h1 style="margin-top: 40px;">Produk Kami</h1>
      <p>Silakan pilih kategori untuk menampilkan produk yang Anda cari</p>

      <div class="search-container">
        <div class="input-with-icon">
          <i class="fas fa-search"></i>
          <input type="text" x-model="searchQuery" placeholder="Cari produk..."
            @input="$event.target.value = $event.target.value.replace(/[^a-zA-Z0-9\s]/g, '')">
        </div>
      </div>
    </div>

    <div class="category-tabs">
      <button class="category-tab" :class="{ 'active': selectedCategory === null }" @click="selectedCategory = null">
        Semua Kategori
      </button>
      <template x-for="cat in categories" :key="cat.id">
        <button class="category-tab" :class="{ 'active': selectedCategory === cat.id }" @click="selectedCategory = cat.id"
          x-text="cat.name"></button>
      </template>
    </div>

    <div class="pagination-controls" x-show="totalPages > 1">
      <span x-show="currentPage > 1">
        <a @click="currentPage = 1" style="color: #888;">&laquo; First</a>
        <a @click="currentPage = currentPage - 1" style="color: #888;">&lt; Prev</a>
      </span>
      <template x-for="page in totalPages" :key="page">
        <a @click="currentPage = page" :class="{ 'active': currentPage === page }" x-text="page"></a>
      </template>
      <span x-show="currentPage < totalPages">
        <a @click="currentPage = currentPage + 1" style="color: #888;">Next &gt;</a>
        <a @click="currentPage = totalPages" style="color: #888;">Last &raquo;</a>
      </span>
    </div>

    <div class="product-grid">
      <template x-for="prod in paginatedProducts" :key="prod.id">
        <div class="product-card">
          <img :src="prod.gambar" :alt="prod.nama_produk" loading="lazy">
          <h3 x-text="prod.nama_produk"></h3>
          <p class="product-price" x-text="prod.harga_format || formatRupiah(prod.harga)"></p>

          <div style="display: flex; gap: 8px; justify-content: center;">
            <button class="specs-button" @click="openSpecsModal(prod)">
              Lihat Spesifikasi
            </button>
            <button class="add-to-cart-btn" @click="addToCart(prod)" :disabled="addingToCart === prod.id"
              :class="{ 'opacity-50 cursor-not-allowed': addingToCart === prod.id }">
              <span x-show="addingToCart !== prod.id">Add to Cart</span>
              <span x-show="addingToCart === prod.id">Adding...</span>
            </button>
          </div>
        </div>
      </template>
    </div>

    <template x-if="filteredProducts.length === 0">
      <div style="text-align: center; color: #ccc; margin: 40px 0; font-size: 1.1rem;">
        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
        <p>Tidak ada produk yang tersedia untuk kategori ini</p>
      </div>
    </template>

    <div x-show="activeProduct" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title" role="dialog" aria-modal="true">

      <div x-show="activeProduct" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" @click="closeModal()">
      </div>

      <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="activeProduct" x-transition:enter="ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
          x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
          x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          class="relative transform overflow-hidden rounded-lg bg-gray-800 border border-gray-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

          <div class="bg-gray-900 px-4 py-3 sm:px-6 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold leading-6 text-white" id="modal-title" x-text="activeProduct?.nama_produk">
            </h3>
            <button @click="closeModal()" class="text-gray-400 hover:text-white">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <div class="px-4 py-5 sm:p-6 max-h-[60vh] overflow-y-auto">
            <div class="flex justify-center mb-4">
              <img :src="activeProduct?.gambar" class="h-32 object-contain bg-white/5 rounded p-2">
            </div>

            <div class="text-center mb-4">
              <span class="text-green-400 font-bold text-xl"
                x-text="activeProduct?.harga_format || formatRupiah(activeProduct?.harga)"></span>
            </div>

            <div x-show="activeProduct && activeProduct.deskripsi">
              <ul class="space-y-2">
                <template x-for="(item, index) in activeProduct?.deskripsi" :key="index">
                  <li
                    class="flex justify-between items-start gap-6 text-sm border-b border-gray-700 pb-3 pt-1 last:border-0">
                    <span class="font-medium text-blue-400 shrink-0 text-left w-1/3" x-text="item.label"></span>
                    <span class="text-gray-300 text-right w-2/3 break-words" x-text="item.value"></span>
                  </li>
                </template>
              </ul>
              <div x-show="typeof activeProduct?.deskripsi === 'string'"
                class="text-gray-300 text-sm leading-relaxed mt-2">
                <p x-text="activeProduct?.deskripsi"></p>
              </div>
            </div>
          </div>

          <div class="bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
            <button type="button" @click="addToCart(activeProduct); closeModal()"
              class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">
              Add to Cart
            </button>
            <button type="button" @click="closeModal()"
              class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:mt-0 sm:w-auto">
              Close
            </button>
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
        currentPage: 1,
        productsPerPage: 12,
        addingToCart: null,

        // === LOGIKA NOTIFIKASI (TOAST) ===
        notifications: [],

        showNotification(message, type = 'success') {
          const id = Date.now(); // ID Unik
          // Push notif baru ke array
          this.notifications.push({
            id,
            message,
            type
          });

          // Hapus otomatis setelah 3 detik
          setTimeout(() => {
            this.removeNotification(id);
          }, 3000);
        },

        removeNotification(id) {
          this.notifications = this.notifications.filter(n => n.id !== id);
        },
        // =================================

        formatRupiah(angka) {
          return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
          }).format(angka);
        },

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
          const end = start + this.productsPerPage;
          return this.filteredProducts.slice(start, end);
        },

        openSpecsModal(product) {
          this.activeProduct = product;
          document.body.style.overflow = 'hidden';
        },

        closeModal() {
          this.activeProduct = null;
          document.body.style.overflow = 'auto';
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
              // PANGGIL TOAST DISINI
              this.showNotification('Produk berhasil ditambahkan ke keranjang', 'success');
            } else {
              this.showNotification('Gagal menambahkan produk', 'error');
            }
          } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('Terjadi kesalahan koneksi', 'error');
          } finally {
            this.addingToCart = null;
          }
        },

        async init() {
          try {
            this.activeProduct = null;
            const res = await fetch('/produk/json');
            if (!res.ok) throw new Error('Gagal memuat data');
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
            console.error('Error loading produk:', err);
          }
        }
      }
    }
  </script>
@endsection
