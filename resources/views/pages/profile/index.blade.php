<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya - Samafitro</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="//unpkg.com/alpinejs" defer></script>

  <style>
    /* Custom Scrollbar untuk Modal */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #1f2937;
    }

    ::-webkit-scrollbar-thumb {
      background: #4b5563;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #6b7280;
    }
  </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center p-4">

  <div x-data="{ showKtpModal: false }" class="w-full max-w-2xl">

    <div class="bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-700 relative">

      <div class="h-32 bg-gradient-to-r from-blue-700 to-indigo-800 relative">
        <a href="{{ route('dashboard') }}"
          class="absolute top-4 left-4 bg-black/30 hover:bg-black/50 text-white p-2 px-3 rounded-full transition backdrop-blur-sm text-sm">
          <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
      </div>

      <div class="px-6 pb-6 relative">
        <div class="flex justify-center -mt-16 mb-4">
          <div class="relative">
            @if ($user->photo)
              <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil"
                class="w-32 h-32 object-cover rounded-full border-4 border-gray-800 shadow-xl bg-gray-700">
            @else
              <img
                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128&bold=true"
                alt="Default Avatar" class="w-32 h-32 object-cover rounded-full border-4 border-gray-800 shadow-xl">
            @endif
          </div>
        </div>

        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
          <p class="text-gray-400 text-sm mt-1">
            <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-700 hover:border-gray-600 transition">
            <div class="flex items-center gap-3 mb-2">
              <div class="p-2 bg-blue-500/20 text-blue-400 rounded-lg">
                <i class="fas fa-building"></i>
              </div>
              <span class="text-xs font-bold text-gray-400 uppercase">Nama Perusahaan</span>
            </div>
            <p class="text-lg text-white font-medium pl-1">
              {{ $user->company_name ?? '-' }}
            </p>
          </div>

          <div x-data="{ visible: false }"
            class="bg-gray-700/50 p-4 rounded-xl border border-gray-700 hover:border-gray-600 transition">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-green-500/20 text-green-400 rounded-lg">
                  <i class="fas fa-phone-alt"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">No. Telepon</span>
              </div>
              <button @click="visible = !visible" class="text-gray-400 hover:text-white transition focus:outline-none">
                <i class="fas" :class="visible ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
            <p class="text-lg text-white font-medium pl-1 font-mono">
              <span x-show="!visible">
                {{ substr($user->phone, 0, 4) }}****{{ substr($user->phone, -2) }}
              </span>
              <span x-show="visible" x-cloak>
                {{ $user->phone ?? '-' }}
              </span>
            </p>
          </div>

          <div x-data="{ visible: false }"
            class="bg-gray-700/50 p-4 rounded-xl border border-gray-700 hover:border-gray-600 transition">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-500/20 text-purple-400 rounded-lg">
                  <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">NPWP</span>
              </div>
              <button @click="visible = !visible" class="text-gray-400 hover:text-white transition focus:outline-none">
                <i class="fas" :class="visible ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
            <p class="text-lg text-white font-medium pl-1 font-mono tracking-wider">
              <span x-show="!visible">
                **.***.***.*-{{ substr($user->npwp, -3) }}
              </span>
              <span x-show="visible" x-cloak>
                {{ $user->npwp ?? '-' }}
              </span>
            </p>
          </div>

          <div x-data="{ visible: false }"
            class="bg-gray-700/50 p-4 rounded-xl border border-gray-700 hover:border-gray-600 transition">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-500/20 text-yellow-400 rounded-lg">
                  <i class="fas fa-id-card"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase">NIK (KTP)</span>
              </div>
              <button @click="visible = !visible" class="text-gray-400 hover:text-white transition focus:outline-none">
                <i class="fas" :class="visible ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
            <p class="text-lg text-white font-medium pl-1 font-mono tracking-widest">
              <span x-show="!visible">
                ****************
              </span>
              <span x-show="visible" x-cloak>
                {{ $user->ktp_number ?? '-' }}
              </span>
            </p>
          </div>

          <div
            class="md:col-span-2 bg-gray-700/50 p-4 rounded-xl border border-gray-700 hover:border-gray-600 transition flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-red-500/20 text-red-400 rounded-lg">
                <i class="fas fa-image"></i>
              </div>
              <div>
                <span class="text-xs font-bold text-gray-400 uppercase block">Foto KTP</span>
                <span class="text-xs text-gray-500">File tersimpan aman</span>
              </div>
            </div>

            @if ($user->ktp_photo)
              <button @click="showKtpModal = true"
                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-eye"></i> Lihat Foto
              </button>
            @else
              <span class="text-gray-500 text-sm italic pr-2">Belum upload</span>
            @endif
          </div>

        </div>

        <div class="mt-8 pt-6 border-t border-gray-700 flex flex-col md:flex-row gap-4">
          <a href="{{ route('profile.edit') }}"
            class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition text-center">
            <i class="fas fa-user-edit mr-2"></i> Edit Profil
          </a>

          <form action="{{ route('logout') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit"
              class="w-full bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-700 hover:text-red-400 font-bold py-3 px-4 rounded-xl transition">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>

      </div>
    </div>

    <div x-show="showKtpModal" style="display: none;" x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">

      <div @click.away="showKtpModal = false"
        class="bg-gray-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-600">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
          <h3 class="text-white font-bold text-lg">Pratinjau KTP</h3>
          <button @click="showKtpModal = false" class="text-gray-400 hover:text-white text-xl">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6 flex justify-center bg-gray-800">
          @if ($user->ktp_photo)
            <img src="{{ asset('storage/' . $user->ktp_photo) }}" alt="KTP User"
              class="max-w-full h-auto rounded-lg shadow-md border border-gray-600">
          @else
            <p class="text-gray-400">File tidak ditemukan</p>
          @endif
        </div>
        <div class="p-4 bg-gray-900 text-center">
          <button @click="showKtpModal = false" class="text-gray-400 hover:text-white text-sm underline">
            Tutup Tampilan
          </button>
        </div>
      </div>
    </div>

  </div>

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

</body>

</html>
