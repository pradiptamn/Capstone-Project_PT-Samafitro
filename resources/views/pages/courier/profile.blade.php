@extends('layouts.courier')

@section('title', 'Profil Saya')

@section('content')
  <div class="container mx-auto px-4 py-10 max-w-3xl">

    {{-- Header Profil & Statistik --}}
    <div
      class="flex flex-col md:flex-row items-center gap-8 mb-10 bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-2xl">
      {{-- Foto Profil (Read-Only) --}}
      <div class="relative">
        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-avatar.png') }}"
          class="w-32 h-32 rounded-full object-cover border-4 border-blue-600 shadow-lg">
      </div>

      {{-- Info Utama & Statistik --}}
      <div class="flex-1 text-center md:text-left">
        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
        <p class="text-gray-400 text-sm mb-4">{{ $user->email }}</p>

        <div class="inline-flex items-center gap-6 bg-gray-900/50 p-4 rounded-xl border border-gray-700">
          <div>
            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Pengiriman Selesai</p>
            <p class="text-2xl font-black text-blue-400">{{ $completedDeliveries }} <span
                class="text-xs text-gray-400 font-normal">Paket</span></p>
          </div>
          <div class="h-10 w-[1px] bg-gray-700"></div>
          <div>
            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Bergabung Sejak</p>
            <p class="text-sm font-bold text-white">{{ $user->created_at->format('M Y') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Form Pengaturan --}}
    <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
      <div class="bg-gray-700/30 px-8 py-4 border-b border-gray-700">
        <h2 class="text-lg font-bold text-white">Pengaturan Akun</h2>
      </div>

      <form action="{{ route('courier.profile.update') }}" method="POST" class="p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Nama (Locked) --}}
          <div>
            <label class="block text-sm text-gray-500 mb-2">Nama Lengkap (Resmi)</label>
            <input type="text" value="{{ $user->name }}"
              class="w-full bg-gray-900/50 border border-gray-700 rounded-lg p-3 text-gray-500 cursor-not-allowed"
              readonly>
          </div>

          {{-- Phone (Editable) --}}
          <div>
            <label class="block text-sm text-gray-400 mb-2">Nomor WhatsApp (Aktif)</label>
            <input type="number" name="phone" value="{{ old('phone', $user->phone) }}"
              class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none transition"
              required>
          </div>
        </div>

        {{-- Keamanan --}}
        <div class="border-t border-gray-700 pt-6">
          <h3 class="text-md font-bold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-shield-alt text-blue-500"></i> Pertanyaan Keamanan
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm text-gray-400 mb-2">Pertanyaan</label>
              <input type="text" name="security_question"
                value="{{ old('security_question', $user->security_question) }}"
                class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none"
                required>
            </div>
            <div>
              <label class="block text-sm text-gray-400 mb-2">Jawaban Baru</label>
              <input type="text" name="security_answer" placeholder="Kosongkan jika tidak diubah"
                class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
            </div>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-blue-900/20">
          Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
@endsection
