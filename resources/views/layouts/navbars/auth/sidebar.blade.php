{{-- <!-- Navbar with dropdown button for mobile -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarDropdown">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Menu
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="{{ url('user-profile') }}">Profil Pengguna</a></li>
            <li><a class="dropdown-item" href="{{ route('kasInduk.index') }}">Kas Induk</a></li>
            <li><a class="dropdown-item" href="{{ route('kas-masuk') }}">Kas Masuk</a></li>
            <li><a class="dropdown-item" href="{{ route('kas-keluar') }}">Kas Keluar</a></li>
            <li><a class="dropdown-item" href="{{ route('buku-masuk') }}">Buku Besar Kas Masuk</a></li>
            <li><a class="dropdown-item" href="{{ route('buku-keluar') }}">Buku Besar Kas Keluar</a></li>
            <li><a class="dropdown-item" href="{{ route('kasUsipa.index') }}">Kas Usipa</a></li>
            <li><a class="dropdown-item" href="{{ route('kas-masuk-usipa') }}">Kas Masuk Usipa</a></li>
            <li><a class="dropdown-item" href="{{ route('kas-keluar-usipa') }}">Kas Keluar Usipa</a></li>
            <li><a class="dropdown-item" href="{{ route('buku-masuk-usipa') }}">Buku Besar Usipa Masuk</a></li>
            <li><a class="dropdown-item" href="{{ route('buku-keluar-usipa') }}">Buku Besar Usipa Keluar</a></li>
            <li><a class="dropdown-item" href="{{ route('kas-keluar') }}">Persediaan Barang</a></li>
            <!-- Tambahkan menu lainnya di sini -->
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav> --}}

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ url('user-profile') }}">
      <img src="{{ asset('assets/img/logo-koperasi.png') }}" class="navbar-brand-img h-100" alt="...">
      <span class="ms-3 font-weight-bold">Primkopkar S-20 Anindyaguna</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-start text-xs font-weight-bolder opacity-6">Manajemen Kas Induk</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ Request::is('kasInduk*') ? 'active' : '' }}" href="{{ route('kasInduk.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-alt ps-2 pe-2 text-center {{ Request::is('kasInduk*') ? 'text-white' : 'text-dark' }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Induk</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('kas-masuk') ? 'active' : '') }}" href="{{ route('kas-masuk') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-money-check-alt  ps-2 pe-2 text-center text-dark {{ (Request::is('kas-masuk') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Masuk</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('kas-keluar') ? 'active' : '') }}" href="{{ route('kas-keluar') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-hand-holding-usd  ps-2 pe-2 text-center text-dark {{ (Request::is('kas-keluar') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Keluar</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('buku-masuk') ? 'active' : '') }}" href="{{ route('buku-masuk') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-invoice-dollar ps-2 pe-2 text-center text-dark {{ (Request::is('buku-masuk') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Buku Besar Kas Masuk</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('buku-keluar') ? 'active' : '') }}" href="{{ route('buku-keluar') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-invoice-dollar ps-2 pe-2 text-center text-dark {{ (Request::is('buku-keluar') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Buku Besar Kas Keluar</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-start text-uppercase text-xs font-weight-bolder opacity-6">Manajemen Kas Usipa</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ Request::is('kasUsipa*') ? 'active' : '' }}" href="{{ route('kasUsipa.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-alt ps-2 pe-2 text-center {{ Request::is('kasUsipa*') ? 'text-white' : 'text-dark' }}" aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Usipa</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('kas-masuk-usipa') ? 'active' : '') }}" href="{{ route('kas-masuk-usipa') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-money-check-alt  ps-2 pe-2 text-center text-dark {{ (Request::is('kas-masuk-usipa') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Masuk Usipa</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('kas-keluar-usipa') ? 'active' : '') }}" href="{{ route('kas-keluar-usipa') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-hand-holding-usd  ps-2 pe-2 text-center text-dark {{ (Request::is('kas-keluar-usipa') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kas Keluar Usipa</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('buku-masuk-usipa') ? 'active' : '') }}" href="{{ route('buku-masuk-usipa') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-invoice-dollar ps-2 pe-2 text-center text-dark {{ (Request::is('buku-masuk-usipa') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Buku Besar Usipa Masuk</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('buku-keluar-usipa') ? 'active' : '') }}" href="{{ route('buku-keluar-usipa') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-file-invoice-dollar ps-2 pe-2 text-center text-dark {{ (Request::is('buku-keluar-usipa') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Buku Besar Usipa Keluar</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-start text-uppercase text-xs font-weight-bolder opacity-6">Manajemen Barang</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('data_barang*') ? 'active' : '') }}" href="{{ route('data_barang.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-box ps-2 pe-2 text-center text-dark {{ (Request::is('data_barang*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Persediaan Barang</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('transaksi*') ? 'active' : '') }}" href="{{ route('transaksi.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-solid fa-cash-register ps-2 pe-2 text-center text-dark {{ (Request::is('transaksi*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kasir</span>
        </a>
      </li>
    </ul>
  </div>
</aside>