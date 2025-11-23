@extends('layouts.admin')

@section('title', 'Kelola Produk | Samafitro')

@section('content')
  <style>
    body {
      background-color: #1e1e1ee6;
      /* Warna gelap */
      margin: 0;
      padding: 0;
      color: white;
      font-family: 'Arial', sans-serif;
    }

    /* Styling untuk halaman admin */
    .admin-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 20px;
      color: white;
    }

    .admin-card {
      background: #1e1e1ee6;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #777777ff;
    }

    .form-control {
      width: 100%;
      padding: 4px;
      border-radius: 6px;
      border: 1px solid #444;
      background: #333;
      color: white;
      font-size: 14px;
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .btn {
      padding: 10px 16px;
      border-radius: 6px;
      border: none;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary {
      background: #d7d7d7ff;
      color: #212529;
    }

    .btn-primary:hover {
      background: #0069d9;
    }

    .btn-warning {
      background: #d7d7d7ff;
      color: #212529;
    }

    .btn-danger {
      background: #ce2133ff;
      color: white;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }

    .product-item {
      background: rgba(40, 40, 40, 0.8);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-image {
      width: 100%;
      height: 150px;
      object-fit: contain;
      background: white;
      padding: 10px;
    }

    .product-info {
      padding: 15px;
    }

    .product-name {
      font-weight: 600;
      margin-bottom: 5px;
      color: #fff;
    }

    .product-category {
      font-size: 0.85rem;
      color: #aaa;
      margin-bottom: 10px;
    }

    .product-actions {
      display: flex;
      gap: 8px;
      margin-top: 10px;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      padding: 24px;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
    }

    .close-modal {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #666;
    }

    .image-preview {
      width: 100%;
      height: 200px;
      background: #f0f0f0;
      border: 1px dashed #ccc;
      border-radius: 8px;
      margin: 10px 0;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .image-preview img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .specification-item {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
      align-items: center;
    }

    .specification-item input {
      flex: 1;
    }

    .specification-actions {
      display: flex;
      gap: 5px;
    }

    /* Tambahkan styling untuk kategori dan pencarian */
    .category-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 15px;
      padding-bottom: 8px;
      border-bottom: 1px solid #444;
      overflow-x: auto;
      scrollbar-width: thin;
    }

    .category-tab {
      padding: 8px 16px;
      border-radius: 20px;
      background: #444;
      color: #ccc;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
      white-space: nowrap;
      font-size: 0.9rem;
    }

    .category-tab.active {
      background: #acacacff;
      color: white;
      font-weight: 500;
      box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
    }

    .category-tab:hover:not(.active) {
      background: #555;
      color: white;
    }

    .search-container {
      position: relative;
      margin-bottom: 15px;
    }

    .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
      pointer-events: none;
    }

    .clear-search {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #888;
      cursor: pointer;
      display: none;
    }

    .no-products {
      text-align: center;
      padding: 30px;
      color: #aaa;
      grid-column: 1 / -1;
    }

    .category-header {
      display: flex;
      justify-content: center;
      /* Pindahkan semua isi ke tengah */
      align-items: center;
      /* Center vertikal */
      margin: 15px auto;
      /* Atas-bawah 15px, kiri-kanan auto (center) */
      max-width: 1200px;
      /* Opsional: batasi lebar */
      width: 100%;
    }

    .category-badge {
      background: #acacacff;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }


    /* Responsif untuk layar kecil (mobile) */
    @media (max-width: 768px) {
      .admin-container {
        padding: 8px;
        margin: 10px auto;
      }

      .admin-card {
        padding: 15px;
        margin-bottom: 20px;
      }

      /* Form control: pastikan input 100% lebar */
      .form-group label {
        font-size: 0.95rem;
      }

      .form-control {
        font-size: 1rem;
        padding: 3px;
        width: 100%;
      }

      textarea.form-control {
        min-height: 100px;
      }

      /* Tombol aksi produk di satu kolom */
      .product-actions {
        flex-direction: column;
      }

      .product-actions button,
      .product-actions form {
        width: 100%;
        text-align: center;
      }

      .product-actions form button {
        width: 100%;
      }

      /* Grid produk: 1 kolom di mobile */
      .product-grid {
        grid-template-columns: repeat(2, 1fr);
        /* Selalu 2 kolom di mobile */
        gap: 12px;
      }

      .product-item {
        max-width: none;
        width: 100%;
        margin: 0 auto;
      }

      .product-image {
        height: 120px;
        padding: 8px;
      }

      .product-info {
        padding: 12px;
      }

      .product-name {
        font-size: 0.95rem;
      }

      .product-category {
        font-size: 0.8rem;
      }

      /* Modal: pastikan muat di layar kecil */
      .modal-content {
        width: 95%;
        max-width: 100%;
        padding: 15px;
      }

      .close-modal {
        top: 10px;
        right: 10px;
        font-size: 20px;
      }

      /* Spesifikasi: tampilkan vertikal di mobile */
      .specification-item {
        flex-direction: column;
        gap: 8px;
      }

      .specification-item input {
        width: 100%;
      }

      .specification-actions {
        align-self: flex-start;
      }

      /* Search dan kategori tab: scrollable */
      .category-tabs {
        justify-content: start;
        padding: 5px 0;
      }

      .category-tab {
        font-size: 0.85rem;
        padding: 6px 12px;
      }

      /* Header pencarian: full width */
      .search-container {
        width: 100%;
      }

      .search-container input {
        font-size: 1rem;
      }

      /* Tombol tambah produk: lebih kecil di mobile */
      .btn {
        font-size: 0.95rem;
        padding: 8px 12px;
      }

      .btn-primary,
      .btn-warning,
      .btn-danger {
        font-size: 0.9rem;
      }

      /* Hindari overflow horizontal */
      body,
      html {
        overflow-x: hidden;
      }
    }

    .product-image {
      width: 100%;
      height: auto;
      max-height: 150px;
      object-fit: contain;
      background: white;
      padding: 10px;
    }
  </style>



  <div class="admin-container">
    <h2 style="text-align: center; margin-bottom: 30px; color: #6e6e6eff;"> </h2>

    <!-- Form Tambah Produk -->
    <div class="admin-card">
      <h3 style="margin-bottom: 15px; color: #fff;">Tambah Produk Baru</h3>
      <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label>Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori_id" class="form-control" required>
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Spesifikasi Produk -->
        <div class="form-group">
          <label>Spesifikasi Produk</label>
          <div id="specifications-container">
            <div class="specification-item">
              <input type="text" name="spec_labels[]" class="form-control"
                placeholder="Spesifikasi (Contoh: Kecepatan Cetak)" required>
              <input type="text" name="spec_values[]" class="form-control"
                placeholder="Detail (Contoh : 4.600 lembar/jam EPM)" required>
              <div class="specification-actions">
                <button type="button" class="btn btn-danger remove-spec"
                  style="padding: 5px 10px; min-width: auto;">Hapus</button>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-warning" id="add-specification"
            style="margin-top: 10px; padding: 5px 15px;">+ Tambah Kolom inputan Spesifikasi</button>
        </div>

        <!-- Upload Gambar -->
        <div class="form-group">
          <label>Upload Gambar Produk (PNG, JPG, JPEG)</label>
          <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
          <div class="image-preview" id="image-preview">
            <span style="color: #888;">Preview gambar akan muncul di sini</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Produk</button>
      </form>
    </div>

    <!-- Daftar Produk -->
    <div class="admin-card">
      <h3 style="margin-bottom: 15px; color: #fff;">Daftar Produk</h3>

      <!-- Kategori Tabs -->
      <div class="category-tabs" id="categoryTabs">
        <button class="category-tab active" data-category-id="all">Semua Kategori</button>
        @foreach ($categories as $cat)
          <button class="category-tab" data-category-id="{{ $cat->id }}">{{ $cat->name }}</button>
        @endforeach
      </div>

      <!-- Header dengan Search (Hanya Search) -->
      <div class="category-header">
        <div class="search-container" style="width: 100%;">
          <div class="form-group" style="margin-bottom: 0;">
            <div style="position: relative; width: 100%;">
              <i class="fas fa-search search-icon"></i>
              <input type="text" id="searchProducts" class="form-control" placeholder="Cari semua produk..."
                style=" width: 100%;">
              <button class="clear-search" id="clearSearch" style="display: none;">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Product Grid -->
      <div class="product-grid" id="productGrid">
        @foreach ($products as $p)
          <div class="product-item" data-category-id="{{ $p->kategori_id }}" data-name="{{ strtolower($p->nama_produk) }}"
            data-category-name="{{ strtolower($p->category?->name) }}">
            <img src="{{ $p->gambar }}" alt="{{ $p->nama_produk }}" class="product-image">
            <div class="product-info">
              <div class="product-name">{{ $p->nama_produk }}</div>
              <div class="product-category">Kategori: {{ $p->category?->name }}</div>
              <div class="product-actions">
                <button onclick="editProduct({{ json_encode($p) }})" class="btn btn-warning">Edit</button>
                <form action="{{ route('admin.produk.delete', $p->id) }}" method="POST" style="flex:1;">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                </form>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Pesan jika tidak ada produk -->
        <div class="no-products" id="noProductsMessage" style="display: none;">
          <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; color: #555;"></i>
          <p>Tidak ada produk dalam kategori ini</p>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Edit -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h3>Edit Produk</h3>
      <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <input type="hidden" name="id" id="editId">

        <div class="form-group">
          <label>Nama Produk</label>
          <input type="text" name="nama_produk" id="editNama" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori_id" id="editKategori" class="form-control" required>
            @foreach ($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Spesifikasi Produk -->
        <div class="form-group">
          <label>Spesifikasi Produk</label>
          <div id="edit-specifications-container">
            <!-- Akan diisi dengan JavaScript -->
          </div>
          <button type="button" class="btn btn-warning" id="add-edit-specification"
            style="margin-top: 10px; padding: 5px 15px;">+ Tambah Spesifikasi</button>
        </div>

        <!-- Upload Gambar -->
        <div class="form-group">
          <label>Upload Gambar Produk (PNG, JPG, JPEG)</label>
          <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg">
          <div class="image-preview" id="edit-image-preview">
            <!-- Preview akan muncul di sini -->
          </div>
          <small style="color: #aaa; display: block; margin-top: 5px;">Biarkan kosong jika tidak ingin mengganti
            gambar</small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Perubahan</button>
          <button type="button" onclick="closeModal()" class="btn"
            style="flex: 1; background: #6c757d; color: white;">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Preview gambar untuk form tambah
    document.querySelector('input[name="gambar"]').addEventListener('change', function(e) {
      const preview = document.getElementById('image-preview');
      preview.innerHTML = '';

      if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          preview.appendChild(img);
        }

        reader.readAsDataURL(e.target.files[0]);
      } else {
        preview.innerHTML = '<span style="color: #888;">Preview gambar akan muncul di sini</span>';
      }
    });

    // Tambah spesifikasi
    document.getElementById('add-specification').addEventListener('click', function() {
      const container = document.getElementById('specifications-container');
      const newItem = document.createElement('div');
      newItem.className = 'specification-item';
      newItem.innerHTML = `
            <input type="text" name="spec_labels[]" class="form-control" placeholder="Spesifikasi (Contoh: Kecepatan Cetak)" required>
            <input type="text" name="spec_values[]" class="form-control" placeholder="Detail (Contoh : 4.600 lembar/jam EPM)" required>
            <div class="specification-actions">
                <button type="button" class="btn btn-danger remove-spec" style="padding: 5px 10px; min-width: auto;">Hapus</button>
            </div>
        `;
      container.appendChild(newItem);
    });

    // Hapus spesifikasi
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('remove-spec')) {
        const item = e.target.closest('.specification-item');
        if (item) {
          item.remove();
        }
      }
    });

    // Fungsi edit produk
    function editProduct(product) {
      document.getElementById('editId').value = product.id;
      document.getElementById('editNama').value = product.nama_produk;
      document.getElementById('editKategori').value = product.kategori_id;

      // Tampilkan preview gambar
      const editPreview = document.getElementById('edit-image-preview');
      editPreview.innerHTML = '';
      if (product.gambar) {
        const img = document.createElement('img');
        img.src = product.gambar;
        editPreview.appendChild(img);
      } else {
        editPreview.innerHTML = '<span style="color: #888;">Tidak ada gambar</span>';
      }

      // Tampilkan spesifikasi
      const specsContainer = document.getElementById('edit-specifications-container');
      specsContainer.innerHTML = '';

      if (product.deskripsi && Array.isArray(product.deskripsi)) {
        product.deskripsi.forEach(function(item, index) {
          const specItem = document.createElement('div');
          specItem.className = 'specification-item';
          specItem.innerHTML = `
                    <input type="text" name="spec_labels[]" class="form-control" placeholder="Label" value="${item.label.replace(/"/g, '&quot;')}" required>
                    <input type="text" name="spec_values[]" class="form-control" placeholder="Nilai" value="${item.value.replace(/"/g, '&quot;')}" required>
                    <div class="specification-actions">
                        <button type="button" class="btn btn-danger remove-spec" style="padding: 5px 10px; min-width: auto;">-</button>
                    </div>
                `;
          specsContainer.appendChild(specItem);
        });
      }

      // Tambahkan event listener untuk tombol tambah spesifikasi di modal edit
      document.getElementById('add-edit-specification').onclick = function() {
        const newItem = document.createElement('div');
        newItem.className = 'specification-item';
        newItem.innerHTML = `
                <input type="text" name="spec_labels[]" class="form-control" placeholder="Label" required>
                <input type="text" name="spec_values[]" class="form-control" placeholder="Nilai" required>
                <div class="specification-actions">
                    <button type="button" class="btn btn-danger remove-spec" style="padding: 5px 10px; min-width: auto;">-</button>
                </div>
            `;
        specsContainer.appendChild(newItem);
      };

      // Set action form
      document.getElementById('editForm').action = '/admin/produk/update/' + product.id;
      document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar konten
    window.onclick = function(event) {
      if (event.target === document.getElementById('editModal')) {
        closeModal();
      }
    }

    // ===== SISTEM KATEGORI DAN PENCARIAN =====
    document.addEventListener('DOMContentLoaded', function() {
      const categoryTabs = document.querySelectorAll('.category-tab');
      const productItems = document.querySelectorAll('.product-item');
      const searchInput = document.getElementById('searchProducts');
      const clearButton = document.getElementById('clearSearch');
      const currentCategoryElement = document.getElementById('currentCategory');
      const noProductsMessage = document.getElementById('noProductsMessage');

      let selectedCategoryId = 'all';
      let currentCategoryName = 'Semua Kategori';

      // Fungsi untuk memperbarui tampilan kategori
      function updateCategoryView() {
        // Update badge kategori
        const count = document.querySelectorAll('.product-item[style*="display: block"]').length;
        currentCategoryElement.textContent = ${currentCategoryName} (${count});
      }

      // Fungsi filter produk
      function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();

        // Tampilkan tombol clear hanya jika ada teks
        clearButton.style.display = searchTerm ? 'block' : 'none';

        let visibleCount = 0;

        productItems.forEach(item => {
          const categoryId = item.dataset.categoryId;
          const name = item.dataset.name;
          const categoryName = item.dataset.categoryName;

          // Tentukan apakah produk harus ditampilkan
          const matchesCategory = selectedCategoryId === 'all' || categoryId === selectedCategoryId;
          const matchesSearch = !searchTerm ||
            name.includes(searchTerm) ||
            categoryName.includes(searchTerm);

          if (matchesCategory && matchesSearch) {
            item.style.display = 'block';
            visibleCount++;
          } else {
            item.style.display = 'none';
          }
        });

        // Tampilkan pesan "tidak ada produk" jika diperlukan
        if (visibleCount === 0 && selectedCategoryId !== 'all') {
          noProductsMessage.style.display = 'block';
        } else {
          noProductsMessage.style.display = 'none';
        }

        updateCategoryView();
      }

      // Event listener untuk tab kategori
      categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Update kelas aktif
          categoryTabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');

          // Simpan ID dan nama kategori yang dipilih
          selectedCategoryId = this.dataset.categoryId;
          currentCategoryName = this.textContent;

          // Reset pencarian saat ganti kategori
          searchInput.value = '';
          filterProducts();
        });
      });

      // Event listener untuk input pencarian
      searchInput.addEventListener('input', filterProducts);

      // Event listener untuk tombol clear
      clearButton.addEventListener('click', function() {
        searchInput.value = '';
        filterProducts();
      });

      // Inisialisasi: tampilkan semua produk saat pertama kali
      filterProducts();
    });
  </script>
  <!-- NOTIFIKASI OVERLAY -->
  @if (session('success') || session('error'))
    <div id="notification-overlay"
      style="
    position: fixed;
    top: 0; left: 0; 
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex; justify-content: center; align-items: center;
    z-index: 999999;
    color: white; font-size: 1rem; 
    text-align: center; padding: 20px;
">
      <div
        style="background: {{ session('success') ? '#28a745' : '#dc3545' }};
                padding: 10px 10px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.5);">
        {{ session('success') ?? session('error') }}
      </div>
    </div>
  @endif

  <!-- SCRIPT UNTUK HILANGKAN OVERLAY SETELAH 3 DETIK -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const overlay = document.getElementById('notification-overlay');
      if (overlay) {
        setTimeout(() => {
          overlay.style.display = 'none';
        }, 3000);
      }
    });
  </script>
@endsection