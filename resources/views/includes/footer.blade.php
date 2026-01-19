<footer class="footer-main">
  <div class="footer-content">
    <div class="footer-logo">
      <img src="{{ asset('images/samafitro1.png') }}" alt="Samafitro" />
    </div>
    <div class="footer-social">
      <h2>Social media kami :</h2>
      <div class="footer-social-list">
        <div class="footer-social-item">
          <span class="footer-icon email"></span>
          <span>samafitro_bdg@samafitro.co.id</span>
        </div>
        <div class="footer-social-item">
          <span class="footer-icon instagram"></span>
          <span>@samafitro_bandung</span>
        </div>
        <div class="footer-social-item">
          <span class="footer-icon youtube"></span>
          <span>samafitro_bdg@samafitro.co.id</span>
        </div>
        <div class="footer-social-item">
          <span class="footer-icon store"></span>
          <span>Samafitro Bandung Official Store</span>
        </div>
        <div class="footer-social-item">
          <span class="footer-icon facebook"></span>
          <span>@SamafitroBandung</span>
        </div>
        <div class="footer-social-item">
          <span class="footer-icon tiktok"></span>
          <span>@samafitro.bandung</span>
        </div>
      </div>
    </div>
  </div>
</footer>
<style>
  .footer-main {
    width: 100%;
    background: linear-gradient(90deg, #444 0%, #222 100%);
    color: #fff;
    padding: 32px 0 16px 0;
    font-family: 'Inter', sans-serif;
  }

  .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: flex-start;
    gap: 48px;
    flex-wrap: wrap;
    padding: 0 24px;
  }

  .footer-logo {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    position: relative;
  }

  .footer-logo img {
    width: 220px;
    height: auto;
    margin-bottom: 12px;
    margin-top: 24px;
    margin-left: 0;
    display: block;
    position: relative;
    left: 0;
  }

  .footer-social {
    flex: 1;
    min-width: 260px;
  }

  .footer-social h2 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 18px;
  }

  .footer-social-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px 32px;
  }

  .footer-social-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.08rem;
    font-weight: 500;
  }

  .footer-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: none;
    border-radius: 0;
    margin-right: 2px;
    color: #fff;
  }

  .footer-icon.email::before,
  .footer-icon.store::before {
    color: #fff;
    filter: none;
  }

  .footer-icon.email::before {
    content: '\2709';
    font-size: 1.4rem;
  }

  .footer-icon.store::before {
    content: '\1F6D2';
    font-size: 1.3rem;
  }

  .footer-icon.instagram::before,
  .footer-icon.youtube::before,
  .footer-icon.facebook::before,
  .footer-icon.tiktok::before {
    content: '';
    display: inline-block;
    width: 22px;
    height: 22px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    filter: brightness(0) invert(1);
  }

  .footer-icon.instagram::before {
    background-image: url('https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg');
  }

  .footer-icon.youtube::before {
    background-image: url('https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg');
  }

  .footer-icon.facebook::before {
    background-image: url('https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg');
  }

  .footer-icon.tiktok::before {
    background-image: url('https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg');
  }

  @media (max-width: 900px) {
    .footer-main {
      padding: 20px 0 10px 0;
      font-size: 0.95rem;
    }

    .footer-content {
      flex-direction: column;
      align-items: flex-start;
      gap: 24px;
      padding: 0 8px;
    }

    .footer-logo {
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      position: relative;
    }

    .footer-logo img {
      width: 160px;
      margin-top: 18px;
      margin-left: 0;
      left: 0;
      position: relative;
      display: block;
    }

    .footer-social-list {
      grid-template-columns: 1fr;
      gap: 14px;
    }

    .footer-social h2 {
      font-size: 1rem;
      margin-bottom: 12px;
    }

    .footer-social-item {
      font-size: 0.98rem;
    }

    .footer-icon {
      width: 26px;
      height: 26px;
    }
  }

  @media (max-width: 600px) {
    .footer-main {
      padding: 12px 0 6px 0;
      font-size: 0.88rem;
    }

    .footer-logo {
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      position: relative;
    }

    .footer-logo img {
      width: 90px;
      margin-top: 4px;
      margin-bottom: -30px;
      margin-left: 0;
      left: 0;
      position: relative;
      display: block;
    }

    .footer-social h2 {
      font-size: 0.60rem;
      margin-bottom: 8px;
    }

    .footer-social-item {
      font-size: 0.60rem;
      gap: 8px;
    }

    .footer-icon {
      width: 18px;
      height: 18px;
    }
  }

  /* Tambahkan di bawah style footer yang sudah ada */
  @media (max-width: 500px) {
    .footer-logo {
      max-width: 10px;
      margin-bottom: 6px;
    }

    .footer {
      font-size: 0.8rem;
    }

    .footer-section h4 {
      font-size: 0.88rem;
    }

    .footer-bottom {
      font-size: 0.8rem;
    }
  }
</style>
