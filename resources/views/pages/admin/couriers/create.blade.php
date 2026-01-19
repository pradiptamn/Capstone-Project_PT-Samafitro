@extends('layouts.admin')

@section('title', 'Tambah Kurir')

@section('content')
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
      <a href="{{ route('admin.couriers.index') }}"
        class="text-gray-400 hover:text-white flex items-center gap-2 transition">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

    {{-- Notifikasi Error Global --}}
    @if ($errors->any())
      <div class="mb-6 bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg text-sm">
        <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Periksa kembali formulir Anda:</p>
        <ul class="list-disc list-inside mt-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-6">Formulir Kurir Baru</h2>

      <form action="{{ route('admin.couriers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Nama Lengkap --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
              class="w-full bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
              required>
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email Login --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Email Login</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full bg-gray-900 border @error('email') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
              required>
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Nomor Telepon --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Nomor Telepon (WA)</label>
            <input type="number" name="phone" value="{{ old('phone') }}"
              class="w-full bg-gray-900 border @error('phone') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
              required>
            @error('phone')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Password Akun</label>
            <input type="password" name="password"
              class="w-full bg-gray-900 border @error('password') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
              required>
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Section Data Identitas --}}
        <div class="border-t border-gray-700 pt-6">
          <h3 class="text-lg font-semibold text-white mb-4">Data Identitas & Foto</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- NIK --}}
            <div class="md:col-span-2">
              <label class="block text-sm text-gray-400 mb-2">Nomor KTP (NIK)</label>
              <input type="number" name="ktp_number" value="{{ old('ktp_number') }}"
                class="w-full bg-gray-900 border @error('ktp_number') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
                required>
              @error('ktp_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Foto Profil (Wajib) --}}
            <div>
              <label class="block text-sm text-gray-400 mb-2">Foto Profil (Resmi)</label>
              <input type="file" name="photo"
                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600 cursor-pointer"
                required>
              <p class="text-[10px] text-gray-500 mt-1">*Wajib menggunakan seragam resmi Samafitro.</p>
              @error('photo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Foto KTP (Wajib) --}}
            <div>
              <label class="block text-sm text-gray-400 mb-2">Foto KTP</label>
              <input type="file" name="ktp_photo"
                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600 cursor-pointer"
                required>
              <p class="text-[10px] text-gray-500 mt-1">*Pastikan foto KTP terlihat jelas dan terbaca.</p>
              @error('ktp_photo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transition mt-4">
          Simpan Data Kurir
        </button>
      </form>
    </div>
  </div>
@endsection
