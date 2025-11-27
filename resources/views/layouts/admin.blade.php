<!DOCTYPE html>
<html lang="id">

<head>
  @include('includes.head')
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

  <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    @include('includes.sidebar')

    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

      @include('includes.header-admin')

      <main class="w-full flex-grow p-6">
        @yield('content')
      </main>

      <footer class="bg-gray-900 border-t border-gray-800 text-center py-4 text-xs text-gray-500">
        &copy; {{ date('Y') }} Cabang PT Samafitro Bandung Oleh Tim Developer Kami.
      </footer>

    </div>
  </div>

</body>

</html>
