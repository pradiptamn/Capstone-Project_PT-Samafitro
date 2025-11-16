<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .floating-wa {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .floating-wa img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .floating-wa:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 14px rgba(0, 255, 0, 0.5);
    }
  </style>
  @include('includes.head')
</head>

<body>
  <header>
    @include('includes.header')
  </header>
  <main>
    @yield('content')
  </main>
  <footer>
    @include('includes.footer')
  </footer>
  <!-- Tambahkan ini sebelum tag </body> -->
  <a href="https://api.whatsapp.com/send/?phone=6285819820008&text&type=phone_number&app_absent=0" class="floating-wa"
    target="_blank" aria-label="Chat via WhatsApp">
    <img src="{{ asset('images/buttonwa.png') }}" alt="WhatsApp Button">
  </a>
</body>

</html>
