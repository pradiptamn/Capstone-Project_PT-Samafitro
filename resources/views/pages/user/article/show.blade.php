@extends('layouts.loggedin')

@section('title', ($article->title ?? 'Artikel') . ' | Samafitro')

@section('additional-styles')
  <style>
    html,
    body {
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
      /* ini biar konten ngisi ruang kosong */
    }
  </style>
@endsection

@section('content')
  {{-- Article Content --}}
  <div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <div class="mb-6">
      <a href="{{ route('article.index') }}"
        class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Artikel
      </a>
    </div>

    {{-- Article Header --}}
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-white mb-4">{{ $article->title ?? 'Judul Artikel' }}</h1>

      {{-- Article Meta --}}
      <div class="flex flex-wrap items-center gap-4 text-gray-400 text-sm">
        <span class="flex items-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          {{ $article->created_at ? $article->created_at->format('d M Y') : 'Tanggal tidak tersedia' }}
        </span>

        @if ($article->year)
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Tahun: {{ $article->year }}
          </span>
        @endif
      </div>
    </div>

    {{-- Article Image --}}
    @if ($article->image)
      <div class="mb-8">
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
          class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
      </div>
    @endif

    {{-- Article Content --}}
    <div class="prose prose-invert prose-lg max-w-none">
      <div class="text-gray-300 text-base leading-relaxed mb-8">
        {!! nl2br(e($article->content ?? 'Konten artikel tidak tersedia.')) !!}
      </div>
    </div>

    {{-- Share Buttons --}}
    <div class="border-t border-gray-700 pt-8">
      <h3 class="text-lg font-semibold text-white mb-4">Bagikan Artikel:</h3>
      <div class="flex flex-wrap gap-4">
        {{-- WhatsApp --}}
        <a href="https://wa.me/?text={{ urlencode(($article->title ?? 'Artikel') . ' ' . url()->current()) }}"
          target="_blank"
          class="w-12 h-12 flex items-center justify-center bg-green-500 rounded-full hover:scale-110 transform transition">
          <img src="{{ asset('images/WhatsApp.png') }}" alt="Share WhatsApp" class="w-6 h-6">
        </a>

        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
          class="w-12 h-12 flex items-center justify-center bg-blue-600 rounded-full hover:scale-110 transform transition">
          <img src="{{ asset('images/facebook.png') }}" alt="Share Facebook" class="w-6 h-6">
        </a>

        {{-- Instagram --}}
        <a href="https://www.instagram.com/" target="_blank"
          class="w-12 h-12 flex items-center justify-center bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 rounded-full hover:scale-110 transform transition">
          <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="w-6 h-6">
        </a>
      </div>
    </div>
  </div>
@endsection

@section('floating-wa')
  {{-- Floating WhatsApp --}}
  <a href="https://wa.me/6281234567890" target="_blank" class="floating-wa">
    <img src="{{ asset('images/buttonwa.png') }}" alt="WhatsApp">
  </a>
@endsection
