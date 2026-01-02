@extends('layouts.admin')

@section('title', 'Admin - Daftar Promo')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-8">
      <h2 class="text-3xl font-bold text-center mb-6 flex items-center justify-center gap-2">
        Daftar Promo Produk
      </h2>

      <!-- Add Button -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <a href="{{ route('admin.promos.create') }}"
          class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
          <i class="fas fa-plus"></i> Tambah Promo
        </a>
        @if (session('success'))
          <div class="bg-green-600 text-white px-4 py-2 rounded-lg shadow">
            {{ session('success') }}
          </div>
        @endif
      </div>

      <!-- Table Card -->
      <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto hidden md:block">
          <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800 text-gray-300">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Judul</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Vendor</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Label</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Diskon</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Periode</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Gambar</th>
                <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
              @forelse($promos as $promo)
                <tr class="hover:bg-gray-800 transition">
                  <td class="px-4 py-3">{{ $promo->name }}</td>
                  <td class="px-4 py-3">{{ $promo->vendor }}</td>
                  <td class="px-4 py-3">
                    <span class="bg-indigo-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                      {{ $promo->label }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-red-400 font-semibold">{{ $promo->discount }}%</td>
                  <td class="px-4 py-3">{{ $promo->periode }}</td>
                  <td class="px-4 py-3">
                    <img src="{{ asset('storage/' . $promo->image) }}" alt="Gambar Promo"
                      class="w-20 h-20 object-cover rounded-lg">
                  </td>
                  <td class="px-4 py-3 text-center space-x-2">
                    <a href="{{ route('admin.promos.edit', $promo->id) }}"
                      class="bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-1 rounded-md text-sm shadow transition inline-flex items-center gap-1">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Yakin hapus promo ini?')">
                      @csrf
                      @method('DELETE')
                      <button
                        class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-md text-sm shadow transition inline-flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data promo.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Mobile Card View -->
        <div class="space-y-4 md:hidden p-4">
          @forelse($promos as $promo)
            <div class="bg-gray-800 p-4 rounded-lg shadow space-y-2">
              <div class="flex justify-between items-center">
                <h3 class="font-bold">{{ $promo->name }}</h3>
                <span
                  class="bg-indigo-500 text-white px-3 py-1 rounded-full text-xs font-semibold">{{ $promo->label }}</span>
              </div>
              <p class="text-sm text-gray-400">Vendor: {{ $promo->vendor }}</p>
              <p class="text-sm text-red-400 font-semibold">Diskon: {{ $promo->discount }}</p>
              <p class="text-sm">Periode: {{ $promo->periode }}</p>
              <img src="{{ asset('storage/' . $promo->image) }}" alt="Gambar Promo"
                class="w-full h-40 object-cover rounded-lg">
              <div class="flex gap-2 pt-2">
                <a href="{{ route('admin.promos.edit', $promo->id) }}"
                  class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-1 rounded-md text-sm shadow text-center">
                  Edit
                </a>
                <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('Yakin hapus promo ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="w-full bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-md text-sm shadow">
                    Hapus
                  </button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center text-gray-500">Belum ada data promo.</p>
          @endforelse
        </div>
      </div>
    </main>
  </div>
@endsection
