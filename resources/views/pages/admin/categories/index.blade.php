@extends('layouts.admin')

@section('title', 'Kelola Kategori | Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <main class="flex-1 container mx-auto px-4 py-8">

      {{-- Judul Halaman - Ikon diganti ke fas fa-layer-group --}}
      <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
        <h2 class="text-3xl font-bold flex items-center gap-2">
          <i class="fas fa-layer-group text-blue-500"></i> Kelola Kategori
        </h2>
      </div>

      {{-- 1. Pesan Error Validasi --}}
      @if ($errors->any())
        <div class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg">
          <h4 class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan:</h4>
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- 2. Pesan Sukses --}}
      @if (session('success'))
        <div
          class="mb-6 p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-check-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
      @endif

      {{-- 3. Pesan Error Sistem --}}
      @if (session('error'))
        <div
          class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-times-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Sisi Kiri: Form Tambah Kategori --}}
        <div class="lg:col-span-1">
          <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden sticky top-8">
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
              <h3 class="text-xl font-semibold text-white">Tambah Kategori Baru</h3>
            </div>
            <div class="p-6">
              <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                  <label class="block mb-2 text-sm font-medium text-gray-300">Nama Kategori</label>
                  <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 block p-2.5 outline-none transition focus:border-blue-500"
                    required placeholder="Contoh: Mesin Fotokopi">
                </div>
                <button type="submit"
                  class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                  <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
              </form>
            </div>
          </div>
        </div>

        {{-- Sisi Kanan: Daftar Kategori --}}
        <div class="lg:col-span-2">
          <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
              <h3 class="text-xl font-semibold text-white">Daftar Kategori Terdaftar</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-800 text-gray-300 border-b border-gray-700">
                  <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($categories as $category)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition">
                      <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>
                      <td class="px-6 py-4 font-semibold text-white">{{ $category->name }}</td>
                      <td class="px-6 py-4">
                        <span class="bg-gray-700 text-blue-400 text-[10px] px-2 py-1 rounded-full border border-gray-600">
                          {{ $category->slug }}
                        </span>
                      </td>
                      <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                          <button onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}')"
                            class="bg-yellow-600 hover:bg-yellow-500 text-white p-2 rounded-lg transition text-xs shadow-md">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                              onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                              class="bg-red-600 hover:bg-red-500 text-white p-2 rounded-lg transition text-xs shadow-md">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                        <i class="fas fa-folder-open text-4xl mb-3 block text-gray-700"></i> Belum ada data kategori yang
                        tersedia.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>

  {{-- Modal Edit --}}
  <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div
        class="relative transform overflow-hidden rounded-xl bg-gray-800 text-left shadow-xl transition-all sm:w-full sm:max-w-md border border-gray-700">
        <div class="bg-gray-900 px-4 py-3 border-b border-gray-700 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-white">Edit Nama Kategori</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
          @csrf @method('PUT')
          <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-300">Nama Kategori</label>
            <input type="text" name="name" id="editName"
              class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg p-2.5 focus:ring-blue-500 outline-none focus:border-blue-500 transition"
              required>
          </div>
          <div class="flex gap-3">
            <button type="submit"
              class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition">
              Update Data
            </button>
            <button type="button" onclick="closeModal()"
              class="flex-1 bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openEditModal(id, name) {
      document.getElementById('editName').value = name;
      document.getElementById('editForm').action = '/admin/categories/' + id;
      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('editModal').classList.add('hidden');
    }
  </script>
@endsection
