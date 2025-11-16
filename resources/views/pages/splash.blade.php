<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PT Samafitro Bandung</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: black;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: opacity 1s ease;
      opacity: 1;
    }

    .fade-out-body {
      opacity: 0;
    }

    .logo {
      width: 60vw;
      max-width: 440px;
      min-width: 120px;
      height: auto;
      max-height: 38vh;
      object-fit: contain;
      opacity: 0;
      filter: grayscale(0) brightness(1);
      transition:
        opacity 2s ease,
        filter 3s ease;
      display: block;
      margin: 0 auto;
    }

    @media (max-width: 900px) {
      .logo {
        width: 70vw;
        max-width: 320px;
        min-width: 80px;
        max-height: 30vh;
      }
    }

    @media (max-width: 600px) {
      .logo {
        width: 80vw;
        max-width: 220px;
        min-width: 60px;
        max-height: 22vh;
      }
    }

    .fade-in {
      opacity: 1;
    }

    .gray {
      filter: grayscale(1) brightness(1);
    }

    .fade-out {
      opacity: 0;
    }
  </style>
</head>

<body id="splash-body">
  <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo" id="logo" />

  <script>
    window.onload = function() {
      const logo = document.getElementById('logo');
      const body = document.getElementById('splash-body');

      // 1. Fade in logo
      setTimeout(() => {
        logo.classList.add('fade-in');
      }, 100);

      // 2. Grayscale
      setTimeout(() => {
        logo.classList.add('gray');
      }, 2500);

      // 3. Fade out logo
      setTimeout(() => {
        logo.classList.add('fade-out');
      }, 4300);

      // 4. Fade out seluruh body
      setTimeout(() => {
        body.classList.add('fade-out-body');
      }, 5000);

      // 5. Redirect setelah halaman hitam
      setTimeout(() => {
        window.location.href = "{{ url('/beranda') }}";
      }, 6000);
    };
  </script>
</body>

</html>
