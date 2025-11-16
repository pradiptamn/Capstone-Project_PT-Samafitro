@extends('layouts.default')
@section('content')
  <!-- Font Awesome untuk ikon search -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css  ">

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js  " defer></script>

  <style>
    /* ====== RESET DAN GLOBAL ====== */
    * {
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background: radial-gradient(circle at 50% 30%, #444 0%, #222 100%);
      min-height: 100vh;
      color: white;
      line-height: 1.6;
    }

    /* ====== HEADER DAN NAV ====== */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
      padding: 8px 5vw;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #333;
    }

    .logo {
      max-height: 50px;
      margin-left: 0;
    }

    /* Desktop nav */
    @media screen and (min-width: 1025px) {
      .nav-wrapper {
        display: flex !important;
        position: static !important;
        max-height: none !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        flex-direction: row !important;
        align-items: center !important;
        background: none !important;
        box-shadow: none !important;
        padding: 0 0 0 40px !important;
        margin-left: 30px !important;
        gap: 0 !important;
      }

      .nav-left {
        display: flex !important;
        flex-direction: row !important;
        gap: 40px !important;
        padding-left: 0 !important;
        align-items: center !important;
      }

      .nav-right {
        display: flex !important;
        flex-direction: row !important;
        gap: 16px !important;
        align-items: center !important;
        padding-left: 0 !important;
      }

      .nav-left a,
      .nav-right a {
        width: auto !important;
        padding: 8px 18px !important;
        border-radius: 8px !important;
        border-bottom: none !important;
        font-size: 0.95rem !important;
        display: inline-block !important;
      }
    }

    /* Mobile nav */
    @media screen and (max-width: 1024px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        padding: 6px 1vw;
      }

      .nav-wrapper {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        margin-left: 0;
        padding-left: 0;
        gap: 6px;
      }

      .nav-left,
      .nav-right {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding-left: 10;
      }

      .logo {
        margin-left: 0;
        margin-bottom: 6px;
      }

      nav a {
        font-size: 0.75rem;
        padding: 4px 6px;
        border-radius: 4px;
      }
    }

    /* ====== KATALOG HEADER & SEARCH ====== */
    .katalog-header {
      text-align: center;
      margin-top: 80px;
      margin-bottom: 12px;
    }

    .katalog-header h1 {
      font-size: 2.2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 6px;
      letter-spacing: 1px;
    }

    .katalog-header p {
      color: #ccc;
      font-size: 1.1rem;
      margin-bottom: 12px;
    }

    /* Search Container - Responsive & Ikon Tetap di Dalam */
    .search-container {
      margin: 12px auto;
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
      /* Ruang untuk ikon */
      border-radius: 8px;
      border: 1px solid #444;
      background: #333;
      color: white;
      font-size: 16px;
      outline: none;
      box-sizing: border-box;
      transition: border-color 0.2s;
      height: auto;
    }

    .input-with-icon input:focus {
      border-color: #555;
      background: #3a3a3a;
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    }

    /* Responsif: Search di mobile */
    @media screen and (max-width: 600px) {
      .search-container {
        padding: 10 20px;
        margin: 10px 0;
      }

      .input-with-icon input {
        font-size: 14px;
        padding: 12px 12px 12px 36px;
        height: 44px;
        margin-top: 16px;
      }

      .input-with-icon i {
        left: 12px;
        font-size: 1rem;
        margin-top: 8px;
      }
    }

    /* ====== KATALOG CONTAINER (Wajib Ada) ====== */
    .katalog-container {
      display: flex;
      justify-content: center;
      width: 100%;
      padding: 0 16px 40px 16px;
      /* Added bottom padding */
      box-sizing: border-box;
    }

    /* ====== KATALOG GRID (Perbaiki agar tidak overflow) ====== */
    .katalog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 16px;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 16px;
      box-sizing: border-box;
    }

    .katalog-card {
      max-width: 300px;
      /* Atur lebar maksimal */
      background: rgba(255, 255, 255, 0.08);
      border-radius: 8px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 18px 6px 14px 6px;
      transition: box-shadow 0.2s, transform 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .katalog-card:hover {
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
      transform: translateY(-4px) scale(1.03);
    }

    .katalog-card img {
      width: 200px;
      height: 80px;
      object-fit: contain;
      margin-bottom: 18px;
      background: none;
    }

    .katalog-card h3 {
      color: #fff;
      font-size: 1.08rem;
      font-weight: 500;
      margin: 0;
      letter-spacing: 0.5px;
    }

    .katalog-card p {
      color: #ccc;
      font-size: 0.95em;
      margin-top: 8px;
      text-align: justify;
    }

    /* Responsif Grid */
    @media screen and (max-width: 900px) {
      .katalog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
        max-width: 98vw;
      }

      .produk-tab-content {
        padding: 18px 0 24px 0;
        min-height: 300px;
      }

      .katalog-card img {
        width: 90px;
        height: 60px;
      }
    }

    @media screen and (max-width: 600px) {
      .produk-tab-content {
        padding: 18px 0 24px 0;
        min-height: 300px;
      }

      /* Tambahkan class baru untuk container produk */
      .produk-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* 2 kolom per baris */
        gap: 16px;
        /* Jarak antar item */
      }

      .produk-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        color: #333;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .katalog-header {
        margin-top: 69px;
      }

      .katalog-header h1 {
        font-size: 1.3rem;
      }

      .katalog-header p {
        font-size: 0.80rem;
      }

      /* Ubah grid agar lebih responsif */
      .katalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        /* Minimal 150px per kolom */
        gap: 14px;
        margin: 18px auto 24px auto;
        padding: 0 4vw;
        overflow-x: auto;
        /* Scroll horizontal jika diperlukan */
      }

      .katalog-card {
        padding: 18px 6px 14px 6px;
        word-break: break-word;
        /* Mencegah teks terpotong */

      }

      .katalog-card img {
        width: 100%;
        /* Pastikan gambar selalu proporsional */
        max-width: 200px;
        /* Batas maksimum lebar gambar */
        height: auto;
        /* Biarkan tinggi otomatis */
        object-fit: cover;
        /* Gambar tetap proporsional */
        margin-bottom: 10px;
      }

      .katalog-card h3 {
        font-size: 0.98rem;
        white-space: normal;
        /* Memastikan judul tidak terpotong */
        overflow: hidden;
        /* Menghindari teks keluar dari batas */
        text-overflow: ellipsis;
        /* Menambahkan "..." jika teks terpotong */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Maksimal 2 baris teks */
        -webkit-box-orient: vertical;
      }

      .katalog-card button {
        font-size: 0.85rem;
        /* Perkecil tombol */
        padding: 6px 10px;
        /* Padding lebih kecil */
      }
    }



    /* ====== TAB LAYOUT ====== */
    .produk-tab-grid {
      display: grid;
      grid-template-columns: 1fr 3fr;
      gap: 0;
      max-width: 1200px;
      margin: 40px auto 0 auto;
      background: rgba(0, 0, 0, 0.10);
      border-radius: 18px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.10);
      min-height: 500px;
    }

    .produk-tab-sidebar {
      background: rgba(30, 30, 30, 0.98);
      border-radius: 18px 0 0 18px;
      padding: 32px 0;
      display: flex;
      flex-direction: column;
      gap: 6px;
      min-width: 180px;
      max-width: 260px;
    }

    .produk-tab-item {
      padding: 14px 24px;
      cursor: pointer;
      color: #fff;
      font-size: 1.08rem;
      border: none;
      background: none;
      border-left: 4px solid transparent;
      transition: background 0.18s, color 0.18s, border-color 0.18s;
      text-align: left;
    }

    .produk-tab-item.active,
    .produk-tab-item:hover {
      background: #fff;
      color: #222;
      font-weight: bold;
      border-left: 4px solid #222;
    }

    .produk-tab-content {
      padding: 32px 24px 40px 24px;
      /* Added bottom padding */
      min-height: 500px;
    }

    /* Mobile: Tab jadi horizontal scroll */
    @media screen and (max-width: 900px) {
      .produk-tab-grid {
        grid-template-columns: 1fr;
        border-radius: 12px;
        max-width: screen;
        box-sizing: border-box;
      }

      .produk-tab-sidebar {
        flex-direction: row;
        border-radius: 12px 12px 0 0;
        min-width: 0;
        max-width: none;
        padding: 0;
        overflow-x: auto;
        gap: 0;
        scrollbar-width: thin;
        -ms-overflow-style: none;
      }

      .produk-tab-sidebar::-webkit-scrollbar {
        display: none;
      }

      .produk-tab-item {
        padding: 12px 18px;
        border-left: none;
        border-top: 4px solid transparent;
        text-align: center;
        min-width: 120px;
      }

      .produk-tab-mobile-dropdown {
        display: none;
        width: 100vw;
        overflow: hidden;
        /* cegah elemen melebar keluar */
        border-radius: 12px 12px 0 0;
        box-sizing: border-box;
      }

      .produk-tab-item.active,
      .produk-tab-item:hover {
        background: #fff;
        color: #222;
        border-top: 4px solid #222;
      }

      .produk-tab-content {
        padding: 18px 0vw 40px 0vw;
        /* Added bottom padding */
        min-height: 300px;
        max-width: fit-content;
      }
    }

    /* ====== MOBILE DROPDOWN (fallback) ====== */
    .produk-tab-mobile-dropdown {
      display: none;
      width: 100%;
      max-width: 100%;
      overflow: hidden;
      /* cegah elemen melebar keluar */
      padding: 18px 12px 12px 12px;
      border-radius: 12px 12px 0 0;
      box-sizing: border-box;
    }

    .produk-tab-select {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #444;
      font-size: 13px;
      background: #222;
      color: #fff;
      box-sizing: border-box;

      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .produk-tab-mobile-dropdown {
      position: relative;
      width: 100%;
    }

    .produk-tab-select svg {
      width: 10px !important;
      height: 10px !important;
      min-width: 10px;
      min-height: 10px;
      flex-shrink: 0;
    }

    /* Card Container */
    .dropdown-card {
      background: #2a2a2a;
      /* warna card */
      border-radius: 10px;
      padding: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      display: inline-block;
    }

    /* Styling Dropdown */
    .produk-tab-select {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background: transparent;
      border: none;
      color: white;
      font-size: 14px;
      padding: 8px 48px 8px 24px;
      /* kanan & kiri diperbesar */
      border-radius: 8px;
      width: 100%;
      cursor: pointer;
      position: center;
    }

    /* Panah kecil */
    .produk-tab-select {
      background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='8' viewBox='0 0 24 24' width='8' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
      background-repeat: no-repeat;
      background-position: right 8px center;
      background-size: 20px;
      /* ukuran panah */
    }

    select {
      width: 250px;
      /* samakan dengan ukuran dropdown */
      padding: 8px;
      background-color: #444;
      /* abu-abu tua */
      color: white;
      /* teks putih */
      border: 1px solid #888;
      border-radius: 4px;
      appearance: none;
      /* hilangkan style default browser */
    }

    select option {
      background-color: #444;
      /* abu-abu tua */
      color: white;
      /* teks putih */
      font-size: 10px;
      /* ukuran font lebih kecil */
      padding: 4px 4px;
      /* background terkesan lebih kecil */
    }


    select:focus {
      outline: none;
      border-color: #ffffffff;
      /* highlight saat focus */
    }




    @media screen and (max-width: 900px) {
      .produk-tab-mobile-dropdown {
        display: block !important;
      }

      .produk-tab-sidebar {
        display: none;
      }
    }

    /* ====== OVERLAY (Modal) ====== */
    [x-cloak] {
      display: none !important;
    }


    .brochure-buttons {
      display: flex;
      flex-direction: row;
      gap: 16px;
      max-width: 430px;
      margin: 2px auto;
      padding: 20px;
      text-align: center;
    }

    .brochure-btn {
      display: block;
      padding: 5px 15px;
      font-size: 0.70rem;
      font-weight: 600;
      text-decoration: none;
      border-radius: 10px;
      text-align: center;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .brochure-btn.primary {
      background: #595757ff;
      /* Warna hijau WhatsApp */
      color: white;
    }

    .brochure-btn.primary:hover {
      background: #595757ff;
      transform: translateY(-2px);
      box-shadow: 0 5px 14px rgba(0, 0, 0, 0.15);
    }

    .brochure-btn.secondary {
      background: #595757ff;
      /* Biru muda profesional */
      color: white;
    }

    .brochure-btn.secondary:hover {
      background: #595757ff;
      transform: translateY(-2px);
      box-shadow: 0 5px 14px rgba(0, 0, 0, 0.15);
    }

    /* Responsive */
    @media screen and (max-width: 600px) {
      .brochure-buttons {
        padding: 6px;
      }

      .brochure-btn {
        font-size: 0.68rem;
        padding: 14px 16px;
      }
    }
  </style>



  <div x-data="produkPage()" x-init="init()" class="container" x-cloak
    style="padding-bottom: 60px; max-width: 1200px; margin: 0 auto;">
    <div class="katalog-header">
      <h1>Produk Kami</h1>
      <p>Silakan pilih kategori untuk menampilkan produk yang Anda cari</p>
      <div class="brochure-buttons">
        <a href="https://drive.google.com/drive/folders/1E-vAQE2o_jvOZNeZ5fYFKNZ3UWREHcPp" target="_blank" rel="noopener"
          class="brochure-btn primary">
          Lihat Brosur Digital Percetakan Dan Garmen
        </a>
        <a href="https://drive.google.com/drive/folders/1_4mMGlnl0xpX3iw2158-PtWkoHqnuENS" target="_blank" rel="noopener"
          class="brochure-btn secondary">
          Lihat Brosur Digital Copier Canon, Laptop Axioo & POS
        </a>
      </div>
      <!-- Search Bar -->
      <div class="search-container">
        <div class="input-with-icon">
          <i class="fas fa-search"></i>
          <input type="text" x-model="searchQuery" placeholder="Cari produk..."
            @input="$event.target.value = $event.target.value.replace(/[^a-zA-Z0-9\s]/g, '')">
        </div>
      </div>
    </div>
    <div class="produk-tab-grid" style="margin: 0 auto; max-width: 1200px;">
      <!-- Mobile Dropdown -->
      <div class="produk-tab-mobile-dropdown" x-show="isMobile">
        <div class="dropdown-card">
          <select x-model="selectedCategory" class="produk-tab-select">
            <template x-for="cat in categories" :key="cat.id">
              <option :value="cat.id" x-text="cat.name"></option>
            </template>
          </select>
        </div>
      </div>

      <!-- Sidebar (Desktop) -->
      <div class="produk-tab-sidebar" x-show="!isMobile">
        <template x-for="cat in categories" :key="cat.id">
          <div class="produk-tab-item" :class="{ 'active': selectedCategory === cat.id }"
            @click="selectedCategory = cat.id" x-text="cat.name"></div>
        </template>
      </div>

      <!-- Content -->
      <div class="produk-tab-content">
        <div class="pagination-controls" style="text-align: center; margin: 16px 0; color: #ccc;" x-show="totalPages > 1">
          <span x-show="currentPage > 1">
            <a @click="currentPage = 1" style="color: #888; margin: 0 6px; cursor: pointer;">&laquo; First</a>
            <a @click="currentPage = currentPage - 1" style="color: #888; margin: 0 6px; cursor: pointer;">
              < Prev</a>
          </span>

          <template x-for="page in totalPages" :key="page">
            <a @click="currentPage = page"
              :style="currentPage === page ? 'background: #7377e3ff; color: white; font-weight: bold;' :
                  'color: white; background: #555;'"
              style="display: inline-block; margin: 0 4px; padding: 6px 10px; border-radius: 6px; cursor: pointer;"
              x-text="page"></a>
          </template>

          <span x-show="currentPage < totalPages">
            <a @click="currentPage = currentPage + 1" style="color: #888; margin: 0 6px; cursor: pointer;">Next ></a>
            <a @click="currentPage = totalPages" style="color: #888; margin: 0 6px; cursor: pointer;">Last &raquo;</a>
          </span>
        </div>

        <!-- Pesan jika tidak ada produk -->
        <template x-if="filteredProducts.length === 0">
          <div style="text-align:center; color:#ccc; margin-top:40px;">
            Produk Belum Tersedia
          </div>
        </template>

        <!-- Grid Produk -->
        <div class="katalog-container">
          <div class="katalog-grid">
            <template x-for="prod in paginatedProducts" :key="prod.id">
              <div class="katalog-card">
                <img :src="prod.gambar" :alt="prod.nama_produk" loading="lazy">
                <h3 x-text="prod.nama_produk"></h3>
                <button class="btn-lihat-spesifikasi" @click="openOverlay(prod)"
                  style="margin-top: 12px; padding: 6px 12px; background: #666060ff; color: white; border: none; border-radius: 6px; font-size: 0.85rem; cursor: pointer;">
                  Lihat Spesifikasi
                </button>
              </div>
            </template>
          </div>
        </div>

      </div>


      <!-- Overlay Spesifikasi Produk (Hanya Deskripsi) -->
      <div class="produk-overlay" x-show="activeProduct" @click.self="activeProduct = null"
        style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px;"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
        <div class="overlay-content"
          style="
            background: white; 
            max-width: 500px; 
            width: 100%; 
            max-height: 80vh; 
            overflow-y: auto; 
            padding: 24px; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
          <!-- Tombol Close -->
          <button @click="activeProduct = null"
            style="position: absolute; top: 12px; right: 12px; background: #f0f0f0; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 16px; cursor: pointer; color: #333;">
            ×
          </button>

          <!-- Hanya Deskripsi Produk -->
          <div style="color: #333; font-size: 1rem; padding: 10px 0;">
            <ul style="list-style: none; padding: 0; margin: 0;">
              <template x-for="(item, index) in activeProduct?.deskripsi" :key="index">
                <li
                  style="
                    display: flex; 
                    justify-content: space-between; 
                    padding: 8px 0; 
                    border-bottom: 1px solid #ccc;
                    gap: 16px;">
                  <!-- Label -->
                  <span style="font-weight: 500; flex: 1; min-width: 150px;" x-text="item.label"></span>

                  <!-- Value rata kanan -->
                  <span style="flex: 1; text-align: right;" x-text="item.value"></span>
                </li>
              </template>
            </ul>
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
        isMobile: window.innerWidth <= 900,
        activeProduct: null,

        // Pagination
        currentPage: 1,
        productsPerPage: 10,

        // Ambil produk yang sudah difilter
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

        // Hitung total halaman
        get totalPages() {
          return Math.ceil(this.filteredProducts.length / this.productsPerPage) || 1;
        },

        // Produk yang ditampilkan sesuai halaman
        get paginatedProducts() {
          const start = (this.currentPage - 1) * this.productsPerPage;
          const end = start + this.productsPerPage;
          return this.filteredProducts.slice(start, end);
        },
        openOverlay(product) {
          this.activeProduct = product;
        },

        async init() {
          try {
            const res = await fetch('/produk/json');
            if (!res.ok) throw new Error('Gagal memuat data');
            const data = await res.json();
            this.categories = data.categories || [];
            this.products = data.products || [];

            if (this.categories.length && !this.selectedCategory) {
              this.selectedCategory = this.categories[0].id;
            }

            const updateMobile = () => {
              this.isMobile = window.innerWidth <= 900;
            };
            window.addEventListener('resize', updateMobile);
          } catch (err) {
            console.error('Error loading produk:', err);
          }
        }
      }
    }
  </script>
@endsection
