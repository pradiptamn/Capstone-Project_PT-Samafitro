@extends('layouts.admin')

@section('title', (isset($promo) ? 'Edit Promo' : 'Tambah Promo') . ' | Admin Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <main class="flex-1 container mx-auto px-4 py-8">

      {{-- ===== BLOK NOTIFIKASI START ===== --}}
      <div class="max-w-4xl mx-auto">
        {{-- 1. Pesan Error Validasi Input (Misal: Judul kosong, Gambar > 2MB) --}}
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

        {{-- 2. Pesan Sukses (Berhasil Simpan/Update) --}}
        @if (session('success'))
          <div
            class="mb-6 p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
          </div>
        @endif

        {{-- 3. Pesan Error Sistem (Misal: SQL Truncated Error) --}}
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
            <i class="fas {{ isset($promo) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
            {{ isset($promo) ? 'Edit Promo' : 'Tambah Promo Baru' }}
          </h2>
          <a href="{{ route('admin.promos.index') }}"
            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>

        <div class="p-6 md:p-8">
          <form action="{{ isset($promo) ? route('admin.promos.update', $promo->id) : route('admin.promos.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($promo))
              @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Judul Promo</label>
                <input type="text" name="name" id="name"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                  value="{{ $promo->name ?? old('name') }}" required placeholder="Contoh: Promo Akhir Tahun">
              </div>

              <div>
                <label for="vendor" class="block mb-2 text-sm font-medium text-gray-300">Brand / Vendor</label>
                <input type="text" name="vendor" id="vendor"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                  value="{{ $promo->vendor ?? old('vendor') }}" required placeholder="Contoh: Canon">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
              <div class="md:col-span-2">
                <label for="label" class="block mb-2 text-sm font-medium text-gray-300">Label</label>
                <input type="text" name="label" id="label"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                  value="{{ $promo->label ?? old('label') }}" placeholder="Contoh: Best Seller">
              </div>

              <div class="md:col-span-1">
                <label for="discount" class="block mb-2 text-sm font-medium text-gray-300">Diskon (%)</label>
                <input type="text" name="discount" id="discount"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                  value="{{ $promo->discount ?? old('discount') }}" placeholder="20%">
              </div>

              <div class="md:col-span-1">
                <label for="periode" class="block mb-2 text-sm font-medium text-gray-300">Periode</label>
                <input type="date" name="periode" id="periode"
                  class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                  value="{{ $promo->periode ?? old('periode') }}">
              </div>
            </div>

            <div class="mb-6">
              <label for="terms" class="block mb-2 text-sm font-medium text-gray-300">Syarat & Ketentuan</label>
              <textarea name="terms" id="terms" rows="5"
                class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 placeholder-gray-500"
                placeholder="Tulis syarat & ketentuan, pisahkan dengan baris baru (Enter)...">{{ old('terms', $promo->terms ?? '') }}</textarea>
              <p class="mt-1 text-xs text-gray-400">
                *Setiap baris baru akan menjadi poin (bullet point) terpisah.
              </p>
            </div>

            <div class="mb-8">
              <label class="block mb-2 text-sm font-medium text-gray-300">Upload Gambar</label>
              <div class="flex flex-col sm:flex-row items-start gap-4">

                <div class="w-full sm:flex-1">
                  <input
                    class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600"
                    id="image" name="image" type="file" onchange="previewImage()">
                  <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, JPEG (Max. 2MB)</p>
                </div>

                <div class="shrink-0">
                  <p class="mb-2 text-xs text-gray-400">Preview:</p>
                  @if (isset($promo) && $promo->image)
                    <img src="{{ asset('storage/' . $promo->image) }}"
                      class="img-preview w-32 h-32 object-cover rounded-lg border-2 border-gray-600" alt="Preview">
                  @else
                    <img class="img-preview w-32 h-32 object-cover rounded-lg border-2 border-gray-600 hidden"
                      alt="Preview">
                    <div
                      class="no-img-placeholder w-32 h-32 rounded-lg border-2 border-dashed border-gray-600 flex items-center justify-center text-gray-500">
                      <span class="text-xs">No Image</span>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-gray-700">
              <button type="submit"
                class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-800">
                <i class="fas fa-save mr-2"></i> {{ isset($promo) ? 'Update Promo' : 'Simpan Promo' }}
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
          if (placeholder) placeholder.classList.add('hidden'); // Sembunyikan placeholder kotak kosong
        }
      }
    }
  </script>
@endsection
