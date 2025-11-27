@extends('layouts.admin')

@section('title', 'Kelola Produk | Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <main class="flex-1 container mx-auto px-4 py-8">

      <h2 class="text-3xl font-bold text-center mb-8 flex items-center justify-center gap-2">
        <i class="fas fa-box-open text-blue-500"></i> Kelola Produk
      </h2>

      <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden mb-10">
        <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
          <h3 class="text-xl font-semibold text-white">Tambah Produk Baru</h3>
        </div>

        <div class="p-6">
          <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-300">Nama Produk</label>
                <input type="text" name="nama_produk"
                  class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                  required placeholder="Contoh: Canon imageRUNNER">
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-300">Kategori</label>
                <select name="kategori_id"
                  class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                  required>
                  <option value="">Pilih Kategori</option>
                  @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-300">Harga (Rp)</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="text-gray-400 font-bold">Rp</span>
                  </div>
                  <input type="number" name="harga"
                    class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2.5"
                    placeholder="0" min="0" required>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <label class="block mb-2 text-sm font-medium text-gray-300">Spesifikasi Produk</label>
              <div id="specifications-container" class="space-y-3">
                <div class="specification-item flex flex-col md:flex-row gap-3">
                  <input type="text" name="spec_labels[]"
                    class="flex-1 bg-gray-800 border border-gray-600 text-white text-sm rounded-lg p-2.5"
                    placeholder="Label (Contoh: Kecepatan)" required>
                  <input type="text" name="spec_values[]"
                    class="flex-1 bg-gray-800 border border-gray-600 text-white text-sm rounded-lg p-2.5"
                    placeholder="Nilai (Contoh: 45 ppm)" required>
                  <button type="button"
                    class="btn-remove-spec bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" id="add-specification"
                class="mt-3 text-sm bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Spesifikasi Lain
              </button>
            </div>

            <div class="mb-6">
              <label class="block mb-2 text-sm font-medium text-gray-300">Upload Gambar (JPG, PNG)</label>
              <input type="file" name="gambar" id="input-gambar"
                class="block w-full text-sm text-gray-400 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 focus:outline-none"
                accept="image/png, image/jpeg, image/jpg" required>

              <div id="image-preview"
                class="mt-4 w-full h-48 border-2 border-dashed border-gray-600 rounded-lg flex items-center justify-center bg-gray-800 text-gray-500">
                <span>Preview gambar akan muncul di sini</span>
              </div>
            </div>

            <div class="pt-4 border-t border-gray-700">
              <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                <i class="fas fa-save mr-2"></i> Simpan Produk
              </button>
            </div>
          </form>
        </div>
      </div>


      <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
        <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 mb-4">
          <h3 class="text-xl font-semibold text-white">Daftar Produk</h3>
        </div>

        <div class="px-6 pb-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div class="flex flex-wrap gap-2" id="categoryTabs">
              <button
                class="category-tab active px-4 py-2 rounded-full text-sm font-medium transition bg-blue-600 text-white shadow-lg"
                data-category-id="all">Semua</button>
              @foreach ($categories as $cat)
                <button
                  class="category-tab px-4 py-2 rounded-full text-sm font-medium transition bg-gray-700 text-gray-300 hover:bg-gray-600"
                  data-category-id="{{ $cat->id }}">{{ $cat->name }}</button>
              @endforeach
            </div>

            <div class="relative w-full md:w-1/3">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input type="text" id="searchProducts"
                class="w-full p-2.5 pl-10 text-sm text-white bg-gray-800 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                placeholder="Cari produk...">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productGrid">
            @foreach ($products as $p)
              <div
                class="product-item bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col"
                data-category-id="{{ $p->kategori_id }}" data-name="{{ strtolower($p->nama_produk) }}">

                <div class="h-40 w-full bg-white p-4 flex items-center justify-center">
                  <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}"
                    class="max-h-full max-w-full object-contain">
                </div>

                <div class="p-4 flex-1 flex flex-col">
                  <h4 class="text-lg font-bold text-white mb-1 line-clamp-2">{{ $p->nama_produk }}</h4>
                  <span class="text-xs text-gray-400 mb-2 block">{{ $p->category?->name }}</span>

                  <p class="text-blue-400 font-bold mb-4">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>

                  <div class="mt-auto flex gap-2">
                    <button onclick="editProduct({{ json_encode($p) }})"
                      class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white py-2 rounded-lg text-sm font-medium transition">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="{{ route('admin.produk.delete', $p->id) }}" method="POST" class="flex-1">
                      @csrf @method('DELETE')
                      <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded-lg text-sm font-medium transition"
                        onclick="return confirm('Hapus produk ini?')">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach

            <div id="noProductsMessage" class="hidden col-span-full text-center py-10 text-gray-500">
              <i class="fas fa-box-open text-4xl mb-3"></i>
              <p>Tidak ada produk yang ditemukan.</p>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>

  <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      <div
        class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-700">

        <div class="bg-gray-900 px-4 py-3 border-b border-gray-700 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-white">Edit Produk</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-white">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <div class="p-6 max-h-[80vh] overflow-y-auto">
          <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="editId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-300">Nama Produk</label>
                <input type="text" name="nama_produk" id="editNama"
                  class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg p-2.5" required>
              </div>
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-300">Kategori</label>
                <select name="kategori_id" id="editKategori"
                  class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg p-2.5" required>
                  @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-300">Harga (Rp)</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="text-gray-400 font-bold">Rp</span>
                  </div>
                  <input type="number" name="harga" id="editHarga"
                    class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2.5"
                    required>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label class="block mb-2 text-sm font-medium text-gray-300">Spesifikasi</label>
              <div id="edit-specifications-container" class="space-y-3">
              </div>
              <button type="button" id="add-edit-specification"
                class="mt-2 text-xs bg-yellow-600 hover:bg-yellow-500 text-white px-3 py-1.5 rounded transition">
                + Tambah Spesifikasi
              </button>
            </div>

            <div class="mb-6">
              <label class="block mb-2 text-sm font-medium text-gray-300">Ganti Gambar</label>
              <input type="file" name="gambar" id="edit-gambar-input"
                class="block w-full text-sm text-gray-400 border border-gray-600 rounded-lg cursor-pointer bg-gray-700 focus:outline-none"
                accept="image/*">
              <div id="edit-image-preview"
                class="mt-3 w-full h-40 border border-gray-600 rounded bg-gray-900 flex items-center justify-center overflow-hidden">
                <span class="text-gray-500 text-sm">Preview gambar</span>
              </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-700">
              <button type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition">Simpan
                Perubahan</button>
              <button type="button" onclick="closeModal()"
                class="flex-1 bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // 1. Preview Gambar (Tambah Produk)
    document.getElementById('input-gambar').addEventListener('change', function(e) {
      const preview = document.getElementById('image-preview');
      preview.innerHTML = '';
      if (e.target.files && e.target.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(e.target.files[0]);
        img.className = 'h-full object-contain';
        preview.appendChild(img);
      } else {
        preview.innerHTML = '<span>Preview gambar akan muncul di sini</span>';
      }
    });

    // 2. Preview Gambar (Edit Produk)
    document.getElementById('edit-gambar-input').addEventListener('change', function(e) {
      const preview = document.getElementById('edit-image-preview');
      preview.innerHTML = '';
      if (e.target.files && e.target.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(e.target.files[0]);
        img.className = 'h-full object-contain';
        preview.appendChild(img);
      }
    });

    // 3. Dynamic Spesifikasi (Tambah)
    document.getElementById('add-specification').addEventListener('click', function() {
      const container = document.getElementById('specifications-container');
      const div = document.createElement('div');
      div.className = 'specification-item flex flex-col md:flex-row gap-3';
      div.innerHTML = `
            <input type="text" name="spec_labels[]" class="flex-1 bg-gray-800 border border-gray-600 text-white text-sm rounded-lg p-2.5" placeholder="Label" required>
            <input type="text" name="spec_values[]" class="flex-1 bg-gray-800 border border-gray-600 text-white text-sm rounded-lg p-2.5" placeholder="Nilai" required>
            <button type="button" class="btn-remove-spec bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition"><i class="fas fa-trash"></i></button>
        `;
      container.appendChild(div);
    });

    // 4. Event Delegation untuk Hapus Spesifikasi
    document.addEventListener('click', function(e) {
      if (e.target.closest('.btn-remove-spec')) {
        e.target.closest('.specification-item').remove();
      }
    });

    // 5. Edit Modal Logic
    function editProduct(product) {
      document.getElementById('editId').value = product.id;
      document.getElementById('editNama').value = product.nama_produk;
      document.getElementById('editKategori').value = product.kategori_id;

      // ISI NILAI HARGA DI MODAL
      document.getElementById('editHarga').value = product.harga;

      document.getElementById('editForm').action = '/admin/produk/update/' + product.id;

      // Preview Gambar Lama
      const editPreview = document.getElementById('edit-image-preview');
      editPreview.innerHTML = '';
      if (product.gambar) {
        const img = document.createElement('img');
        img.src = '/storage/' + product.gambar;
        img.className = 'h-full object-contain';
        editPreview.appendChild(img);
      } else {
        editPreview.innerHTML = '<span class="text-gray-500">Tidak ada gambar</span>';
      }

      // Load Spesifikasi
      const specsContainer = document.getElementById('edit-specifications-container');
      specsContainer.innerHTML = '';

      if (product.deskripsi && Array.isArray(product.deskripsi)) {
        product.deskripsi.forEach(item => {
          addEditSpecRow(item.label, item.value);
        });
      }
      if (specsContainer.children.length === 0) {
        addEditSpecRow('', '');
      }

      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    function addEditSpecRow(label, value) {
      const container = document.getElementById('edit-specifications-container');
      const div = document.createElement('div');
      div.className = 'specification-item flex gap-2';
      div.innerHTML = `
            <input type="text" name="spec_labels[]" value="${label}" class="flex-1 bg-gray-700 border border-gray-600 text-white text-sm rounded p-2" placeholder="Label">
            <input type="text" name="spec_values[]" value="${value}" class="flex-1 bg-gray-700 border border-gray-600 text-white text-sm rounded p-2" placeholder="Nilai">
            <button type="button" class="btn-remove-spec bg-red-600 text-white px-2 rounded"><i class="fas fa-minus"></i></button>
        `;
      container.appendChild(div);
    }

    document.getElementById('add-edit-specification').onclick = function() {
      addEditSpecRow('', '');
    };

    // 6. Filter Kategori & Search
    const tabs = document.querySelectorAll('.category-tab');
    const items = document.querySelectorAll('.product-item');
    const searchInput = document.getElementById('searchProducts');
    const noMsg = document.getElementById('noProductsMessage');

    function filter() {
      const activeTab = document.querySelector('.category-tab.active');
      const catId = activeTab ? activeTab.dataset.categoryId : 'all';
      const query = searchInput.value.toLowerCase();
      let count = 0;

      items.forEach(item => {
        const itemCat = item.dataset.categoryId;
        const itemName = item.dataset.name;
        const matchCat = catId === 'all' || itemCat === catId;
        const matchSearch = itemName.includes(query);

        if (matchCat && matchSearch) {
          item.style.display = 'flex';
          count++;
        } else {
          item.style.display = 'none';
        }
      });

      noMsg.style.display = count === 0 ? 'block' : 'none';
    }

    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        tabs.forEach(t => {
          t.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg');
          t.classList.add('bg-gray-700', 'text-gray-300');
        });
        this.classList.remove('bg-gray-700', 'text-gray-300');
        this.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg');
        filter();
      });
    });

    searchInput.addEventListener('input', filter);
  </script>
@endsection
