@extends('layouts.default')
@section('content')
  <style>
    /* Spinner animation for map loading */
    .map-loading-spinner svg {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    main {
      padding-top: 50px;
    }

    @media (max-width: 900px) {
      main {
        padding-top: 10px;
      }
    }

    body {
      background: radial-gradient(circle, #b0b0b0 0%, #000000 100%);
      background-attachment: fixed;
      background-size: cover;
      color: white;

    }

    .logo {
      max-height: 50px;
      margin-left: 0;
    }



    h2 {
      text-align: center;
      margin-bottom: 0px;
      font-size: 2rem;
      font-weight: 700;
    }

    h3 {
      text-align: center;
      font-weight: 400;
      margin-bottom: 32px;
      color: #ccc;
    }

    .depo-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-bottom: 50px;
    }

    .depo-buttons button {
      background-color: #444;
      color: white;
      border: none;
      padding: 14px 24px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .depo-buttons button:hover {
      background-color: #666;
    }

    .icon-location {
      font-size: 1.2rem;
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

    .section-divider {
      margin: 60px 0 30px;
      border-top: 1px solid #444;
    }

    @media (max-width: 900px) {
      .main-content {
        max-width: 100%;
        padding: 0 0px;
        margin-top: 0;
      }

      h2 {
        margin-top: 0;
        margin-bottom: 0px;
      }

      .map-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
      }

      .map-view {
        order: -1;
        width: 100%;
        min-width: 100%;
        padding: 0;
      }

      #googleMap {
        width: 100% !important;
        height: 220px !important;
        min-height: 180px !important;
        border-radius: 18px !important;
      }

      .location-list {
        margin: 0 auto;
        gap: 8px;
        border-radius: 10px;
      }

      .location-tab {
        font-size: 0.92rem;
        padding: 8px 8px;
        border-radius: 6px;
      }

      .btn-gmaps {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 5px;
      }

      h2 {
        font-size: 1.3rem;
        margin-bottom: 6px;
      }

      h3 {
        font-size: 1rem;
        margin-bottom: 18px;
      }

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

      .pelanggan-track img {
        width: 70px;
        height: 32px;
      }

      .pelanggan-track {
        gap: 18px;
        padding: 6px 0;
      }
    }

    .main-content {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 16px;
    }

    .map-container {
      display: grid;
      grid-template-columns: 30% 70%;
      gap: 32px;
      margin-bottom: 50px;
      align-items: flex-start;
    }

    .location-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
      background: #222;
      padding: 24px 12px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      min-width: 0;
    }

    .location-tab {
      background: #444;
      color: white;
      border: none;
      padding: 14px 18px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      text-align: left;
      cursor: pointer;
      transition: background 0.3s, color 0.3s;
      outline: none;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }

    .location-tab.active {
      color: #fff;
      box-shadow: 0 2px 8px rgba(115, 119, 227, 0.15);
      border-left: 4px solid #fff;
    }

    .map-view {
      width: 100%;
      background: #222;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      padding: 12px;
      min-width: 0;
      position: relative;
      overflow: hidden;
    }

    @media (max-width: 900px) {
      .main-content {
        max-width: 100%;
        padding: 0 8px;
      }

      .map-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .map-view {
        width: 100%;
        min-width: 220px;
      }
    }

    .carousel-wrapper {
      overflow: hidden;
      position: relative;
      width: 100%;
      margin-top: 30px;
    }

    .carousel-track {
      display: flex;
      width: max-content;
      animation: scroll-left 30s linear infinite;
    }

    .carousel-logos {
      display: flex;
    }

    .carousel-track img {
      width: 120px;
      height: auto;
      margin: 0 20px;
      background-color: white;
      padding: 10px;
      border-radius: 8px;
      object-fit: contain;
    }

    @keyframes scroll-left {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    .alamat {
      font-size: 0.70em;
      color: #ccc;
      font-weight: 100;
      display: block;
      margin-top: 2px;
    }

    .main-content {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .produk-section {
      background: #fff;
      border-radius: 16px;
      padding: 16px 0 24px 0;
      margin-top: 0;
      margin-bottom: 32px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
    }

    .produk-section h2 {
      text-align: center;
      margin-bottom: 24px;
      font-size: 2rem;
      font-weight: 700;
      color: #222;
    }

    .produk-section .logo-grid {
      margin-bottom: 0;
    }

    .pelanggan-section {
      background: #fff;
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

    .pelanggan-grid img {
      width: 120px;
      height: 60px;
      object-fit: contain;
      background: none;
      padding: 0;
      border-radius: 4px;
      box-shadow: none;
      display: block;
    }

    .pelanggan-grid {
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 20px;
      justify-items: center;
      align-items: center;
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

    .btn-gmaps {
      display: inline-block;
      margin-top: 6px;
      background: #717171ff;
      color: #fff;
      font-weight: 600;
      font-size: 0.70rem;
      padding: 6px 16px;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.2s;
    }

    .btn-gmaps:hover {
      background: #a7a7a7ff;
      color: #fff;
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  </head>
  <main style="padding-top: 50px;">
    <div class="main-content">
      <h2>Depo Kami</h2>
      <h3>PT Samafitro Jawa Barat</h3>
      <div class="map-container">
        <div class="location-list">
          <button class="location-tab active" data-lat="-6.915033" data-lng="107.629699">
            <strong>Kantor Cabang Bandung</strong><br>
            <span class="alamat">LLRE Martadinata St No.229, Cihapit, Bandung Wetan, Bandung City, West Java
              40114</span><br>
            <a href="https://www.google.com/maps/dir//Jl.+L.+L.+R.E.+Martadinata+No.229,+Cihapit,+Kec.+Bandung+Wetan,+Kota+Bandung,+Jawa+Barat+40114/@-6.9148861,107.5476234,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x2e68e7ceab0422ef:0xdbf3d4e6cbed87a1!2m2!1d107.6300253!2d-6.9148932?entry=ttu&g_ep=EgoyMDI1MDgwNC4wIKXMDSoASAFQAw%3D%3D"
              target="_blank" class="btn-gmaps">Buka di Google Maps</a>
          </button>
          <button class="location-tab" data-lat="-6.911408" data-lng="106.946949">
            <strong>Depo Sukabumi</strong><br>
            <span class="alamat">Perum Taman Asri, Blok B26 no.11 RT.09 Rw.14. Desa Subang Jaya, Kec Cikole, Kota
              SukaBumi</span>
            <a href="https://maps.app.goo.gl/t8n9qUAmdU7MKaCB9"target="_blank" class="btn-gmaps">Buka di Google Maps</a>
          </button>
          <button class="location-tab" data-lat="-6.401219" data-lng="107.446138">
            <strong>Depo Cikampek</strong><br>
            <span class="alamat">Mall Jl. Ahmad Yani, Dawuan Tengah, Kec. Cikampek, Karawang, Jawa Barat 41373</span><br>
            <a href="https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIHCAEQIRigATIHCAIQIRigATIHCAMQIRiPAjIHCAQQIRiPAtIBCDIyOTJqMGo5qAIAsAIB&um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KZuirwlYcmkuMe0tw2zT_EEu&daddr=Mall+Jl.+Ahmad+Yani,+Dawuan+Tengah,+Kec.+Cikampek,+Karawang,+Jawa+Barat+41373"
              target="_blank" class="btn-gmaps">Buka di Google Maps</a>
          </button>
          <button class="location-tab" data-lat="-6.714760" data-lng="108.533389">
            <strong>Depo Cirebon</strong><br>
            <span class="alamat"> Komplek Bumi Linggahara 4, Jalan Brigadir Jendral Dharsono, By Pass, Kedawung, Kec.
              Kedawung, Kabupaten Cirebon, Jawa Barat 45153</span><br>
            <a href="https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIHCAEQIRigATIHCAIQIRigATIHCAMQIRiPAjIHCAQQIRiPAtIBCDQ3NDBqMGo0qAIAsAIA&um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KbESy24a4m4uMd7a0YtnQZP5&daddr=Komplek+Bumi+Linggahara+4,+Jalan+Brigadir+Jendral+Dharsono,+By+Pass,+Kedawung,+Kec.+Kedawung,+Kabupaten+Cirebon,+Jawa+Barat+45153"
              target="_blank" class="btn-gmaps">Buka di Google Maps</a>
          </button>

          <button class="location-tab" data-lat="-7.357994" data-lng="108.214394">
            <strong>Depo Tasikmalaya</strong><br>
            <span class="alamat">Perumahan Baitul Marhamah 1 Blok N6 kel. Sambong jaya kec.mangkubumi taskmalaya</span>
            <a href="https://maps.app.goo.gl/P55NRvv2cj3edx4Q8" target="_blank" class="btn-gmaps">Buka di Google Maps</a>
          </button>
        </div>
        <div class="map-view">
          <div id="googleMap" style="width:100%;min-height:400px;height:400px;border-radius:12px;"></div>
          <div id="map-loading"
            style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(34,34,34,0.85);color:#fff;font-size:1.1rem;font-weight:600;z-index:2;border-radius:12px;">
            <span class="map-loading-spinner" style="width:44px;height:44px;display:block;margin-bottom:12px;">
              <svg viewBox="0 0 50 50" style="width:100%;height:100%;">
                <circle cx="25" cy="25" r="20" fill="none" stroke="#fff" stroke-width="5"
                  opacity="0.2" />
                <circle cx="25" cy="25" r="20" fill="none" stroke="#fff" stroke-width="5"
                  stroke-linecap="round" stroke-dasharray="31.4 94.2" stroke-dashoffset="0" />
              </svg>
            </span>
            Peta sedang dimuat
          </div>
        </div>

      </div>
    </div>
    <script>
      const locations = [{
          name: 'Kantor Cabang Bandung',
          lat: -6.917464,
          lng: 107.619123
        },
        {
          name: 'Depo Sukabumi',
          lat: -6.929328,
          lng: 106.927404
        },
        {
          name: 'Depo Cikampek',
          lat: -6.413785,
          lng: 107.445522
        },
        {
          name: 'Depo Cirebon',
          lat: -6.732023,
          lng: 108.552315
        },
        {
          name: 'Depo Tasikmalaya',
          lat: -7.327524,
          lng: 108.220243
        }
      ];
      let map;
      let marker;

      function initMap() {
        // Find the active tab for initial coordinates
        const tabs = document.querySelectorAll('.location-tab');
        let initialTab = document.querySelector('.location-tab.active');
        let initialLat = initialTab ? parseFloat(initialTab.getAttribute('data-lat')) : locations[0].lat;
        let initialLng = initialTab ? parseFloat(initialTab.getAttribute('data-lng')) : locations[0].lng;
        const mapDiv = document.getElementById('googleMap');
        const loadingDiv = document.getElementById('map-loading');
        if (!mapDiv) return;
        if (loadingDiv) loadingDiv.style.display = 'flex';
        map = new google.maps.Map(mapDiv, {
          center: {
            lat: initialLat,
            lng: initialLng
          },
          zoom: 16,
          mapTypeId: 'satellite',
        });
        marker = new google.maps.Marker({
          position: {
            lat: initialLat,
            lng: initialLng
          },
          map: map,
          title: initialTab ? initialTab.querySelector('strong')?.textContent?.trim() || initialTab.textContent.trim() :
            locations[0].name
        });
        // Hide loading overlay when map is fully loaded
        google.maps.event.addListenerOnce(map, 'idle', function() {
          if (loadingDiv) loadingDiv.style.display = 'none';
        });
        // Tab event
        tabs.forEach((tab) => {
          tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const lat = parseFloat(tab.getAttribute('data-lat'));
            const lng = parseFloat(tab.getAttribute('data-lng'));
            map.setCenter({
              lat,
              lng
            });
            marker.setPosition({
              lat,
              lng
            });
            marker.setTitle(tab.querySelector('strong')?.textContent?.trim() || tab.textContent.trim());
          });
        });
      }
      window.initMap = initMap;
    </script>
    <script src="https://cdn.jsdelivr.net/gh/somanchiu/Keyless-Google-Maps-API@v7.1/mapsJavaScriptAPI.js"></script>


  </main>
@endsection
