@extends('layouts.admin')

@section('title', 'Tambah Artikel Baru | Admin Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <main class="flex-1 container mx-auto px-4 py-8">
      {{-- ===== BLOK NOTIFIKASI START ===== --}}
      <div class="max-w-4xl mx-auto">
        {{-- 1. Pesan Error Validasi Input (Judul kosong, File terlalu besar, dll) --}}
        @if ($errors->any())
          <div class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg">
            <h4 class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Periksa Kembali Input Anda:</h4>
            <ul class="list-disc list-inside text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- 2. Pesan Sukses (Berhasil Publikasi Artikel) --}}
        @if (session('success'))
          <div
            class="mb-6 p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
          </div>
        @endif

        {{-- 3. Pesan Error Sistem (Gagal Upload ke Storage, dll) --}}
        @if (session('error'))
          <div
            class="mb-6 p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-times-circle text-xl"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
          </div>
        @endif
      </div>
      {{-- ===== BLOK NOTIFIKASI END ===== --}}

      <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden border border-gray-700 max-w-4xl mx-auto">

        <div class="px-6 py-4 border-b border-gray-700 bg-gray-800 flex items-center justify-between">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-newspaper"></i> Tambah Artikel Baru
          </h2>
          <a href="{{ route('admin.articles.index') }}"
            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>

        <div class="p-6 md:p-8">
          <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
              <label for="title" class="block mb-2 text-sm font-medium text-gray-300">Judul Artikel</label>
              <input type="text" name="title" id="title"
                class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-500"
                placeholder="Masukkan judul artikel yang menarik..." required>
              @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label for="year" class="block mb-2 text-sm font-medium text-gray-300">Tahun Publikasi</label>
                <input type="number" name="year" id="year"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-500"
                  placeholder="Contoh: 2025" value="{{ date('Y') }}" required>
              </div>
            </div>

            <div class="mb-6">
              <label for="content" class="block mb-2 text-sm font-medium text-gray-300">Isi Artikel</label>
              <textarea name="content" id="content" rows="10"
                class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-gray-500 leading-relaxed"
                placeholder="Tulis isi artikel lengkap di sini..." required></textarea>
              <p class="mt-1 text-xs text-gray-400">*Gunakan enter untuk membuat paragraf baru.</p>
            </div>

            <div class="mb-8">
              <label class="block mb-2 text-sm font-medium text-gray-300">Gambar Utama / Thumbnail</label>
              <div class="flex flex-col sm:flex-row items-start gap-4">

                <div class="w-full sm:flex-1">
                  <input
                    class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600"
                    id="image" name="image" type="file" onchange="previewImage()" accept="image/*">
                  <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, JPEG, WEBP (Max. 2MB)</p>
                </div>

                <div class="shrink-0">
                  <p class="mb-2 text-xs text-gray-400">Preview:</p>
                  <img class="img-preview w-40 h-24 object-cover rounded-lg border-2 border-gray-600 hidden"
                    alt="Preview">
                  <div
                    class="no-img-placeholder w-40 h-24 rounded-lg border-2 border-dashed border-gray-600 flex items-center justify-center text-gray-500">
                    <span class="text-xs">No Image</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-gray-700">
              <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-800">
                <i class="fas fa-save mr-2"></i> Publikasikan Artikel
              </button>
            </div>

          </form>
        </div>
      </div>

    </main>
  </div>

  <script>
    function previewImage() {
      const image = document.querySelector('#image');
      const imgPreview = document.querySelector('.img-preview');
      const placeholder = document.querySelector('.no-img-placeholder');

      if (image.files && image.files[0]) {
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
          imgPreview.src = oFREvent.target.result;
          imgPreview.classList.remove('hidden'); // Tampilkan gambar
          if (placeholder) placeholder.classList.add('hidden'); // Sembunyikan kotak kosong
        }
      }
    }
  </script>
@endsection
