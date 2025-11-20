<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Samafiltro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">

  <nav class="bg-white shadow-md border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <a href="#" class="flex-shrink-0 flex items-center">
            <span class="font-bold text-xl text-gray-800">Samafiltro</span>
          </a>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <a href="#"
              class="border-b-2 border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
              Dashboard
            </a>
            <a href="#"
              class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
              Profile
            </a>
          </div>
        </div>

        <div class="flex items-center">
          <div class="ml-3 relative flex items-center gap-4">
            <div class="text-sm font-medium text-gray-500">
              Halo, {{ Auth::user()->name ?? 'User' }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
          <h3 class="text-lg font-bold mb-2">Selamat Datang di Dashboard User!</h3>
          <p class="text-gray-600">Anda berhasil login sebagai <span
              class="font-semibold">{{ Auth::user()->email }}</span>.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h4 class="text-gray-500 text-sm font-medium uppercase">Total Pengguna</h4>
          <p class="mt-2 text-3xl font-bold text-gray-900">1,240</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h4 class="text-gray-500 text-sm font-medium uppercase">Status Server</h4>
          <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
            Online
          </span>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h4 class="text-gray-500 text-sm font-medium uppercase">Notifikasi</h4>
          <p class="mt-2 text-3xl font-bold text-gray-900">5</p>
        </div>
      </div>

    </div>
  </div>

</body>

</html>