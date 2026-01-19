<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pertanyaan Keamanan - Samafitro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-200 p-4">
  <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="flex flex-col lg:flex-row">

      <div class="lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-900 to-gray-700">
        <a href="{{ route('password.request') }}" class="text-white text-base hover:text-gray-300">
          &#8592; Ganti Email
        </a>

        <div class="text-white mt-8">
          <h2 class="text-2xl md:text-3xl font-bold mb-3">Pertanyaan Keamanan</h2>
          <p class="text-gray-300 mb-6">Jawab pertanyaan berikut untuk memverifikasi identitas Anda.</p>

          <div class="mb-6 p-4 bg-gray-800 border-l-4 border-blue-500 rounded text-blue-200 italic">
            "{{ $question }}"
          </div>

          <form method="POST" action="{{ route('password.challenge.verify') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-6">
              <label class="block text-gray-200 mb-2" for="answer">Jawaban Anda</label>
              <input id="answer" type="text" name="answer"
                class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Ketik jawaban..." required autofocus autocomplete="off">
              @error('answer')
                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
              @enderror
            </div>

            <button type="submit"
              class="w-full py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-200 mb-4">
              Verifikasi & Reset
            </button>
          </form>

          <div class="border-t border-gray-600 pt-4 mt-4 text-center">
            <p class="text-gray-400 text-sm mb-2">Lupa jawabannya?</p>
            <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <input type="hidden" name="email" value="{{ $email }}">
              <button type="submit"
                class="text-blue-300 hover:text-white text-sm underline bg-transparent border-none p-0">
                Kirim link reset ke Email saya
              </button>
            </form>
          </div>
        </div>
      </div>

      <div
        class="hidden lg:flex flex-col items-center justify-center lg:w-1/2 p-8 md:p-12 bg-gradient-to-br from-gray-100 to-gray-300">
        <img src="{{ asset('images/samafitro-bandung.png') }}" alt="Samafitro Logo" class="rounded mx-auto mb-8 w-1/2">
        <h2 class="text-gray-700 text-2xl font-bold mb-4">Verifikasi Akun</h2>
        <p class="text-gray-600 mb-8 text-center px-8">Metode ini membantu Anda mereset password tanpa perlu membuka
          email, asalkan Anda mengingat jawaban rahasia Anda.</p>
      </div>
    </div>
  </div>
</body>

</html>