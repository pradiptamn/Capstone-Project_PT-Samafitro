<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil - Samafitro</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center p-4 py-6 md:py-10">

  <div class="w-full max-w-5xl">

    <div class="bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-700">

      <div class="h-20 md:h-24 bg-gradient-to-r from-blue-700 to-indigo-800 relative flex items-center px-4 md:px-6">
        <a href="{{ route('profile.index') }}"
          class="bg-black/30 hover:bg-black/50 text-white p-2 px-3 rounded-full transition backdrop-blur-sm text-xs md:text-sm flex items-center gap-2">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-lg md:text-xl font-bold text-white ml-3 md:ml-4">Edit Informasi Akun</h1>
      </div>

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-8">
        @csrf
        @method('PUT')

        @if (session('success'))
          <div
            class="mb-6 p-4 bg-green-500/10 border border-green-500 text-green-400 rounded-xl flex items-center gap-3 text-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-6 p-4 bg-red-500/10 border border-red-500 text-red-400 rounded-xl">
            <ul class="list-disc list-inside text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <div
            class="lg:col-span-1 flex flex-col items-center border-b lg:border-b-0 lg:border-r border-gray-700 pb-6 lg:pb-0">
            <div class="relative group cursor-pointer" onclick="document.getElementById('photo-input').click()">
              <img id="profile-preview"
                src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff&size=128' }}"
                class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full border-4 border-gray-700 shadow-xl group-hover:opacity-75 transition">

              <div
                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                <i class="fas fa-camera text-white text-3xl drop-shadow-md"></i>
              </div>

              <input type="file" name="photo" id="photo-input" class="hidden" accept="image/*"
                onchange="previewProfile(event)">
            </div>
            <p class="text-xs text-gray-400 mt-3 text-center">Klik foto untuk mengganti.<br>Max 2MB (JPG/PNG)</p>
          </div>

          <div class="lg:col-span-2 space-y-8">

            <div>
              <h3
                class="text-blue-400 font-bold text-sm uppercase tracking-wider mb-4 border-b border-gray-700 pb-2 flex items-center">
                <i class="fas fa-user-circle mr-2"></i> Identitas Diri
              </h3>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <div class="lg:col-span-2">
                  <label class="block text-sm text-gray-400 mb-1">Nama Lengkap</label>
                  <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                </div>

                <div>
                  <label class="block text-sm text-gray-400 mb-1">Email (Readonly)</label>
                  <input type="email" value="{{ $user->email }}" readonly
                    class="w-full bg-gray-900 border border-gray-700 text-gray-500 rounded-lg px-4 py-2.5 cursor-not-allowed">
                </div>

                <div>
                  <label class="block text-sm text-gray-400 mb-1">Nomor Telepon</label>
                  <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
                </div>

                <div class="lg:col-span-2">
                  <label class="block text-sm text-gray-400 mb-1">Alamat Lengkap</label>
                  <textarea name="address" rows="2"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">{{ old('address', $user->address) }}</textarea>
                </div>
              </div>
            </div>

            <div>
              <h3
                class="text-purple-400 font-bold text-sm uppercase tracking-wider mb-4 border-b border-gray-700 pb-2 flex items-center">
                <i class="fas fa-building mr-2"></i> Data Perusahaan
              </h3>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <div class="lg:col-span-2">
                  <label class="block text-sm text-gray-400 mb-1">Nama Perusahaan</label>
                  <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-500 transition">
                </div>

                <div>
                  <label class="block text-sm text-gray-400 mb-1">NPWP</label>
                  <input type="text" name="npwp" value="{{ old('npwp', $user->npwp) }}"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-500 transition">
                </div>

                <div>
                  <label class="block text-sm text-gray-400 mb-1">NIK (KTP)</label>
                  <input type="text" name="ktp_number" value="{{ old('ktp_number', $user->ktp_number) }}"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-500 transition">
                </div>

                <div class="lg:col-span-2">
                  <label class="block text-sm text-gray-400 mb-2">Foto KTP</label>
                  <div
                    class="flex flex-col sm:flex-row items-start gap-4 p-4 bg-gray-700/30 rounded-xl border border-gray-600 border-dashed">
                    <div class="shrink-0">
                      @if ($user->ktp_photo)
                        <img id="ktp-preview" src="{{ asset('storage/' . $user->ktp_photo) }}"
                          class="h-24 w-full sm:w-32 object-cover rounded-md border border-gray-500">
                      @else
                        <img id="ktp-preview"
                          class="h-24 w-full sm:w-32 object-cover rounded-md border border-gray-500 hidden">
                        <div id="ktp-placeholder"
                          class="h-24 w-full sm:w-32 flex items-center justify-center bg-gray-700 rounded-md border border-gray-500 text-gray-400 text-xs">
                          No Image
                        </div>
                      @endif
                    </div>
                    <div class="flex-1 w-full">
                      <input type="file" name="ktp_photo" id="ktp-input"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-500 cursor-pointer"
                        onchange="previewKTP(event)">
                      <p class="text-xs text-gray-500 mt-2">Max 2MB (JPG/PNG)</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <h3
                class="text-red-400 font-bold text-sm uppercase tracking-wider mb-4 border-b border-gray-700 pb-2 flex items-center">
                <i class="fas fa-lock mr-2"></i> Ganti Password
              </h3>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="lg:col-span-2">
                  <label class="block text-sm text-gray-400 mb-1">Password Lama</label>
                  <input type="password" name="current_password" placeholder="Kosongkan jika tidak ganti"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-red-500 transition">
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Password Baru</label>
                  <input type="password" name="new_password"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-red-500 transition">
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Konfirmasi Password</label>
                  <input type="password" name="new_password_confirmation"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-red-500 transition">
                </div>
              </div>
              <a href="{{ route('password.request') }}" class="text-gray-300 text-sm hover:underline">Lupa
                Password?</a>
            </div>

            <div class="pt-1">
              <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
              </button>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    function previewProfile(event) {
      const reader = new FileReader();
      reader.onload = function() {
        document.getElementById('profile-preview').src = reader.result;
      };
      if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
      }
    }

    function previewKTP(event) {
      const reader = new FileReader();
      const output = document.getElementById('ktp-preview');
      const placeholder = document.getElementById('ktp-placeholder');

      reader.onload = function() {
        output.src = reader.result;
        output.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
      };

      if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
      }
    }
  </script>
</body>

</html>
