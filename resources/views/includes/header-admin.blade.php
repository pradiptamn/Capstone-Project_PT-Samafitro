<header class="bg-gray-900 border-b border-gray-800 h-16 py-6 flex items-center justify-between px-6 sticky top-0 z-30">

  <button @click="sidebarOpen = true" class="text-gray-400 hover:text-white focus:outline-none lg:hidden">
    <i class="fas fa-bars text-xl"></i>
  </button>

  <div class="hidden lg:block"></div>

  <div class="flex items-center space-x-4">

    <a href="{{ route('User.profile.index') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition">
      <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name ?? 'Admin' }}</span>
      <img src="/images/profile.png" class="h-8 w-8 rounded-full border border-gray-600">
    </a>

    <div class="h-6 w-px bg-gray-700"></div>

    <form action="{{ route('logout') }}" method="POST" class="inline">
      @csrf
      <button type="submit"
        class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors flex items-center gap-1">
        <i class="fas fa-sign-out-alt"></i>
        <span class="hidden md:inline">Logout</span>
      </button>
    </form>
  </div>
</header>