<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Baru - Samafitro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-200 p-4">
  <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="flex flex-col lg:flex-row">

      <div class="lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-900 to-gray-700">
        <div class="text-white mt-4">
          <h2 class="text-2xl md:text-3xl font-bold mb-3">Password Baru</h2>
          <p class="text-gray-300 mb-8">Silakan buat kata sandi baru untuk akun Anda.</p>

          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
              <label class="block text-gray-200 mb-2" for="email">Alamat Email</label>
              <input id="email" type="email" name="email" value="{{ old('email', $email) }}"
                class="w-full px-4 py-2 rounded bg-gray-800 text-gray-400 border border-gray-600 cursor-not-allowed"
                readonly required>
              @error('email')
                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-4 relative">
              <label class="block text-gray-200 mb-2" for="password">Kata Sandi Baru</label>
              <input id="password" type="password" name="password"
                class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Password baru" required autofocus>
              <span onclick="togglePassword('password')" class="absolute top-10 right-3 text-gray-300 cursor-pointer">
                👁️
              </span>
              @error('password')
                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-6 relative">
              <label class="block text-gray-200 mb-2" for="password_confirmation">Konfirmasi Password</label>
              <input id="password_confirmation" type="password" name="password_confirmation"
                class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Ulangi password" required>
              <span onclick="togglePassword('password_confirmation')"
                class="absolute top-10 right-3 text-gray-300 cursor-pointer">
                👁️
              </span>
            </div>

            <button type="submit"
              class="w-full py-2 rounded bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-200">
              Simpan Password
            </button>
          </form>
        </div>
      </div>

      <div
        class="hidden lg:flex flex-col items-center justify-center lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-100 to-gray-300">
        <img src="{{ asset('images/Samafitro-Hitam-Persegi-Panjang.png') }}" alt="Samafitro Logo"
          class="rounded mx-auto mb-8 w-1/2">
        <h2 class="text-gray-700 text-2xl font-bold mb-4">Hampir Selesai!</h2>
        <p class="text-gray-600 mb-8 text-center">Pastikan password baru Anda kuat dan mudah diingat.</p>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(id) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>

</html>