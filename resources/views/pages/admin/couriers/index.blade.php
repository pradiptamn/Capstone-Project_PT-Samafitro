@extends('layouts.admin')

@section('title', 'Manajemen Kurir')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-white">Daftar Kurir Internal</h1>
      <a href="{{ route('admin.couriers.create') }}"
        class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold transition shadow-lg">
        <i class="fas fa-plus mr-2"></i> Tambah Kurir
      </a>
    </div>

    {{-- ===== BLOK NOTIFIKASI UNIFIED START ===== --}}
    <div class="mb-6">
      {{-- 1. Pesan Sukses (Update/Store/Delete) --}}
      @if (session('success'))
        <div
          class="p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-check-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
      @endif

      {{-- 2. Pesan Error Validasi (Jika ada input salah) --}}
      @if ($errors->any())
        <div class="p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg">
          <h4 class="font-bold mb-1 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> Ada Kesalahan:</h4>
          <ul class="list-disc list-inside text-xs">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- 3. Pesan Error Sistem --}}
      @if (session('error'))
        <div class="p-4 bg-red-600/20 border border-red-600 text-red-400 rounded-lg shadow-lg flex items-center gap-3">
          <i class="fas fa-times-circle text-xl"></i>
          <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
      @endif
    </div>
    {{-- ===== BLOK NOTIFIKASI UNIFIED END ===== --}}

    {{-- Pembungkus Utama dengan shadow dan border --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-xl overflow-hidden">

      {{-- WRAPPER SCROLL: Bagian ini yang membuat tabel bisa digeser ke samping --}}
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-400 min-w-[800px]">
          <thead class="bg-gray-900 text-gray-200 uppercase font-bold tracking-wider">
            <tr>
              <th class="px-6 py-4 w-24 text-center">Foto</th>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">Kontak</th>
              <th class="px-6 py-4">NIK & KTP</th>
              <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            @forelse($couriers as $courier)
              <tr class="hover:bg-gray-750 transition duration-150">

                {{-- Foto Profil (Klik untuk Lihat) --}}
                <td class="px-6 py-4">
                  <div class="flex justify-center">
                    <a href="{{ $courier->photo ? asset('storage/' . $courier->photo) : asset('images/profile.png') }}"
                      target="_blank" class="group relative block">
                      <img src="{{ $courier->photo ? asset('storage/' . $courier->photo) : asset('images/profile.png') }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-700 group-hover:border-blue-500 transition shadow-sm">
                      <div
                        class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <i class="fas fa-search-plus text-white text-[10px]"></i>
                      </div>
                    </a>
                  </div>
                </td>

                {{-- Nama (Diberi whitespace-nowrap agar tidak berantakan saat scroll) --}}
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="font-bold text-white text-base">{{ $courier->name }}</div>
                  <div class="text-[10px] text-gray-500 uppercase tracking-wider">Joined:
                    {{ $courier->created_at->format('d M Y') }}</div>
                </td>

                {{-- Kontak --}}
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-gray-600 w-4"></i> {{ $courier->email }}
                  </div>
                  <div class="mt-1 flex items-center gap-2">
                    <i class="fas fa-phone text-gray-600 w-4"></i> {{ $courier->phone }}
                  </div>
                </td>

                {{-- NIK & KTP --}}
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="font-mono text-gray-200 tracking-wider">{{ $courier->ktp_number }}</div>
                  @if ($courier->ktp_photo)
                    <a href="{{ asset('storage/' . $courier->ktp_photo) }}" target="_blank"
                      class="text-blue-400 text-[10px] inline-flex items-center gap-1 hover:underline mt-1">
                      <i class="fas fa-id-card"></i> (Lihat KTP)
                    </a>
                  @endif
                </td>

                {{-- Aksi --}}
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.couriers.edit', $courier->id) }}"
                      class="bg-yellow-600/20 text-yellow-500 p-2 rounded border border-yellow-600/30 hover:bg-yellow-600 hover:text-white transition shadow-sm"
                      title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.couriers.destroy', $courier->id) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurir ini?');">
                      @csrf @method('DELETE')
                      <button type="submit"
                        class="bg-red-600/20 text-red-500 p-2 rounded border border-red-600/30 hover:bg-red-600 hover:text-white transition shadow-sm"
                        title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                  <i class="fas fa-user-slash text-4xl mb-3 block opacity-20"></i>
                  Belum ada data kurir internal terdaftar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div> {{-- Akhir Wrapper Scroll --}}

      {{-- Pagination (Tetap terlihat tanpa perlu scroll horizontal) --}}
      <div class="p-4 border-t border-gray-700 bg-gray-900/20">
        {{ $couriers->links() }}
      </div>
    </div>
  </div>
@endsection
