@extends('layouts.loggedin')

@section('title', ($promo->name ?? 'Detail Promo') . ' | Samafitro')

@section('content')
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white py-12">
    <div class="container mx-auto px-4">

      <div class="mb-6">
        <a href="{{ route('promo.index') }}"
          class="inline-flex items-center text-gray-400 hover:text-white transition text-sm">
          <i class="fas fa-arrow-left mr-2"></i> Kembali ke Promo
        </a>
      </div>

      <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">
        <div class="flex flex-col lg:flex-row">

          <div class="lg:w-1/2 relative bg-white h-[300px] lg:h-auto flex items-center justify-center p-4">

            <div class="absolute top-0 left-0 z-10 bg-red-600 text-white font-bold px-4 py-2 rounded-br-xl shadow-md">
              {{ $promo->discount ?? '30%' }} OFF
            </div>

            @if ($promo->image)
              <img src="{{ asset('storage/' . $promo->image) }}" alt="{{ $promo->name }}"
                class="w-full h-full object-contain max-h-[400px]">
            @else
              <div class="flex flex-col items-center justify-center text-gray-400 h-64">
                <i class="fas fa-image text-4xl mb-2"></i>
                <span>No Image Available</span>
              </div>
            @endif
          </div>

          <div class="lg:w-1/2 p-8 md:p-10 flex flex-col justify-center">

            <div class="flex flex-wrap gap-2 mb-4">
              @if ($promo->vendor)
                <span
                  class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-600 uppercase tracking-wider">
                  {{ $promo->vendor }}
                </span>
              @endif
              @if ($promo->label)
                <span
                  class="bg-blue-900/50 text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-800 uppercase tracking-wider">
                  {{ $promo->label }}
                </span>
              @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
              {{ $promo->name ?? 'Promo Spesial' }}
            </h1>

            <div class="flex items-center gap-3 bg-gray-900/50 p-4 rounded-xl border border-gray-700 mb-6">
              <div class="bg-blue-600/20 p-2 rounded-lg text-blue-400">
                <i class="far fa-clock text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 uppercase font-bold">Periode Promo</p>
                <p class="text-white font-medium">
                  {{ \Carbon\Carbon::parse($promo->periode)->translatedFormat('d F Y') ?? '-' }}
                </p>
              </div>
            </div>

            @if ($promo->terms)
              <div class="mb-8">
                <h3
                  class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-3 border-b border-gray-700 pb-1 inline-block">
                  Syarat & Ketentuan
                </h3>
                <ul class="list-disc list-outside ml-4 text-gray-400 text-sm space-y-1 leading-relaxed">
                  @foreach (explode("\n", $promo->terms) as $term)
                    @if (trim($term))
                      <li>{{ trim($term) }}</li>
                    @endif
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="mt-auto">
              @php
                $waNumber = '6281234567890';
                $waText = urlencode(
                    "Halo, saya tertarik promo: {$promo->name} (ID: {$promo->id}). Mohon info lebih lanjut.",
                );
              @endphp

              <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank"
                class="inline-flex items-center justify-center w-full sm:w-auto bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                <i class="fab fa-whatsapp text-xl mr-2"></i>
                Pesan Sekarang via WhatsApp
              </a>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
