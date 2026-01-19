<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Kurir Samafitro</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-900 text-gray-100 font-sans pb-20">
  <div class="bg-gray-800 p-4 sticky top-0 z-50 shadow-md border-b border-gray-700 flex justify-between items-center">
    <div class="flex items-center gap-3">
      <img src="{{ asset('images/logo-samafitro.png') }}" class="h-8">
      <span class="font-bold text-gray-200">Kurir App</span>
    </div>
    <div class="flex items-center gap-3">
      <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="text-red-400 hover:text-red-300"><i class="fas fa-sign-out-alt"></i></button>
      </form>
    </div>
  </div>

  <main class="p-4">
    @if (session('success'))
      <div class="bg-green-600 text-white p-3 rounded mb-4 text-sm shadow-lg animate-pulse">
        {{ session('success') }}
      </div>
    @endif
    @yield('content')
  </main>

  <nav class="fixed bottom-0 w-full bg-gray-800 border-t border-gray-700 p-3 flex justify-around shadow-lg z-50">
    <a href="{{ route('courier.dashboard') }}"
      class="text-center {{ request()->routeIs('courier.dashboard') ? 'text-blue-400' : 'text-gray-500' }}">
      <i class="fas fa-box text-xl"></i>
      <span class="text-[10px] block">Tugas</span>
    </a>
    <a href="{{ route('courier.profile.edit') }}"
      class="text-center {{ request()->routeIs('courier.profile.edit') ? 'text-blue-400' : 'text-gray-500' }}">
      <i class="fas fa-user text-xl"></i>
      <span class="text-[10px] block">Profil</span>
    </a>
  </nav>

</body>

</html>
