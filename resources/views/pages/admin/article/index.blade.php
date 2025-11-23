@extends('layouts.admin')

@section('title', 'Admin Artikel | Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">


    <!-- Header -->
    <header
      class="bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 shadow-md">
      <h1 class="text-xl sm:text-2xl font-bold text-white">Artikel Admin</h1>
      <a href="{{ route('admin.articles.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition text-center">
        + Tambah Artikel
      </a>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex-grow w-full">
      <div
        class="bg-gradient-to-br from-gray-800/80 to-gray-700/80 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-xl border border-gray-600">
        <h2 class="text-xl sm:text-2xl font-semibold text-white mb-4 sm:mb-6 border-b border-gray-600 pb-2">Daftar Artikel
          & Berita</h2>

        <div class="overflow-x-auto rounded-lg">
          <table class="min-w-full text-white text-sm sm:text-base">
            <thead class="bg-gray-800/90">
              <tr>
                <th class="px-4 py-3 text-left font-medium">Gambar</th>
                <th class="px-4 py-3 text-left font-medium">Judul</th>
                <th class="px-4 py-3 text-left font-medium">Tahun</th>
                <th class="px-4 py-3 text-left font-medium">Isi</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              @forelse ($articles as $article)
                <tr class="hover:bg-gray-700/50 transition duration-150">
                  <td class="px-4 py-3">
                    @if ($article->image)
                      <img src="{{ asset('storage/' . $article->image) }}" class="w-20 sm:w-24 h-auto rounded-md shadow"
                        alt="Gambar Artikel">
                    @else
                      <span class="text-gray-400 italic">Tidak ada gambar</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 font-semibold">{{ $article->title }}</td>
                  <td class="px-4 py-3">{{ $article->year }}</td>
                  <td class="px-4 py-3 max-w-xs overflow-hidden text-ellipsis">
                    {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 80) }}
                  </td>
                  <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                    <a href="{{ route('admin.articles.edit', $article) }}"
                      class="text-blue-400 hover:text-blue-200 transition">Edit</a>
                    <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline-block"
                      onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="text-red-400 hover:text-red-300 transition">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-6 text-gray-400">Belum ada artikel ditambahkan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>


    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
      <script>
        window.onload = function() {
          alert("{{ session('success') }}");
        }
      </script>
    @endif

  @endsection