<!-- Header Partial -->
<style>
  .header-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 5vw;
    height: 70px;
    background: #000;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    z-index: 9999;
    border-bottom: 1px solid #333;
    box-sizing: border-box;
  }

  .header-group-left {
    display: flex;
    align-items: center;
    gap: 32px;
    min-width: 0;
  }

  .logo {
    max-height: 48px;
    margin-right: 16px;
    margin-left: 0;
    flex-shrink: 0;
  }

  .nav-list-desktop ul {
    display: flex;
    gap: 24px;
    list-style: none;
    align-items: center;
    margin: 0;
    padding: 0;
  }

  .nav-list-desktop a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.82rem;
    transition: color 0.2s;
    padding: 4px 0;
    border-radius: 0;
    border-bottom: none;

  }

  .nav-list-desktop a.active-nav {
    color: #7d7d7dff;
    font-weight: 700;
    border-bottom: 2px solid #ffffffff;
    border-radius: 0;

  }

  .nav-list-desktop a:hover {
    color: #ffffffff;
  }

  .header-group-right {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-left: 24px;
  }

  @media (max-width: 1024px) {
    .header-group-right {
      display: none;
    }
  }

  .btn-nav {
    background: #222;
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.78rem;
    transition: background 0.2s;
  }

  .btn-nav:hover {
    background: #b4c6ccff;
    color: #111;
  }

  .hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 20;
    padding: 0;
  }

  .hamburger span {
    display: block;
    width: 24px;
    height: 3px;
    margin: 4px 0;
    background: #fff;
    border-radius: 2px;
    transition: 0.35s cubic-bezier(.68, -0.55, .27, 1.55);
  }


  @media (max-width: 1024px) {
    .header-flex {
      padding: 0 12px;
      height: 60px;
    }

    .nav-list-desktop {
      display: none;
    }

    .hamburger {
      display: flex;
      width: 20px;
      height: 40px;
    }

    .hamburger span {
      width: 28px;
      height: 4px;
      margin: 5px 0;
    }
  }

  /* Mobile nav styles */
  .nav-wrapper {
    display: none;
    position: absolute;
    top: 70px;
    left: 0;
    width: 100%;
    background: #222;
    flex-direction: column;
    padding: 24px 0 12px 0;
    z-index: 9;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    border-radius: 0;
  }

  .nav-wrapper.is-open {
    display: flex;
  }

  .nav-left,
  .nav-right {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 0 32px;
  }

  .nav-left a,
  .nav-right a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    padding: 4px 0;
    border-radius: 0;

    transition: color 0.2s;
  }

  .nav-left a.active-nav,
  .nav-right a.active-nav {
    color: #6a6a6aff;
    font-weight: 700;

    border-radius: 0;
    text-underline-offset: 2px;
  }

  .nav-left a:hover,
  .nav-right a:hover {
    color: #ffffffff;
  }

  @media (max-width: 1024px) {
    .nav-wrapper {
      top: 60px;
      padding: 8px 0 4px 0;
    }

    .nav-left,
    .nav-right {
      padding: 0 8px;
      font-size: 0.78rem;
    }
  }

  @media (max-width: 600px) {
    .header-flex {
      padding: 0 4px;
      height: 52px;
    }

    .logo {

      margin-right: 8px;
    }

    .hamburger {
      width: 44px;
      height: 44px;
      padding: 0;
    }

    .hamburger span {
      width: 20px;
      height: 3px;
      margin: 3px 0;
      margin-left: -2px;
    }

    .nav-wrapper {
      top: 52px;
      padding: 4px 0 2px 0;
      z-index: 9999;
      position: fixed;
      width: 100vw;
      left: 0;
      background: #222;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
      border-radius: 0;
      max-height: 60vh;
      overflow-y: auto;
    }

    .nav-left,
    .nav-right {
      padding: 0 4px;
      gap: 8px;
    }

    .nav-left a,
    .nav-right a {
      font-size: 0.82rem;
    }
  }
</style>

<header>
  <div class="header-flex">
    <div class="header-group-left">
      <img src="{{ asset('images/logo.png') }}" alt="Samafitro" class="logo">
      <nav class="nav-list-desktop">
        <ul>
          <li><a href="{{ url('/beranda') }}"
              class="{{ Request::is('/') || Request::is('') || Request::is('beranda') || Request::routeIs('home') ? 'active-nav' : '' }}">Beranda</a>
          </li>
          <li><a href="{{ url('/tentangkami') }}" class="{{ Request::is('tentangkami') ? 'active-nav' : '' }}">Tentang
              Kami</a></li>
          <li><a href="{{ url('/produk') }}" class="{{ Request::is('produk') ? 'active-nav' : '' }}">Produk</a></li>
          <li><a href="{{ url('/kantor-cabang') }}"
              class="{{ Request::is('kantor-cabang') ? 'active-nav' : '' }}">Kantor Cabang</a></li>
          <li><a href="{{ url('/hubungi-kami') }}"
              class="{{ Request::is('hubungi-kami') ? 'active-nav' : '' }}">Hubungi Kami</a></li>
        </ul>
      </nav>
    </div>
    <div class="header-group-right">
      @auth
        <a href="{{ url('/dashboard') }}" class="btn-nav">Dashboard</a>
      @else
        <a href="{{ url('/login') }}" class="btn-nav">Masuk</a>
        <a href="{{ url('/register') }}" class="btn-nav">Daftar</a>
      @endauth
    </div>
    <button class="hamburger" aria-label="Menu" aria-expanded="false" aria-controls="nav-wrapper" id="hamburger-btn"
      type="button">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
  <!-- mobile nav -->
  <div class="nav-wrapper" id="nav-wrapper">
    <nav class="nav-left">
      <a href="{{ url('/beranda') }}"
        class="{{ Request::is('/') || Request::is('') || Request::is('beranda') || Request::routeIs('home') ? 'active-nav' : '' }}">Beranda</a>
      <a href="{{ url('/tentangkami') }}" class="{{ Request::is('tentangkami') ? 'active-nav' : '' }}">Tentang
        Kami</a>
      <a href="{{ url('/produk') }}" class="{{ Request::is('produk') ? 'active-nav' : '' }}">Produk</a>
      <a href="{{ url('/kantor-cabang') }}" class="{{ Request::is('kantor-cabang') ? 'active-nav' : '' }}">Kantor
        Cabang</a>
      <a href="{{ url('/hubungi-kami') }}" class="{{ Request::is('hubungi-kami') ? 'active-nav' : '' }}">Hubungi
        Kami</a>
      @auth
        <a href="{{ url('/dashboard') }}" class="btn-nav">Dashboard</a>
      @else
        <a href="{{ url('/login') }}" class="btn-nav">Masuk</a>
        <a href="{{ url('/register') }}" class="btn-nav">Daftar</a>
      @endauth
    </nav>
  </div>
</header>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    var hamburger = document.getElementById('hamburger-btn');
    var navWrapper = document.getElementById('nav-wrapper');
    if (hamburger && navWrapper) {
      hamburger.onclick = function(e) {
        e.stopPropagation();
        hamburger.classList.toggle('active');
        navWrapper.classList.toggle('is-open');
        hamburger.setAttribute('aria-expanded', hamburger.classList.contains('active'));
      };
      document.body.addEventListener('click', function(e) {
        if (hamburger.classList.contains('active')) {
          if (!navWrapper.contains(e.target) && !hamburger.contains(e.target)) {
            hamburger.classList.remove('active');
            navWrapper.classList.remove('is-open');
            hamburger.setAttribute('aria-expanded', false);
          }
        }
      });
    }
  });
</script>
