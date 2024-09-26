<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('user-management') }}">
      <img src="{{ asset('assets/img/infolahta_logo.png') }}" class="navbar-brand-img h-100" alt="...">
      <span class="ms-3 font-weight-bold">Koperasi Infolahta</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('user-profile') ? 'active' : '') }} " href="{{ url('user-profile') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title>customer-support</title>
              <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Rounded-Icons" transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                  <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                    <g id="customer-support" transform="translate(1.000000, 0.000000)">
                      <path class="color-background" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z" id="Path" opacity="0.59858631"></path>
                      <path class="color-foreground" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z" id="Path"></path>
                      <path class="color-foreground" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z" id="Path"></path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">Profil Pengguna</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Manajemen Kas Induk</h6>
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
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Manajemen Kas Usipa</h6>
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
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Manajemen Barang</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('data_barang') ? 'active' : '') }}" href="{{ url('data_barang') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-box ps-2 pe-2 text-center text-dark {{ (Request::is('user-management') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Persediaan Barang</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('transaksi') ? 'active' : '') }}" href="{{ url('transaksi') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-box ps-2 pe-2 text-center text-dark {{ (Request::is('user-management') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
          </div>
          <span class="nav-link-text ms-1">Kasir </span>
        </a>
      </li>
    </ul>
  </div>
</aside>