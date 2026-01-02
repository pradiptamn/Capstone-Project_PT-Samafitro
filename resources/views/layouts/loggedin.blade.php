<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Samafitro')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" />

  <style>
    .floating-wa {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 5;
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

  @yield('additional-styles')
</head>

<body class="bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white font-sans">
  @include('includes.header-loggedin')

  <main>
    @yield('content')
  </main>

  {{-- Floating WhatsApp --}}
  <a href="https://wa.me/6281234567890" target="_blank" class="floating-wa">
    <img src="{{ asset('images/buttonwa.png') }}" alt="WhatsApp">
  </a>

  @include('includes.footer-loggedin')

  @yield('additional-scripts')

  <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
