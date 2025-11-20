<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun | Samafitro</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Gaya umum untuk dropdown */
    select {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;

      font-size: 14px;
      line-height: 1.5;
      padding: 8px 32px 8px 8px;
      /* Padding untuk teks dan panah */
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
      color: #333;
      transition: border-color 0.3s ease;

      /* Hilangkan panah default browser */
      &::-ms-expand {
        display: none;
      }
    }

    /* Opsi dalam dropdown */
    select option {
      font-size: 14px;
      padding: 8px;
      background-color: #fff;
      color: #333;
      border-bottom: 1px solid #eee;
      /* Garis pemisah antar opsi */

      /* Gaya ketika opsi dipilih */
      &:hover,
      &:focus {
        background-color: #e0f7fa;
        /* Warna latar belakang saat hover/focus */
      }
    }

    /* Scrollbar Horizontal (opsional) */
    select::-webkit-scrollbar {
      width: 6px;
      /* Lebar scrollbar */
    }

    select::-webkit-scrollbar-thumb {
      background-color: #aaa;
      /* Warna thumb scrollbar */
      border-radius: 4px;
    }

    select::-webkit-scrollbar-track {
      background-color: #f1f1f1;
      /* Warna track scrollbar */
    }

    /* Panah kustom */
    .dropdown-arrow {
      pointer-events: none;
      /* Hindari interaksi dengan panah */
      transform: rotate(90deg);
      /* Rotasi panah */
    }
  </style>

</head>

<body class="bg-gray-100 min-h-screen">

  <!-- Flex layout: stack on small screens, row on large screens -->
  <div class="flex flex-col lg:flex-row min-h-screen">

    <!-- Left Panel (Informasi) - Hanya tampil di layar besar -->
    <div class="lg:w-1/2 bg-gradient-to-br from-gray-100 to-gray-300 p-6 lg:p-10 text-center hidden lg:block">
      <a href="{{ route('login') }}" class=" text-gray-600 hover:text-gray-800 text-xl font-semibold mb-8">
        ← samafitro
      </a>
      <div class="mt-10">
        <h2 class="text-4xl font-bold mb-4">Hi!</h2>
        <p class="text-gray-600 mb-6">Anda sudah memiliki akun?</p>
        <a href="{{ route('login') }}"
          class="inline-block bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
          Masuk Disini
        </a>
      </div>
    </div>

    <!-- Right Panel (Form Daftar) -->
    <div class="w-full lg:w-1/2 bg-gray-900 text-white p-6 lg:p-10 overflow-y-auto">
      <h2 class="text-2xl md:text-3xl font-semibold mb-8 text-center">Daftar Akun</h2>

      <!-- Tampilkan Error -->
      @if ($errors->any())
        <div class="bg-red-900 border border-red-600 text-red-200 p-4 rounded mb-6">
          <strong class="font-medium">Terjadi kesalahan:</strong>
          <ul class="list-disc pl-5 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Data Pribadi -->
          <div>
            <label class="block mb-1 text-sm font-medium">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Masukkan nama lengkap" required>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Masukkan email" required>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="08xxxxxxx" required>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Alamat</label>
            <input type="text" name="address" value="{{ old('address') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Alamat lengkap">
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Kata Sandi</label>
            <input type="password" name="password"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Minimal 8 karakter" required>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Minimal 8 karakter" required>
          </div>

          <!-- Data KTP -->
          <div>
            <label class="block mb-1 text-sm font-medium">Nomor KTP</label>
            <input type="text" name="ktp_number" value="{{ old('ktp_number') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Sesuai dengan KTP" required>
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">Unggah Foto KTP</label>
            <input type="file" name="ktp_photo" class="w-full p-2 bg-gray-800 rounded border border-gray-600 text-sm"
              accept=".jpg,.jpeg,.png" required>
          </div>

          <!-- Profil Organisasi -->
          <div>
            <label class="block mb-1 text-sm font-medium">
              Nama Perusahaan
              <span class="text-gray-400 text-xs font-normal">(opsional)</span>
            </label>
            <input type="text" name="company_name" value="{{ old('company_name') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Misalnya: PT Samafiltro Indonesia">
          </div>
          <div>
            <label class="block mb-1 text-sm font-medium">
              NPWP
              <span class="text-gray-400 text-xs font-normal">(opsional)</span>
            </label>
            <input type="text" name="npwp" value="{{ old('npwp') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Sesuai dengan NPWP">
          </div>

          <!-- Pertanyaan Keamanan - Dropdown lebih kecil di mobile -->
          <div>
            <label class="block mb-1 text-sm font-medium">Pertanyaan Keamanan</label>
            <div class="relative max-w-xs w-full">
              <!-- Select Dropdown -->
              <select name="security_question"
                class="w-full p-2 bg-white border border-blue-500 rounded text-sm text-gray-700
                   focus:outline-none focus:ring-2 focus:ring-blue-200
                   appearance-none h-10 leading-tight
                   transition ease-in-out duration-200
                   focus:border-blue-500"
                required>
                <option value="" disabled selected>Pilih pertanyaan</option>
                <option value="ibu_kandung">Siapa nama ibu kandung Anda?</option>
                <option value="sekolah_pertama">Apa nama sekolah pertama Anda?</option>
                <option value="makanan_favorit">Apa makanan favorit Anda?</option>
              </select>

              <!-- Panah Kustom (Ganti Panah Default) -->
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>
          <!-- Jawaban Keamanan -->
          <div>
            <label class="block mb-1 text-sm font-medium">Jawaban Keamanan</label>
            <input type="text" name="security_answer" value="{{ old('security_answer') }}"
              class="w-full p-2 bg-gray-800 rounded border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
              placeholder="Sesuaikan dengan pertanyaan keamanan" required>
          </div>

        </div>
        <!-- Tombol Submit -->
        <div class="text-center pt-6">
          <button type="submit"
            class="w-full md:w-auto bg-blue-600 hover:bg-blue-500 text-white px-8 py-2 rounded font-medium transition">
            Daftar Sekarang
          </button>

          <!-- Link "Sudah punya akun?" - Hanya muncul di perangkat kecil -->
          <p class="mt-4 text-sm text-gray-300 lg:hidden">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-white hover:underline font-medium">
              Masuk disini
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>

</body>

</html>