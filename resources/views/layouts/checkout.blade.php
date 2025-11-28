<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Checkout - Samafitro')</title>

  {{-- Vite & Scripts --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- Alpine JS (Untuk interaksi kecil jika perlu) --}}
  <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

  {{-- HEADER SEDERHANA (Hanya Logo) --}}
  <div class="bg-gray-950 border-b border-gray-800 py-4 shadow-md">
    <div class="container mx-auto text-center">
      <a href="{{ route('dashboard') }}" class="inline-block">
        <img src="{{ asset('images/logo-samafitro.png') }}" alt="Samafitro" class="h-12 w-auto object-contain">
      </a>
    </div>
  </div>

  {{-- KONTEN UTAMA --}}
  <main>
    @yield('content')
  </main>

  {{-- FOOTER SEDERHANA --}}
  <footer class="bg-gray-950 py-6 text-center text-gray-600 text-sm mt-auto border-t border-gray-800">
    <p>&copy; {{ date('Y') }} PT Samafitro. All rights reserved.</p>
  </footer>

  {{-- SCRIPT UNTUK AKSI HAPUS --}}
  <script>
    async function removeCartItem(productId) {
      if (!confirm('Hapus produk ini dari pesanan?')) return;

      try {
        // Tampilkan Loading Overlay
        document.body.innerHTML += `
                    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; justify-content: center; align-items: center; color: white; flex-direction: column;">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3 text-blue-500"></i>
                        <p>Memperbarui pesanan...</p>
                    </div>
                `;

        const response = await fetch("{{ route('cart.remove') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            product_id: productId
          })
        });

        const data = await response.json();

        if (data.success) {
          // Reload halaman agar perhitungan harga di Controller dihitung ulang
          window.location.reload();
        } else {
          alert('Gagal menghapus item');
          window.location.reload(); // Reload anyway untuk safety
        }
      } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan');
        window.location.reload();
      }
    }
  </script>

</body>

</html>
