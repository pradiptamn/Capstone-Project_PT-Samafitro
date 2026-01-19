@extends('layouts.default')
@section('content')
  <div>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
      }

      body {
        background: radial-gradient(circle, #b0b0b0 0%, #000 100%) fixed;
        color: white;
        min-height: 100vh;
      }

      /* Remove header/nav styles to use shared partial styles */
      .main-content {
        max-width: 1100px;
        margin: 0 auto;
        padding: 120px 20px 40px 20px;
        display: flex;
        gap: 40px;
        align-items: flex-start;
        justify-content: space-between;
      }

      .contact-section {
        flex: 1.2;
        display: flex;
        flex-direction: column;
        gap: 18px;
      }

      .contact-section h1 {
        font-size: 2.3rem;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .contact-section p {
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: #e0e0e0;
      }

      .contact-section .jamkerja {
        font-size: 1rem;
        margin-bottom: 18px;
        color: #ccc;
      }

      .contact-buttons {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 10px;
      }

      .contact-btn {
        background: #888;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: background 0.3s;
      }

      .contact-btn:hover {
        background: #7377e3ff;
        color: #fff;
      }

      .contact-btn i {
        font-size: 1.2em;
      }

      .location-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }

      .location-img {
        width: 100%;
        max-width: 320px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12);
        margin-bottom: 8px;
      }

      .lokasi-title {
        font-size: 2rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 2px;
        margin-top: 0;
        text-align: left;
        width: 100%;
      }

      .lokasi-alamat {
        font-size: 1.1rem;
        color: #fff;
        margin-bottom: 8px;
        text-align: left;
        width: 100%;
      }

      .lokasi-divider {
        width: 80%;
        height: 4px;
        background: #fff;
        border-radius: 2px;
        margin: 10px 0 0 0;
        opacity: 0.5;
      }

      @media (max-width: 900px) {
        .main-content {
          flex-direction: column;
          gap: 30px;
          padding: 110px 10px 30px 10px;
        }

        .location-section {
          align-items: flex-start;
          width: 100%;
        }

        .location-img {
          max-width: 100%;
        }

        .lokasi-title,
        .lokasi-alamat {
          text-align: left;
          width: 100%;
        }
      }

      @media (max-width: 600px) {
        header {
          flex-direction: column;
          align-items: flex-start;
          z-index: 9999;
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          min-width: 0;
          overflow-x: auto;
          background: #111;
          box-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
        }

        #hamburger-btn {
          width: 32px !important;
          height: 32px !important;
          font-size: 1.2rem !important;
          padding: 4px !important;
          margin-right: 2vw !important;
          z-index: 10001;
          cursor: pointer;
        }

        #hamburger-btn span,
        #hamburger-btn i {
          font-size: 1.2rem !important;
        }

        .nav-list-desktop,
        .nav-list-mobile {
          font-size: 0.85rem !important;
          min-width: 100vw;
          overflow-x: auto;
          white-space: nowrap;
          background: transparent;
          position: fixed;
          top: 56px;
          left: 0;
          width: 100vw;
          height: calc(100vh - 56px);
          z-index: 1000;
          box-shadow: none;
          padding: 0;
          max-height: none;
          overflow-y: auto;
          transition: none;
          border-radius: 0;
        }

        .nav-list-desktop a,
        .nav-list-mobile a {
          font-size: 0.85rem !important;
          padding: 8px 12px !important;
          border-radius: 6px;
          display: block;
          margin-bottom: 2px;
        }

        .main-content {
          padding: 170px 5px 20px 5px;
          flex-direction: column;
          gap: 18px;
          min-width: 0;
          box-sizing: border-box;
          overflow-x: auto;
          margin-top: 0;
          position: relative;
          z-index: 1;
        }

        .contact-section h1 {
          font-size: 1.13rem;
          margin-bottom: -8px;
          margin-top: -90px;

        }

        .contact-section p {
          font-size: 0.90rem;
          margin-bottom: -10px;
        }

        .contact-section .jamkerja {
          font-size: 0.85rem;
          margin-bottom: 12px;
        }

        .contact-buttons {
          gap: 10px;
        }

        .accordion-btn {
          font-size: 0.92rem;
          padding: 9px 10px;
        }

        .accordion-content {
          font-size: 0.92rem;
          padding: 9px 10px;
        }

        .sales-grid {
          grid-template-columns: 1fr;
          gap: 10px;
        }

        .sales-card {
          flex-direction: row;
          padding: 7px 4px;
          gap: 8px;
        }

        .sales-brand {
          width: 28px;
          height: 28px;
        }

        .sales-info {
          font-size: 0.92rem;
        }

        .lokasi-title {
          font-size: 1rem;
          margin-bottom: 2px;
        }

        .lokasi-alamat {
          font-size: 0.85rem;
          margin-bottom: 6px;
        }

        .location-section {
          align-items: flex-start;
          width: 100%;
          margin-top: 10px;
          min-width: 0;
        }

        .location-img {
          max-width: 100%;
          border-radius: 6px;
          margin-bottom: 6px;
        }

        .lokasi-divider {
          width: 70%;
          height: 3px;
          margin: 8px 0 0 0;
        }

        body {
          min-width: 0;
          overflow-x: auto;
        }
      }

      .accordion {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .accordion-btn {
        background: #888;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
      }

      .accordion-btn .arrow {
        margin-left: auto;
        font-size: 1.1em;
        transition: transform 0.3s;
      }

      .accordion-btn.active .arrow {
        transform: rotate(180deg);
      }

      .accordion-btn:hover {
        background: #7377e3ff;
        color: #fff;
      }

      .accordion-content {
        background: #222;
        color: #fff;
        border-radius: 0 0 10px 10px;
        padding: 12px 18px;
        font-size: 0.98rem;
        display: none;
        animation: fadeIn 0.3s;
      }

      .accordion-content.active {
        display: block;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .sales-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-top: 18px;
      }

      .sales-card {
        background: #333;
        border-radius: 10px;
        display: flex;
        align-items: center;
        padding: 12px 16px;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      }

      .sales-brand {
        width: 48px;
        height: 48px;
        object-fit: contain;
        border-radius: 6px;
        background: #fff;
        padding: 4px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
      }

      .sales-info {
        color: #fff;
        font-size: 1.05rem;
        font-weight: 500;
        margin-left: auto;
      }

      @media (max-width: 900px) {
        .sales-grid {
          grid-template-columns: repeat(2, 1fr);
        }
      }

      @media (max-width: 600px) {
        .sales-grid {
          grid-template-columns: 1fr;
        }

        .sales-card {
          flex-direction: row;
          padding: 10px 8px;
        }

        .sales-brand {
          width: 38px;
          height: 38px;
        }

        .sales-info {
          font-size: 0.98rem;
        }
      }

      .sales-card {
        text-decoration: none;
        border: none;
        outline: none;
        /* No hover effect on card */
      }

      .sales-wa {
        font-size: 1.2rem;
        color: #25d366;
        background: #fff;
        border-radius: 50%;
        padding: 3px;
        margin-right: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
      }

      .sales-contacts {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-left: 18px;
        flex: 1;
      }

      .sales-wa-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        background: none;
        border: none;
        outline: none;
        transition: color 0.2s;
      }

      .sales-wa-link:hover {
        color: #25d366;
        background: #222;
        border-radius: 6px;
      }

      .sales-wa-number {
        font-size: 0.95rem;
        color: #e0e0e0;
        margin-left: 6px;
        font-weight: 400;
      }


      /* Mengatur gaya umum */
      .social-media-icons {
        display: flex;
        justify-content: center;
        /* Menyentraikan ikon secara horizontal */
        align-items: center;
        gap: 20px;
        /* Jarak antar ikon */
        padding: 16px 0;
        /* Padding atas/bawah */
      }

      /* Gaya untuk setiap ikon */
      .social-media-icons a {
        color: white;
        /* Warna teks/ikon putih */
        font-size: 35px;
        /* Ukuran ikon */
        transition: transform 0.3s ease;
        /* Efek hover */
      }

      /* Efek hover */
      .social-media-icons a:hover {
        transform: scale(1.2);
        /* Memperbesar ikon saat dihover */
        cursor: pointer;
      }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- head diilangin, body diganti div, script ditaruh paling bawah -->
    <!-- Header is included in the layout, do not include here to avoid double navigation bar -->
    <main>
      <div class="main-content">
        <section class="contact-section">
          <h1>Hubungi Kami</h1>
          <p>Jangan ragu untuk menghubungi kami dan temukan solusi terbaik untuk kebutuhan Anda</p>
          <div class="jamkerja">Jam Kerja : Senin – Jumat | 08:00 WIB – 17.00 WIB</div>
          <div class="contact-buttons">

            <div class="social-media-icons">
              <!-- Instagram -->
              <a href="https://www.instagram.com/samafitro_bandung/" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-instagram"></i>
              </a>

              <!-- Facebook -->
              <a href="https://www.facebook.com/SamafitroBandung" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-facebook-f"></i>
              </a>

              <!-- TikTok -->
              <a href="https://www.tiktok.com/@samafitro.bandung" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-tiktok"></i>
              </a>

              <!-- YouTube -->
              <a href="https://www.youtube.com/@SamafitroBandung" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-youtube"></i>
              </a>
            </div>
            <div class="accordion">

              <div class="accordion-item">
                <button class="accordion-btn">
                  <i class="fa-solid fa-user"></i>
                  Admin Cabang Bandung
                  <span class="arrow">&#9660;</span>
                </button>
                <div class="accordion-content">
                  <p>Admin Samafitro Bandung siap membantu Anda ! </p>

                  <!-- Tambahkan grid atau kontainer untuk kontak admin -->
                  <div class="sales-grid">
                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Admin</strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=6285819820008&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link"
                          aria-label="WhatsApp Admin Samafitro Bandung">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Admin Samafitro Bandung
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="accordion-item">
                <button class="accordion-btn"><i class="fa-solid fa-comments"></i> Kontak Sales <span
                    class="arrow">&#9660;</span></button>
                <div class="accordion-content">
                  <p>Hubungi tim sales untuk konsultasi produk dan penawaran terbaik.</p>
                  <div class="sales-grid">
                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Account Manager</strong>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6281295597232&text&type=phone_number&app_absent=0
