<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
  x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-950 border-r border-gray-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">

  <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-800">
    <a href="#" class="flex items-center gap-2">
      <img src="{{ asset('images/logo-samafitro.png') }}" alt="Samafitro" class="h-8">
    </a>
  </div>

  <nav class="mt-5 px-4 space-y-1">

    <a href="{{ route('admin.dashboard') }}"
      class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
      <i class="fas fa-home w-6"></i>
      Edit Beranda
    </a>

    <a href="{{ route('admin.produk.index') }}"
      class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.produk.*') ? 'bg-gray-800 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
      <i class="fas fa-box w-6"></i>
      Edit Produk
    </a>

    <a href="{{ route('admin.promos.index') }}"
      class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.promos.*') ? 'bg-gray-800 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
      <i class="fas fa-tags w-6"></i>
      Edit Promo
    </a>

    <a href="{{ route('admin.articles.index') }}"
      class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-gray-800 text-indigo-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
      <i class="fas fa-newspaper w-6"></i>
      Edit Artikel
    </a>
  </nav>
</div>