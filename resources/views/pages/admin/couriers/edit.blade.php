@extends('layouts.admin')

@section('title', 'Edit Kurir: ' . $courier->name)

@section('content')
  <div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
      <a href="{{ route('admin.couriers.index') }}"
        class="text-gray-400 hover:text-white flex items-center gap-2 transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
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
      <h2 class="text-2xl font-bold text-white mb-6">Edit Data Kurir</h2>

      <form action="{{ route('admin.couriers.update', $courier->id) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Nama Lengkap --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $courier->name) }}"
              class="w-full bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none"
              required>
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email Login --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Email Login</label>
            <input type="email" name="email" value="{{ old('email', $courier->email) }}"
              class="w-full bg-gray-900 border @error('email') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none"
              required>
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Nomor Telepon --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Nomor Telepon (WA)</label>
            <input type="number" name="phone" value="{{ old('phone', $courier->phone) }}"
              class="w-full bg-gray-900 border @error('phone') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none"
              required>
            @error('phone')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password Baru --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Password Baru (Opsional)</label>
            <input type="password" name="password"
              class="w-full bg-gray-900 border @error('password') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none"
              placeholder="Kosongkan jika tidak ingin diubah">
            <p class="text-xs text-gray-500 mt-1">*Minimal 6 karakter jika ingin diubah.</p>
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="border-t border-gray-700 pt-6">
          <h3 class="text-lg font-semibold text-white mb-4">Data Identitas & Foto</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- NIK --}}
            <div class="md:col-span-2">
              <label class="block text-sm text-gray-400 mb-2">Nomor KTP (NIK)</label>
              <input type="number" name="ktp_number" value="{{ old('ktp_number', $courier->ktp_number) }}"
                class="w-full bg-gray-900 border @error('ktp_number') border-red-500 @else border-gray-600 @enderror rounded-lg p-3 text-white focus:border-blue-500 outline-none"
                required>
              @error('ktp_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Edit Foto Profil (Nullable) --}}
            <div>
              <label class="block text-sm text-gray-400 mb-2">Foto Profil (Opsional)</label>
              @if ($courier->photo)
                <div class="mb-3">
                  <p class="text-[10px] text-gray-500 mb-1">Foto Profil Saat Ini:</p>
                  <img src="{{ asset('storage/' . $courier->photo) }}"
                    class="h-24 w-24 object-cover rounded-full border border-gray-600 shadow-md">
                </div>
              @endif
              <input type="file" name="photo"
                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600">
              @error('photo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Edit Foto KTP (Nullable) --}}
            <div>
              <label class="block text-sm text-gray-400 mb-2">Foto KTP (Opsional)</label>
              @if ($courier->ktp_photo)
                <div class="mb-3">
                  <p class="text-[10px] text-gray-500 mb-1">Foto KTP Saat Ini:</p>
                  <a href="{{ asset('storage/' . $courier->ktp_photo) }}" target="_blank" class="inline-block">
                    <img src="{{ asset('storage/' . $courier->ktp_photo) }}"
                      class="h-24 w-40 object-cover rounded border border-gray-600 hover:border-blue-500 transition shadow-md">
                  </a>
                </div>
              @endif
              <input type="file" name="ktp_photo"
                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600">
              @error('ktp_photo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4">
          <button type="submit"
            class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transition">
            Perbarui Data Kurir
          </button>
          <a href="{{ route('admin.couriers.index') }}"
            class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 rounded-lg text-center transition">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