"
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Budi">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Iman Taufik
                        </a>
                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Excecutive Production Printing Solution</strong>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6282115514712&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>

                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Excecutive Industrial Printing Solustion</strong>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6285759704469&text&type=phone_number&app_absent=0  "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>
                        </a>
                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Excecutive Copier Canon </strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=6281323737323&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 1
                        </a>
                        </a>
                        <a href=" https://api.whatsapp.com/send/?phone=6281320682020&text&type=phone_number&app_absent=0  "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 2
                        </a>
                        </a>
                        <a href=" https://api.whatsapp.com/send/?phone=6285797247652&text&type=phone_number&app_absent=0  "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 3
                        </a>
                        </a>
                        <a href=" https://api.whatsapp.com/send/?phone=62811726646&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 4
                        </a>
                        </a>
                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Manager Business Image Solution</strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=628562020005&text&type=phone_number&app_absent=0  "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>
                        </a>
                      </div>
                    </div>
                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Support POS</strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=6281214772626&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>
                        </a>
                      </div>
                    </div>


                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Excecutive Garmen & Textile</strong>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6281573044726&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 1
                        </a>
                        </a>
                        <a href="https://api.whatsapp.com/send/?phone=6282130655559&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 2
                        </a>
                        </a>
                        <a href=" https://api.whatsapp.com/send/?phone=628562111988&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 3
                        </a>
                        </a>
                        <a href=" https://api.whatsapp.com/send/?phone=6285183277498&text&type=phone_number&app_absent=0"
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales 4
                        </a>
                        </a>
                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Axioo TKDN Product</strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=628995820247&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>
                        </a>
                      </div>
                    </div>

                    <div class="sales-card">
                      <div class="sales-contacts">
                        <div class="sales-position">
                          <strong>Excecutive S & D</strong>
                        </div>
                        <a href=" https://api.whatsapp.com/send/?phone=6282142656655&text&type=phone_number&app_absent=0 "
                          target="_blank" rel="noopener" class="sales-wa-link" aria-label="WhatsApp Rina">
                          <i class="fa-brands fa-whatsapp sales-wa"></i> Kontak Sales
                        </a>
                        </a>
                      </div>
                    </div>

                  </div>

                </div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn"><i class="fa-solid fa-phone"></i> 022 720 5555 <span
                    class="arrow">&#9660;</span></button>
                <div class="accordion-content">
                  <p>Silahkan Telfon langsung ke kantor Samafitro Bandung pada jam kerja </p>
                </div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn"><i class="fa-solid fa-envelope"></i> Samafitro_bdg@samafitro.co.id <span
                    class="arrow">&#9660;</span></button>
                <div class="accordion-content">
                  <p>Kirim email untuk pertanyaan, permintaan penawaran, atau kerjasama.</p>
                </div>
              </div>
            </div>

          </div>
        </section>

    </main>
    <script>
      // Accordion logic
      document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.accordion-item');
        items.forEach(item => {
          const btn = item.querySelector('.accordion-btn');
          const content = item.querySelector('.accordion-content');
          btn.addEventListener('click', function() {
            const isActive = btn.classList.contains('active');
            // Close all
            document.querySelectorAll('.accordion-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('active'));
            // Open if not active
            if (!isActive) {
              btn.classList.add('active');
              content.classList.add('active');
            }
          });
        });
      });
      // Hamburger logic for mobile nav is already included in the shared header partial
    </script>
  </div>
@endsection
