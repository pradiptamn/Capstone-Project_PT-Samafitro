@extends('layouts.loggedin')

@section('title', 'Hubungi Kami | Samafitro')

@section('content')
  {{-- Container Utama --}}
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans pb-20">

    {{-- 1. Header Section --}}
    <section class="relative pt-12 px-4 text-center">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
          Hubungi <span class="text-blue-500">Kami</span>
        </h1>
        <p class="text-gray-400 text-lg mb-6">
          Jangan ragu untuk menghubungi kami dan temukan solusi terbaik untuk kebutuhan bisnis Anda.
        </p>

        {{-- Jam Kerja Badge --}}
        <div
          class="inline-flex items-center gap-2 bg-gray-800 border border-gray-700 px-4 py-2 rounded-full text-sm text-gray-300 mb-8">
          <i class="far fa-clock text-blue-500"></i>
          <span>Senin – Jumat | 08:00 – 17.00 WIB</span>
        </div>

        {{-- Social Media Icons --}}
        <div class="flex justify-center items-center gap-6 mb-12">
          <a href="https://www.instagram.com/samafitro_bandung/" target="_blank"
            class="text-gray-400 hover:text-pink-500 transition transform hover:scale-110">
            <i class="fa-brands fa-instagram text-3xl"></i>
          </a>
          <a href="https://www.facebook.com/SamafitroBandung" target="_blank"
            class="text-gray-400 hover:text-blue-600 transition transform hover:scale-110">
            <i class="fa-brands fa-facebook-f text-3xl"></i>
          </a>
          <a href="https://www.tiktok.com/@samafitro.bandung" target="_blank"
            class="text-gray-400 hover:text-white transition transform hover:scale-110">
            <i class="fa-brands fa-tiktok text-3xl"></i>
          </a>
          <a href="https://www.youtube.com/@SamafitroBandung" target="_blank"
            class="text-gray-400 hover:text-red-600 transition transform hover:scale-110">
            <i class="fa-brands fa-youtube text-3xl"></i>
          </a>
        </div>
      </div>
    </section>

    {{-- 2. Accordion Section (Alpine.js) --}}
    <section class="container mx-auto px-4 md:px-6 max-w-4xl" x-data="{ activeAccordion: 1 }">

      {{-- ACCORDION ITEM 1: ADMIN --}}
      <div class="mb-4 bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <button @click="activeAccordion = (activeAccordion === 1 ? null : 1)"
          class="w-full px-6 py-4 flex items-center justify-between bg-gray-800 hover:bg-gray-750 transition focus:outline-none">
          <div class="flex items-center gap-3 font-bold text-lg">
            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center text-blue-500">
              <i class="fa-solid fa-user"></i>
            </div>
            Admin Cabang Bandung
          </div>
          <i class="fas fa-chevron-down transition-transform duration-300"
            :class="activeAccordion === 1 ? 'rotate-180' : ''"></i>
        </button>

        <div x-show="activeAccordion === 1" x-collapse class="border-t border-gray-700 bg-gray-900/50">
          <div class="p-6">
            <p class="text-gray-400 mb-4 text-sm">Admin Samafitro Bandung siap membantu kebutuhan umum Anda.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                class="bg-gray-800 p-4 rounded-lg border border-gray-700 flex items-center justify-between hover:border-blue-500 transition group">
                <div>
                  <p class="text-xs text-gray-500 uppercase font-bold">Admin</p>
                  <p class="text-white font-semibold">Admin Bandung</p>
                </div>
                <a href="https://api.whatsapp.com/send/?phone=6285819820008&text&type=phone_number&app_absent=0"
                  target="_blank"
                  class="bg-green-600 hover:bg-green-500 text-white p-2 px-3 rounded-lg text-sm flex items-center gap-2 transition shadow-lg shadow-green-900/20">
                  <i class="fa-brands fa-whatsapp text-lg"></i> Chat
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ACCORDION ITEM 2: SALES --}}
      <div class="mb-4 bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <button @click="activeAccordion = (activeAccordion === 2 ? null : 2)"
          class="w-full px-6 py-4 flex items-center justify-between bg-gray-800 hover:bg-gray-750 transition focus:outline-none">
          <div class="flex items-center gap-3 font-bold text-lg">
            <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center text-purple-500">
              <i class="fa-solid fa-comments-dollar"></i>
            </div>
            Kontak Sales
          </div>
          <i class="fas fa-chevron-down transition-transform duration-300"
            :class="activeAccordion === 2 ? 'rotate-180' : ''"></i>
        </button>

        <div x-show="activeAccordion === 2" x-collapse class="border-t border-gray-700 bg-gray-900/50">
          <div class="p-6">
            <p class="text-gray-400 mb-4 text-sm">Hubungi spesialis kami untuk penawaran produk spesifik.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

              {{-- Sales Item 1 --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Account Manager</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Iman Taufik</p>
                  <a href="https://api.whatsapp.com/send/?phone=6281295597232&text&type=phone_number&app_absent=0"
                    target="_blank" class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 2 --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Production Printing</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=6282115514712&text&type=phone_number&app_absent=0"
                    target="_blank" class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 3 --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Industrial Printing</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=6285759704469&text&type=phone_number&app_absent=0"
                    target="_blank" class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 4 (Canon Copier) --}}
              <div
                class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group sm:col-span-2">
                <p class="text-xs text-purple-400 font-bold mb-2 border-b border-gray-700 pb-2">Excecutive Copier Canon
                </p>
                <div class="grid grid-cols-2 gap-3">
                  <a href="https://api.whatsapp.com/send/?phone=6281323737323" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 1</a>
                  <a href="https://api.whatsapp.com/send/?phone=6281320682020" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 2</a>
                  <a href="https://api.whatsapp.com/send/?phone=6285797247652" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 3</a>
                  <a href="https://api.whatsapp.com/send/?phone=62811726646" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 4</a>
                </div>
              </div>

              {{-- Sales Item 5 --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Manager Business Image Solution</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=628562020005" target="_blank"
                    class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 6 --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Support POS</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=6281214772626" target="_blank"
                    class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 7 (Garmen) --}}
              <div
                class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group sm:col-span-2">
                <p class="text-xs text-purple-400 font-bold mb-2 border-b border-gray-700 pb-2">Excecutive Garmen &
                  Textile</p>
                <div class="grid grid-cols-2 gap-3">
                  <a href="https://api.whatsapp.com/send/?phone=6281573044726" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 1</a>
                  <a href="https://api.whatsapp.com/send/?phone=6282130655559" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 2</a>
                  <a href="https://api.whatsapp.com/send/?phone=628562111988" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 3</a>
                  <a href="https://api.whatsapp.com/send/?phone=6285183277498" target="_blank"
                    class="flex items-center gap-2 text-sm text-gray-300 hover:text-white"><i
                      class="fa-brands fa-whatsapp text-green-500"></i> Sales 4</a>
                </div>
              </div>

              {{-- Sales Item 8 (TKDN) --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Axioo TKDN</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=628995820247" target="_blank"
                    class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

              {{-- Sales Item 9 (S&D) --}}
              <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 hover:border-purple-500 transition group">
                <p class="text-xs text-purple-400 font-bold mb-1">Excecutive S & D</p>
                <div class="flex justify-between items-center">
                  <p class="text-white font-semibold">Sales Team</p>
                  <a href="https://api.whatsapp.com/send/?phone=6282142656655" target="_blank"
                    class="text-green-500 hover:text-green-400 text-2xl"><i
                      class="fa-brands fa-whatsapp-square"></i></a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      {{-- ACCORDION ITEM 3: PHONE --}}
      <div class="mb-4 bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <button @click="activeAccordion = (activeAccordion === 3 ? null : 3)"
          class="w-full px-6 py-4 flex items-center justify-between bg-gray-800 hover:bg-gray-750 transition focus:outline-none">
          <div class="flex items-center gap-3 font-bold text-lg">
            <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center text-green-500">
              <i class="fa-solid fa-phone"></i>
            </div>
            022 720 5555
          </div>
          <i class="fas fa-chevron-down transition-transform duration-300"
            :class="activeAccordion === 3 ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeAccordion === 3" x-collapse class="border-t border-gray-700 bg-gray-900/50">
          <div class="p-6 text-gray-400">
            Silahkan telepon langsung ke kantor Samafitro Bandung pada jam kerja untuk respons cepat.
          </div>
        </div>
      </div>

      {{-- ACCORDION ITEM 4: EMAIL --}}
      <div class="mb-4 bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <button @click="activeAccordion = (activeAccordion === 4 ? null : 4)"
          class="w-full px-6 py-4 flex items-center justify-between bg-gray-800 hover:bg-gray-750 transition focus:outline-none">
          <div class="flex items-center gap-3 font-bold text-lg">
            <div class="w-10 h-10 rounded-lg bg-red-600/20 flex items-center justify-center text-red-500">
              <i class="fa-solid fa-envelope"></i>
            </div>
            Samafitro_bdg@samafitro.co.id
          </div>
          <i class="fas fa-chevron-down transition-transform duration-300"
            :class="activeAccordion === 4 ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeAccordion === 4" x-collapse class="border-t border-gray-700 bg-gray-900/50">
          <div class="p-6 text-gray-400">
            Kirim email untuk pertanyaan detail, permintaan penawaran resmi, atau kerjasama B2B.
            <a href="mailto:Samafitro_bdg@samafitro.co.id" class="text-blue-400 hover:underline block mt-2">Kirim Email
              Sekarang &rarr;</a>
          </div>
        </div>
      </div>

    </section>

  </div>
@endsection
