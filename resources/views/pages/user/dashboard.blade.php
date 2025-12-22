@extends('layouts.loggedin')

@section('title', 'Dashboard Samafitro')

@section('content')
  {{-- Carousel --}}
  <section x-data="carousel()" x-init="start()" class="max-w-7xl mx-auto px-4 py-14">
    <div class="relative overflow-hidden h-[400px] rounded-3xl shadow-2xl bg-gray-900">
      <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeIndex === index" x-transition
          class="absolute inset-0 md:relative flex flex-col md:flex-row items-center w-full h-full">
          <div
            class="w-full md:w-1/2 bg-black/40 backdrop-blur-md p-6 sm:p-8 md:p-12 flex flex-col justify-center space-y-4">
            <h2 class="text-white text-2xl sm:text-3xl md:text-4xl font-bold" x-text="slide.title"></h2>
            <p class="text-blue-300 text-lg md:text-xl font-medium" x-text="slide.brand"></p>
            <p class="text-gray-300 text-sm md:text-base" x-text="slide.description"></p>
          </div>
          <div class="w-full md:w-1/2 h-full flex items-center justify-center p-4">
            <img :src="slide.image" :alt="slide.title"
              class="object-contain w-full h-full max-h-[300px] md:max-h-[500px]">
          </div>
        </div>
      </template>

      {{-- Navigation Arrows --}}
      <button @click="prev()"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all duration-300 z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button @click="next()"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all duration-300 z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>

      {{-- Dots Indicator --}}
      <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
        <template x-for="(slide, index) in slides" :key="index">
          <button @click="activeIndex = index" :class="activeIndex === index ? 'bg-blue-500' : 'bg-gray-400'"
            class="w-3 h-3 rounded-full transition-all duration-300"></button>
        </template>
      </div>
    </div>
  </section>

  {{-- Section with text and images --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

    {{-- Left side cards --}}
    {{-- Container diberi height fix (h-[220px]) dan relative --}}
    <div class="relative w-full h-[220px] bg-gray-800 rounded-2xl shadow-lg overflow-hidden" x-data="{ active: true }"
      x-init="setInterval(() => { active = !active }, 3000)">

      {{-- Image 1 --}}
      <div class="absolute inset-0 flex items-center justify-center p-4" x-show="active"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-700"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <img src="{{ asset('images/banner/ucjv330.png') }}" alt="UCJV330" class="object-contain w-full h-[180px]">
      </div>

      {{-- Image 2 --}}
      <div class="absolute inset-0 flex items-center justify-center p-4" x-show="!active"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-700"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <img src="{{ asset('images/banner/lxir320.png') }}" alt="LXiR320" class="object-contain w-full h-[180px]">
      </div>
    </div>

    {{-- Center text --}}
    <div class="text-center">
      <h2 class="text-xl sm:text-2xl md:text-4xl font-bold">Kepercayaan Anda,<br> keahlian kami</h2>
      <p class="mt-3 text-gray-300 text-sm sm:text-base">
        Dengan mesin yang berperforma tinggi,<br> memberikan pengalaman terbaik kepada Anda
      </p>
    </div>

    {{-- Right side cards --}}
    {{-- Container diberi height fix (h-[220px]) dan relative --}}
    <div class="relative w-full h-[220px] bg-gray-800 rounded-2xl shadow-lg overflow-hidden" x-data="{ active: true }"
      x-init="setTimeout(() => { setInterval(() => { active = !active }, 3000) }, 1500)">

      {{-- Image 3 --}}
      <div class="absolute inset-0 flex items-center justify-center p-4" x-show="active"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-700"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <img src="{{ asset('images/banner/tc20m.png') }}" alt="TC-20M" class="object-contain w-full h-[180px]">
      </div>

      {{-- Image 4 --}}
      <div class="absolute inset-0 flex items-center justify-center p-4" x-show="!active"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-700"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <img src="{{ asset('images/banner/prestos.png') }}" alt="Presto S" class="object-contain w-full h-[180px]">
      </div>
    </div>
  </section>

  {{-- Items dari database --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach ($items as $item)
        <div class="bg-gray-800 rounded-xl p-4 shadow hover:shadow-xl transition">
          <h3 class="text-lg font-semibold mb-2">{{ $item->judul }}</h3>
          <p class="text-gray-300 text-sm mb-3">{{ $item->deskripsi }}</p>
          @if ($item->gambar)
            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
              class="rounded-lg w-full object-cover max-h-52">
          @endif
        </div>
      @endforeach
    </div>
  </section>

  {{-- Quick Actions Section --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
      <h3 class="text-xl font-semibold text-white mb-4">Quick Actions</h3>
      <div class="flex flex-wrap gap-4">
        <a href="{{ route('profile.index') }}"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
          <i class="fas fa-user mr-2"></i>View Profile
        </a>
        <a href="{{ route('promo.index') }}"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
          <i class="fas fa-tags mr-2"></i>View Promos
        </a>
        <a href="{{ route('article.index') }}"
          class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
          <i class="fas fa-newspaper mr-2"></i>Read Articles
        </a>
        <a href="{{ route('produk.user') }}"
          class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition-colors">
          <i class="fas fa-box mr-2"></i>Browse Products
        </a>
        <a href="{{ route('contact-us') }}"
          class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg transition-colors">
          <i class="fas fa-headset mr-2"></i>Hubungi Kami
        </a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </button>
        </form>
      </div>
    </div>
  </section>
@endsection

@section('additional-scripts')
  <script>
    function carousel() {
      return {
        activeIndex: 0,
        slides: [{
            image: '/images/banner/ucjv330.png',
            title: 'UCJV330 SERIE',
            brand: 'Mimaki',
            description: 'Inovasi cetak, kualitas profesional.'
          },
          {
            image: '/images/banner/tc20m.png',
            title: 'Image PROGRAF TC-20M',
            brand: 'Canon',
            description: 'Desain ramping, performa maksimal.'
          },
          {
            image: '/images/banner/lxir320.png',
            title: 'RollToRoll UV Printer LXiR320',
            brand: 'Jetrix',
            description: 'Warna lebih hidup, daya tahan lebih lama.'
          },
          {
            image: '/images/banner/prestos.png',
            title: 'KORNIT Presto S',
            brand: 'Kornit',
            description: 'Solusi cetak, untuk kain berkualitas.'
          },
        ],
        start() {
          setInterval(() => this.next(), 4000);
        },
        next() {
          this.activeIndex = (this.activeIndex + 1) % this.slides.length;
        },
        prev() {
          this.activeIndex = (this.activeIndex - 1 + this.slides.length) % this.slides.length;
        }
      };
    }
  </script>
@endsection
