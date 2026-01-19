@extends('layouts.default')
@section('content')
  <style>
    /* Animasi Fade In */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .about-text {
      animation: fadeIn 1s ease-out forwards;
      opacity: 0;
      /* Mulai dari transparan */
    }

    .about-text h3,
    .about-text p,
    .about-text em {
      opacity: 0;
      animation: fadeIn 1s ease-out forwards;
    }

    .about-text h3 {
      animation-delay: 0.3s;
    }

    .about-text p {
      animation-delay: 0.6s;
    }

    .about-text em {
      animation-delay: 0.9s;
    }


    /* Animasi Fade In */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .about-images {
      animation: fadeIn 1s ease-out forwards;
      opacity: 0;
      /* Mulai dari transparan */
    }

    .about-header h1 {
      opacity: 0;
      animation: fadeIn 1s ease-out 0.3s forwards;
      display: inline-block;
    }




    /* Produk & Pelanggan Section (dari kantor-cabang) */
    @media (max-width: 480px) {
      .about-section {
        flex-direction: column;
        padding: 10px 4px;
        gap: 10px;
      }

      .about-images {
        display: none !important;
      }

      .image-wrapper {
        position: relative;
        width: 92vw;
        max-width: 340px;
        min-width: 120px;
        border-width: 2px;
        margin: 0 auto;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
        top: 0;
        z-index: 1;
        background: #fff;
      }

      .image-wrapper.atas {
        top: 0;
        right: 0;
        z-index: 2;
      }

      .image-wrapper.bawah {
        bottom: 0;
        left: 0;
        top: 0;
        z-index: 1;
      }

      .about-text {
        max-width: 99vw;
        font-size: 0.95em;
        padding: 0 1px;
        margin: 80px auto 0 auto;
        text-align: left;
        position: static;
        clear: both;
      }

      .about-text h2,
      .about-text h3 {
        font-size: 1em;
        margin-top: 8px;
        margin-bottom: 4px;
      }

      .about-text p {
        font-size: 0.95em;
        margin-bottom: 6px;
        padding-left: 6px;
        position: static;
      }

      .about-text em {
        font-size: 0.88em;
        margin-top: 8px;
        margin-left: 0;
      }

      .produk-section {
        padding: 6px 0 8px 0;
        margin-bottom: 8px;
        box-shadow: none;
        border-radius: 5px;
        max-width: 100vw;
      }

      .produk-section h2 {
        font-size: 1em;
        margin-bottom: 8px;
      }

      .logo-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-bottom: 16px;
      }

      .logo-grid img {
        width: 60px;
        height: 32px;
      }

      .pelanggan-section {
        padding: 8px 0 8px 0;
        border-radius: 6px;
        margin-bottom: 12px;
        max-width: 100vw;
      }

      .pelanggan-section h2 {
        font-size: 1em;
        margin-bottom: 6px;
      }

      .pelanggan-marquee {
        margin-top: 2px;
      }

      .pelanggan-track {
        gap: 12px;
        padding: 4px 0;
      }

      .pelanggan-track img {
        width: 40px;
        height: 20px;
      }

      .floating-wa {
        width: 36px;
        height: 36px;
        bottom: 8px;
        right: 8px;
      }

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
        padding-left: 0;
      }

      .logo {
        margin-left: 0;
        margin-bottom: 6px;
        max-height: 28px;
      }

      nav a {
        font-size: 0.75rem;
        padding: 4px 6px;
        border-radius: 4px;
      }

      .about-header {
        height: 80px;
        margin-top: 10px;
      }

      .about-header h1 {
        font-size: 1rem;
      }
    }

    .produk-section {
      background: #e2e2e2ff;
      border-radius: 10px;
      padding: 2px 0 20px 0;
      margin-top: -0px;
      margin-bottom: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
    }

    .produk-section h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.2em;
      font-weight: 700;
      color: #222;
      margin-top: 20px;
    }

    .produk-section .logo-grid {
      margin-bottom: 0;
    }

    .logo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 16px;
      justify-items: center;
      align-items: center;
      margin-bottom: 40px;
      background: transparent;
      border-radius: 0;
    }

    .logo-grid img {
      width: 140px;
      height: 80px;
      object-fit: contain;
      background: none;
      padding: 0;
      border-radius: 4px;
      box-shadow: none;
    }

    @media (max-width: 900px) {

      .produk-section,
      .pelanggan-section {
        padding: 18px 0 12px 0;
        margin-bottom: 24px;
        border-radius: 10px;
      }

      .logo-grid {
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 8px;
      }

      .logo-grid img {
        width: 80px;
        height: 40px;
      }
    }

    .pelanggan-section {
      background: #e2e2e2ff;
      text-align: center;
      border-radius: 16px;
      padding: 32px 0 24px 0;
      margin-bottom: 40px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
    }

    .pelanggan-section .logo-grid {
      margin-bottom: 0;
    }

    .pelanggan-marquee {
      overflow: hidden;
      position: relative;
      width: 100%;
      margin-top: 10px;
    }

    .pelanggan-track {
      display: flex;
      width: max-content;
      animation: pelanggan-scroll 40s linear infinite;
      align-items: center;
      gap: 40px;
      padding: 10px 0;
    }

    .pelanggan-track img {
      width: 120px;
      height: 60px;
      object-fit: contain;
      background: none;
      padding: 0;
      border-radius: 4px;
      box-shadow: none;
      display: block;
    }

    @keyframes pelanggan-scroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

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

    /* Removed conflicting hamburger and nav-wrapper styles to fix mobile menu activation */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      font-family: Arial, sans-serif;
      background: radial-gradient(circle, #7a7a7aff 0%, #000000 100%) fixed;
      margin: 0;
      padding: 0;
      color: white;
    }

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

    /* Removed conflicting nav-wrapper, nav-left, nav-right styles to allow hamburger dropdown to work */

    /* Removed nav a styles to prevent override of header partial's mobile nav dropdown */

    .about-header {
      background: url('{{ asset('images/aboutatas.png') }}') no-repeat center center;
      background-size: cover;
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 35px;
    }

    .about-header h1 {
      color: white;
      font-size: 25px;
      font-weight: 700;
    }

    .about-section {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      gap: 40px;
      padding: 40px 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .about-images {
      position: relative;
      width: 450px;
      height: 450px;
      flex-shrink: 0;
    }

    .image-wrapper {
      position: absolute;
      border: 6px solid white;
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.5);
      border-radius: 4px;
      background-color: white;
    }

    .image-wrapper img {
      width: 100%;
      height: auto;
      display: block;
    }

    .image-wrapper.atas {
      width: 250px;
      top: 5px;
      right: 9px;
      z-index: 2;
    }

    .image-wrapper.bawah {
      width: 250px;
      bottom: 90px;
      left: 30px;
      z-index: 1;
    }

    .about-text {
      max-width: 550px;
    }

    .about-text h2 {
      font-size: 1.3em;
      margin-top: 24px;
      margin-bottom: 16px;
      color: #ffffff;
    }

    .about-text p {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 16px;
      color: #dddddd;


    }


    .about-text em {
      display: block;
      font-style: italic;
      color: #ffa500;
      margin-bottom: 24px;
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

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        padding: 9px 4vw;
      }

      .nav-wrapper {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        margin-left: 0;
        padding-left: 0;
        gap: 10px;
      }

      .nav-left,
      .nav-right {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding-left: 10px;
      }

      .logo {
        margin-left: 0;
        margin-bottom: 10px;
        max-height: 50px;
      }

      nav a {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 6px;
      }

      .about-section {
        flex-direction: column;
        padding: 40px 20px;
      }

      .about-images {
        width: 100%;
        height: 400px;
      }

      .image-wrapper.atas,
      .image-wrapper.bawah {
        width: 70%;
      }

      .image-wrapper.atas {
        right: 10px;
      }

      .image-wrapper.bawah {
        left: 10px;
      }

      .about-text {
        text-align: center;
      }
    }

    .about-text {
      max-width: 600px;
      margin: 0 auto;
      text-align: justify;
    }

    .about-text h3 {
      margin: 20 20 4px 0;
      /* hilangkan margin atas, beri margin bawah sedikit */
      font-size: 1.3em;
      margin-left: 5px;
      margin-top: -10px;

    }

    .about-text p {
      margin: 0 0 10px 0;
    }

    .about-text em {
      display: block;
      margin-top: 17px;
      margin-left: 5px;
      font-style: italic;
      color: #cdffd0ff;
    }

    @media (max-width: 1024px) {
      .header-group-right {
        display: none !important;
      }

      .hamburger {
        display: flex;
      }

      .nav-wrapper {
        display: none;
      }

      .nav-wrapper.show {
        display: flex;
      }
    }

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

      .hamburger {
        display: none !important;
      }
    }

    .nav-wrapper.show {
      display: flex;
    }

    .hamburger {
      display: flex;
    }
  </style>
  </head>

  <body>


    <div class="about-header">
      <h1 class="animate">Tentang Kami</h1>
    </div>

    <section class="about-section">
      <div class="about-images">
        <div class="image-wrapper atas">
          <img src="{{ asset('images/about1.png') }}" alt="Foto 1">
        </div>
        <div class="image-wrapper bawah">
          <img src="{{ asset('images/about2.png') }}" alt="Foto 2">
        </div>
      </div>

      <div class="about-text">
        <h3><strong> PT. Samafitro Hadir Sebagai</strong></h3>
        <p>
          mitra yang memberikan solusi yang terpercaya dan cerdas bagi para konsumennya.
          Produk-produk bermerek ternama yang diusung akan memberi solusi yang tepat bagi kebutuhan pelanggan di
          berbagai bidang usaha.
        </p>
        <em>"Kami memberikan solusi yang tepat dan kompetitif"</em>
        <h2>Visi &
          Value</h2>
        <p><strong>Visi:</strong> Mitra pilihan dalam setiap usaha yang kami jalankan</p>
        <p><strong>Value:</strong> Grow together amongst stakeholders based on IRACE (Integrity, Respect,
          Accountability, Continuous Improvement, and Enthusiasm)</p>
      </div>
    </section>

    <!-- Produk Section -->
    <div class="produk-section">
      <h2 style="color:#222;">Produk Kami</h2>
      <div class="logo-grid">
        <img src="{{ asset('images/partners/areta.png') }}" alt="Areta">
        <img src="{{ asset('images/partners/axio.png') }}" alt="Axio">
        <img src="{{ asset('images/partners/canon.png') }}" alt="Canon">
        <img src="{{ asset('images/partners/cp.png') }}" alt="CP">
        <img src="{{ asset('images/partners/edan.png') }}" alt="EDAN">
        <img src="{{ asset('images/partners/epson.png') }}" alt="Epson">
        <img src="{{ asset('images/partners/fec.png') }}" alt="FEC">
        <img src="{{ asset('images/partners/gcc.png') }}" alt="GCC">
        <img src="{{ asset('images/partners/graph.png') }}" alt="Graph">
        <img src="{{ asset('images/partners/honey.png') }}" alt="Honeywell">
        <img src="{{ asset('images/partners/hp.png') }}" alt="HP">
        <img src="{{ asset('images/partners/jetrix.png') }}" alt="Jetrix">
        <img src="{{ asset('images/partners/kingt.png') }}" alt="KINGT">
        <img src="{{ asset('images/partners/konica.png') }}" alt="Konica Minolta">
        <img src="{{ asset('images/partners/kornit.png') }}" alt="Kornit">
        <img src="{{ asset('images/partners/logo-social-removebg-preview 1.png') }}" alt="Logo Social">
        <img src="{{ asset('images/partners/mactac.png') }}" alt="Mactac">
        <img src="{{ asset('images/partners/massivit.png') }}" alt="Massivit">
        <img src="{{ asset('images/partners/mimaki.png') }}" alt="Mimaki">
        <img src="{{ asset('images/partners/monkefit.png') }}" alt="Monkefit">
        <img src="{{ asset('images/partners/sunmi 1.png') }}" alt="Sunmi">
        <img src="{{ asset('images/partners/transmatic.png') }}" alt="Transmatik">
        <img src="{{ asset('images/partners/zund.png') }}" alt="Zund">
      </div>
    </div>
    <!-- Pelanggan Section -->
    <div class="pelanggan-section">
      <h2 style="color:#222;">Pelanggan Kami</h2>
      <div class="pelanggan-marquee">
        <div class="pelanggan-track">
          <img src="{{ asset('images/customers/bca.png') }}" alt="BCA">
          <img src="{{ asset('images/customers/bea cukai.png') }}" alt="Bea Cukai">
          <img src="{{ asset('images/customers/button.png') }}" alt="Buttonscarves">
          <img src="{{ asset('images/customers/cocacola.png') }}" alt="CocaCola">
          <img src="{{ asset('images/customers/Djarum.png') }}" alt="Djarum">
          <img src="{{ asset('images/customers/gudang garam.png') }}" alt="Gudang Garam">
          <img src="{{ asset('images/customers/holand.png') }}" alt="Holand">
          <img src="{{ asset('images/customers/indomaret.png') }}" alt="Indomaret">
          <img src="{{ asset('images/customers/ITB.png') }}" alt="ITB">
          <img src="{{ asset('images/customers/kfc.png') }}" alt="KFC">
          <img src="{{ asset('images/customers/lawson.png') }}" alt="Lawson">
          <img src="{{ asset('images/customers/mandiri.png') }}" alt="Mandiri">
          <img src="{{ asset('images/customers/pizza.png') }}" alt="Pizza Hut">
          <img src="{{ asset('images/customers/sociolla.png') }}" alt="Sociolla">
          <img src="{{ asset('images/customers/subway.png') }}" alt="Subway">
          <img src="{{ asset('images/customers/uniqlo.png') }}" alt="Uniqlo">
          <img src="{{ asset('images/customers/UPI.png') }}" alt="UPI">
          <img src="{{ asset('images/customers/yogya.png') }}" alt="Yogya">
          <!-- Duplikat agar marquee seamless -->
          <img src="{{ asset('images/customers/bca.png') }}" alt="BCA">
          <img src="{{ asset('images/customers/bea cukai.png') }}" alt="Bea Cukai">
          <img src="{{ asset('images/customers/button.png') }}" alt="Buttonscarves">
          <img src="{{ asset('images/customers/cocacola.png') }}" alt="CocaCola">
          <img src="{{ asset('images/customers/Djarum.png') }}" alt="Djarum">
          <img src="{{ asset('images/customers/gudang garam.png') }}" alt="Gudang Garam">
          <img src="{{ asset('images/customers/holand.png') }}" alt="Holand">
          <img src="{{ asset('images/customers/indomaret.png') }}" alt="Indomaret">
          <img src="{{ asset('images/customers/ITB.png') }}" alt="ITB">
          <img src="{{ asset('images/customers/kfc.png') }}" alt="KFC">
          <img src="{{ asset('images/customers/lawson.png') }}" alt="Lawson">
          <img src="{{ asset('images/customers/mandiri.png') }}" alt="Mandiri">
          <img src="{{ asset('images/customers/pizza.png') }}" alt="Pizza Hut">
          <img src="{{ asset('images/customers/sociolla.png') }}" alt="Sociolla">
          <img src="{{ asset('images/customers/subway.png') }}" alt="Subway">
          <img src="{{ asset('images/customers/uniqlo.png') }}" alt="Uniqlo">
          <img src="{{ asset('images/customers/UPI.png') }}" alt="UPI">
          <img src="{{ asset('images/customers/yogya.png') }}" alt="Yogya">
        </div>
      </div>
    </div>

  </body>
@endsection
