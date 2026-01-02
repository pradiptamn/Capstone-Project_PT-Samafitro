@extends('layouts.loggedin')

@section('title', 'Promo Menarik Bulan Ini')

@section('content')
  {{-- Container Utama dengan Background Gelap --}}
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen pb-20">

    {{-- Header Section --}}
    <div class="container mx-auto px-4 py-12">
      <div class="text-center max-w-3xl mx-auto mb-12">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
          Promo Menarik <span class="text-blue-500">Bulan Ini</span>
        </h1>
        <p class="text-gray-400 text-lg md:text-xl leading-relaxed">
          Dapatkan penawaran terbaik dan potongan harga spesial untuk produk-produk berkualitas dari Samafitro.
        </p>
      </div>

      {{-- Promo Grid --}}
      @if (count($promos) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach ($promos as $promo)
            {{-- Card Item --}}
            <div
              class="group bg-gray-800 rounded-2xl overflow-hidden shadow-lg border border-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full relative">

              {{-- Promo Tag (Absolute) --}}
              <div class="absolute top-0 left-0 z-10 bg-red-600 text-white px-4 py-2 rounded-br-2xl shadow-md">
                <div class="text-lg font-bold">Diskon {{ $promo['discount'] . '%' ?? '30%' }}</div>
                <div class="text-[10px] uppercase tracking-wider opacity-90">Brand {{ $promo['vendor'] }}</div>
              </div>

              {{-- Image Wrapper --}}
              <div class="h-56 overflow-hidden relative bg-white">
                <img src="{{ asset('storage/' . $promo->image) }}"
                  class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                  alt="{{ $promo['title'] }}">

                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60">
                </div>
              </div>

              {{-- Card Body --}}
              <div class="p-6 flex flex-col flex-grow justify-between">
                <div>
                  {{-- Title --}}
                  <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors line-clamp-2">
                    {{ $promo['title'] }}
                  </h3>

                  {{-- Badges --}}
                  <div class="flex flex-wrap gap-2 mb-4">
                    <span
                      class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-600">
                      {{ $promo['vendor'] }}
                    </span>
                    <span
                      class="bg-blue-900/50 text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-800">
                      {{ $promo['label'] }}
                    </span>
                  </div>
                </div>

                {{-- Footer Card --}}
                <div class="mt-4 pt-4 border-t border-gray-700">
                  <p class="text-gray-400 text-sm mb-4 flex items-center">
                    <i class="far fa-clock text-blue-500 mr-2"></i>
                    <span>Periode: <span class="text-gray-200 font-medium">
                        {{ \Carbon\Carbon::parse($promo->periode)->translatedFormat('d F Y') ?? '-' }}
                      </span></span>
                  </p>

                  <a href="{{ route('promo.show', $promo['id']) }}"
                    class="flex items-center justify-between w-full bg-transparent border border-gray-600 text-gray-300 hover:bg-white hover:text-gray-900 hover:border-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 group">
                    <span>Lihat Detail</span>
                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        {{-- Empty State --}}
        <div class="text-center py-20">
          <div class="inline-block p-4 rounded-full bg-gray-800 mb-4">
            <i class="fas fa-tags text-4xl text-gray-500"></i>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Belum ada promo saat ini</h3>
          <p class="text-gray-400">Nantikan penawaran menarik kami selanjutnya.</p>
        </div>
      @endif

    </div>
  </div>
@endsection
