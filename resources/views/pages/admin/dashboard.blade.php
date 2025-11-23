@extends('layouts.admin')

@section('title', 'Admin Dashboard Samafitro')

@section('content')

  <!-- Form Input -->
  <section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">Tambah Konten Dashboard</h1>
    <form action="{{ route('admin.dashboard.store') }}" method="POST" enctype="multipart/form-data"
      class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-800 p-6 rounded-xl shadow-xl border border-gray-700">
      @csrf
      <div>
        <label class="block mb-2 text-sm font-semibold text-gray-300">Judul</label>
        <input type="text" name="judul"
          class="w-full p-3 rounded bg-gray-700 text-white focus:outline-none focus:ring focus:ring-blue-500" required>

        <label class="block mt-4 mb-2 text-sm font-semibold text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" rows="4"
          class="w-full p-3 rounded bg-gray-700 text-white focus:outline-none focus:ring focus:ring-blue-500" required></textarea>
      </div>
      <div>
        <label class="block mb-2 text-sm font-semibold text-gray-300">Gambar</label>
        <input type="file" name="gambar"
          class="w-full bg-gray-700 rounded p-2 text-sm text-white border border-gray-600">

        <button type="submit"
          class="mt-6 w-full bg-blue-600 hover:bg-blue-700 transition py-3 rounded text-white font-semibold">
          <i class="fas fa-save mr-2"></i> Simpan Konten
        </button>
      </div>
    </form>
  </section>

  <!-- Quick Access Section -->
  <section class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6 text-white">Quick Access</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
      <a href="{{ route('admin.produk.index') }}"
        class="bg-blue-600 hover:bg-blue-700 transition p-6 rounded-xl text-center text-white">
        <i class="fas fa-box text-3xl mb-3"></i>
        <h3 class="text-lg font-semibold">Kelola Produk</h3>
        <p class="text-sm text-blue-100">Tambah, edit, hapus produk</p>
      </a>

      <a href="{{ route('admin.promos.index') }}"
        class="bg-green-600 hover:bg-green-700 transition p-6 rounded-xl text-center text-white">
        <i class="fas fa-tags text-3xl mb-3"></i>
        <h3 class="text-lg font-semibold">Kelola Promo</h3>
        <p class="text-sm text-green-100">Tambah, edit, hapus promo</p>
      </a>

      <a href="{{ route('admin.articles.index') }}"
        class="bg-purple-600 hover:bg-purple-700 transition p-6 rounded-xl text-center text-white">
        <i class="fas fa-newspaper text-3xl mb-3"></i>
        <h3 class="text-lg font-semibold">Kelola Artikel</h3>
        <p class="text-sm text-purple-100">Tambah, edit, hapus artikel</p>
      </a>
    </div>
  </section>

  <!-- Data Grid -->
  <section class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach ($items as $item)
      <div
        class="bg-gray-900 border border-gray-800 hover:shadow-xl transition rounded-xl overflow-hidden relative group">
        @if ($item->gambar)
          <img src="{{ asset('storage/' . $item->gambar) }}" class="w-full h-40 object-cover">
        @endif
        <div class="p-4">
          <h2 class="text-xl font-semibold text-white mb-2">{{ $item->judul }}</h2>
          <p class="text-gray-400 text-sm leading-relaxed">{{ $item->deskripsi }}</p>
        </div>
        <form action="{{ route('admin.dashboard.destroy', $item->id) }}" method="POST" class="absolute top-3 right-3">
          @csrf
          @method('DELETE')
          <button class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition"
            onclick="return confirm('Hapus item ini?')">
            <i class="fas fa-trash"></i>
          </button>
        </form>
      </div>
    @endforeach
  </section>

@endsection