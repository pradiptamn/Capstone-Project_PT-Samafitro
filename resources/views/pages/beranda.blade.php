@extends('layouts.default')
@section('content')
  <style>
    /* Reset & Global */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background: radial-gradient(circle, #7a7a7a 0%, #000 100%) fixed;
      color: white;
      line-height: 1.6;
    }

    /* Header */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
      padding: 8px 5vw;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #333;
    }

    .logo {
      max-height: 50px;
      margin-left: 0;
    }

    /* Desktop nav */
    @media (min-width: 1025px) {
      .nav-wrapper {
        display: flex !important;
        position: static !important;
        max-height: none !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        flex-direction: row !important;
        align-items: center !important;
        background: none !important;
        box-shadow: none !important;
        padding: 0 0 0 40px !important;
        margin-left: 30px !important;
        gap: 0 !important;
      }

      .nav-left {
        display: flex !important;
        flex-direction: row !important;
        gap: 40px !important;
        padding-left: 0 !important;
        align-items: center !important;
      }

      .nav-right {
        display: flex !important;
        flex-direction: row !important;
        gap: 16px !important;
        align-items: center !important;
        padding-left: 0 !important;
      }

      .nav-left a,
      .nav-right a {
        width: auto !important;
        padding: 8px 18px !important;
        border-radius: 8px !important;
        border-bottom: none !important;
        font-size: 0.95rem !important;
        display: inline-block !important;
      }
    }

    /* Mobile nav */
    @media (max-width: 1024px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        padding: 6px 1vw;
      }

      .nav-wrapper {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        margin-left: 0;
        padding-left: 0;
        gap: 6px;
      }

      .nav-left,
      .nav-right {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding-left: 10;
      }

      .logo {
        margin-left: 0;
        margin-bottom: 6px;
      }

      nav a {
        font-size: 0.75rem;
        padding: 4px 6px;
        border-radius: 4px;
      }
    }

    /* --- BAGIAN LAIN DIBIARKAN SAMA --- */

    .hero {
      position: relative;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 10vh 5vw 60px 5vw;
      /* tambah padding-bottom agar konten tidak terlalu pendek dan footer terdorong ke bawah */
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
      overflow: hidden;
    }

    .hero-content {
      width: 100%;
      max-width: 600px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      padding: 32px 24px;
      border-radius: 18px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.12);
      margin-top: 60px;
    }

    .hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
      opacity: 0;
      animation: fadeSlideUp 1s ease-out forwards;
    }

    .hero p {
      font-size: 1rem;
      margin: 12px 0;
      max-width: 600px;
      opacity: 0;
      animation: fadeSlideUp 1s ease-out forwards;
      animation-delay: 0.4s;
    }

    .shop-btn {
      background-color: white;
      color: black;
      padding: 5px 40px;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      margin-top: 20px;
      cursor: pointer;
      transition: background-color 0.3s;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .shop-btn:hover {
      background-color: #e5e5e5;
    }

    .shop-btn img {
      width: 28px;
      height: 28px;
      margin-left: -15px;
    }

    .address {
      max-width: 300px;
    }

    .social-icons a {
      display: inline-block;
      margin-right: 12px;
      margin-bottom: 8px;
      color: white;
      text-decoration: none;
      font-size: 0.95rem;
    }

    .social-icons a:hover {
      color: #00BFFF;
    }

    @media (max-width: 1024px) {
      .hero {
        min-height: 100vh;
        padding: 6vh 2vw;
      }

      .hero-content {
        max-width: 90vw;
        padding: 24px 12px;
      }

      .hero-content h1 {
        font-size: 1.7rem;
      }

      .hero-content p {
        font-size: 0.95rem;
      }

      .shop-btn {
        font-size: 0.95rem;
        padding: 10px 24px;
        max-width: 220px;
      }
    }

    @media (max-width: 767px) {
      .hero {
        min-height: 100vh;
        padding: 4vh 2vw;
      }

      .hero-content {
        max-width: 98vw;
        padding: 16px 6px;
        margin-top: 30px;
      }

      .hero-content h1 {
        font-size: 1.1rem;
        margin-bottom: 2px;
      }

      .hero-content p {
        font-size: 0.7rem;
      }

      .shop-btn {
        font-size: 0.8rem;
        padding: 8px 12px;
        max-width: 180px;
      }

      .shop-btn img {
        width: 20px;
        height: 20px;
        margin-left: -8px;
      }

      .address img {
        margin-bottom: 10px;
      }

      .social-icons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
      }

      .social-icons a {
        background-color: #1a1a1a;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: background-color 0.3s;
      }

      .social-icons a:hover {
        background-color: #333;
      }
    }

    .social-icons {
      justify-content: center;
    }

    .social-icons a {
      display: block;
      margin: 6px 0;
    }

    /* Ganti video dengan gambar background */
    .hero-bg-desktop,
    .hero-bg-mobile {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    .hero-bg-mobile {
      display: none;
    }

    @media (max-width: 768px) {
      .hero-bg-desktop {
        display: none;
      }

      .hero-bg-mobile {
        display: block;
      }
    }

    /* Animasi teks fade-in geser */
    @keyframes fadeSlideUp {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Animasi shake tombol */
    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      20% {
        transform: translateX(-5px);
      }

      40% {
        transform: translateX(5px);
      }

      60% {
        transform: translateX(-4px);
      }

      80% {
        transform: translateX(4px);
      }
    }

    .shop-btn.shake {
      animation: shake 0.5s ease;
    }

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

  <section class="hero">
    <img src="{{ asset('images/landing1.png') }}" alt="Hero Background Desktop" class="hero-bg-desktop">
    <img src="{{ asset('images/landingmobile.png') }}" alt="Hero Background Mobile" class="hero-bg-mobile">
    <div class="hero-content">
      <h1>PT. Samafitro Bandung</h1>
      <p>Jl. L. L. R.E. Martadinata No.229, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114</p>
      <button class="shop-btn">
        <img src="{{ asset('images/buttonshop.png') }}" alt="Shop Icon">
        Samafitro Shop
      </button>
    </div>
  </section>



  <script>
    const button = document.querySelector('.shop-btn');
    setInterval(() => {
      button.classList.add('shake');
      setTimeout(() => {
        button.classList.remove('shake');
      }, 600);
    }, 1200); // setiap 50 detik
    // Hapus JS hamburger menu di sini, gunakan JS dari includes/header.blade.php saja
  </script>
@endsection
