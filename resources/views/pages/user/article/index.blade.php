@extends('layouts.loggedin')

@section('title', 'Artikel & Berita | Samafitro')

@section('content')
  {{-- Wrapper Utama --}}
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans pb-20">

    {{-- 1. Header Section (Lebih Ramping) --}}
    <section class="relative py-10 px-4 text-center">
      <div class="relative z-10 max-w-3xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-3 tracking-tight text-white">
          Artikel & <span class="text-blue-500">Berita</span>
        </h1>
        <p class="text-gray-400 text-base">
          Informasi terbaru seputar teknologi dan produk Samafitro.
        </p>
        <div class="w-16 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
      </div>
    </section>

    {{-- 2. Artikel Grid --}}
    <section class="container mx-auto px-4 md:px-6 lg:px-8">

      @if ($articles->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach ($articles as $article)
            <article
              class="group bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">

              {{-- Gambar Artikel (Lebih Pendek: h-44) --}}
              <div class="relative h-44 overflow-hidden bg-gray-700">
                <a href="{{ route('article.show', $article->id) }}">
                  @if ($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                      class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                  @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-600 bg-gray-800">
                      <i class="far fa-image text-3xl mb-2"></i>
                      <span class="text-xs">No Image</span>
                    </div>
                  @endif

                  {{-- Overlay Hover --}}
                  <div class="absolute inset-0 bg-black/10 group-hover:bg-black/5 transition-colors"></div>
                </a>

                {{-- Badge Tanggal (Lebih Kecil) --}}
                <div
                  class="absolute top-3 right-3 bg-gray-900/90 backdrop-blur-sm text-[10px] font-bold text-white px-2.5 py-1 rounded-md border border-gray-600 shadow-sm">
                  {{ $article->created_at ? $article->created_at->format('d M Y') : '-' }}
                </div>
              </div>

              {{-- Konten Artikel --}}
              <div class="p-5 flex flex-col flex-grow">

                <a href="{{ route('article.show', $article->id) }}" class="block">
                  {{-- Judul: Max 2 Baris --}}
                  <h3
                    class="text-lg font-bold text-white mb-2 line-clamp-2 leading-tight group-hover:text-blue-400 transition-colors">
                    {{ $article->title }}
                  </h3>
                </a>

                {{-- Deskripsi: Max 2 Baris (Agar tidak tinggi) --}}
                <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed flex-grow">
                  {{ Str::limit(strip_tags($article->content), 100) }}
                </p>

                {{-- Footer Card --}}
                <div class="mt-auto pt-3 border-t border-gray-700 flex items-center justify-between">
                  <a href="{{ route('article.show', $article->id) }}"
                    class="text-blue-400 hover:text-blue-300 text-xs font-semibold uppercase tracking-wide flex items-center gap-1 group/link">
                    Baca Selengkapnya
                    <i
                      class="fas fa-chevron-right text-[10px] transform group-hover/link:translate-x-1 transition-transform"></i>
                  </a>
                </div>

              </div>
            </article>
          @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
          {{-- {{ $articles->links() }} --}}
        </div>
      @else
        {{-- Empty State --}}
        <div class="text-center py-16 bg-gray-800/50 rounded-2xl border border-gray-700/50 max-w-2xl mx-auto">
          <div
            class="inline-flex items-center justify-center w-16 h-16 bg-gray-800 rounded-full mb-4 border border-gray-700 shadow-inner">
            <i class="far fa-newspaper text-3xl text-gray-500"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-1">Belum ada artikel</h3>
          <p class="text-gray-400 text-sm">Nantikan informasi menarik dari kami segera.</p>
        </div>
      @endif

    </section>
  </div>
@endsection
