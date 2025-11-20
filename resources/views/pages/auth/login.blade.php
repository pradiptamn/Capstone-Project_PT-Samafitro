<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Samafiltro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-200 p-4">
  <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="flex flex-col lg:flex-row">
      <div class="lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-900 to-gray-700">
        <a href="/" class=" text-white text-base">
          &#8592; Kembali
        </a>
        <div class="text-white mt-8">
          <h2 class="text-2xl md:text-3xl font-bold mb-3">Masuk Ke Akun Anda</h2>
          <p class="text-gray-300 mb-6">Silahkan masukkan email dan kata sandi yang sudah terdaftar</p>

          @if (session('status'))
            <div
              class="mb-6 p-4 bg-green-600 text-white rounded-lg text-sm font-medium shadow-md border border-green-500">
              {{ session('status') }}
            </div>
          @endif
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
              <label class="block text-gray-200 mb-2" for="email">Alamat Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan email" required autofocus>
              @error('email')
                <span class="text-red-400 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-6 relative">
              <label class="block text-gray-200 mb-2" for="password">Kata Sandi</label>
              <input id="password" type="password" name="password"
                class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan password" required>
              <span onclick="togglePassword()" class="absolute top-10 right-3 text-gray-300 cursor-pointer">
                👁️
              </span>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
              <a href="{{ route('password.request') }}" class="text-gray-300 text-sm hover:underline">Lupa Password?</a>
              <span class="text-gray-300 text-sm">atau <a href="/register" class="underline">Daftar</a></span>
            </div>
            <button type="submit"
              class="w-full py-2 rounded bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-200">
              Masuk
            </button>
          </form>
        </div>
      </div>

      <div
        class="hidden lg:flex flex-col items-center justify-center lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-100 to-gray-300">

        <img src="{{ asset('images/samafitro-bandung.png') }}" alt="Samafiltro Logo" class="rounded mx-auto mb-8 w-1/2">
        <h2 class="text-gray-700 text-2xl font-bold mb-4">Selamat Datang !</h2>
        <p class="text-gray-600 mb-8">Anda belum memiliki akun?</p>
        <a href="/register"
          class="inline-block py-2 px-6 rounded bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-200">
          Daftar Disini
        </a>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const password = document.getElementById('password');
      password.type = password.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>

</html>