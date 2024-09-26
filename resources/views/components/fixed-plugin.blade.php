<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg ">
      <div class="card-header pb-0 pt-3 ">

        <div class="{{ (Request::is('rtl') ? 'float-start mt-4' : 'float-end mt-4') }}">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>     
       
      </div>
      <div class="card-body">
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
                <i style="font-size: 1rem;" class="fas fa-hand-holding-usd  ps-2 pe-2 text-center text-dark {{ (Request::is('kas-keluar-usipa') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
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
            <a class="nav-link {{ (Request::is('user-management') ? 'active' : '') }}" href="{{ url('user-management') }}">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-box ps-2 pe-2 text-center text-dark {{ (Request::is('user-management') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
              </div>
              <span class="nav-link-text ms-1">Persediaan Barang</span>
            </a>
          </li>
        </ul>
      </div>
</div>
  
</div>
  <style>
    .fixed-plugin .card {

    overflow-y: auto; /* Enable scrolling */
}

.navbar-nav {
    text-align: left; /* Align text to the left */
}

.fixed-plugin .icon {
    justify-content: flex-start; /* Align icons to the left */
}
.nav-item .nav-link {
    display: flex;
    align-items: center;
    padding-left: 10px; /* Adjust as needed */
}
.nav-link-text {
    white-space: nowrap; /* Prevent text from wrapping */
    color: darkgrey; /* Set the default color to dark grey */
    transition: color 0.3s ease; /* Smooth color transition */
}
.nav-link-text:hover {
      color: purple; /* Change to purple when hovered */
  }
h6{
  text-align: center;
  color: black;
}



  </style>

{{-- <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
  <i class="fa fa-close"></i>
</button> --}}
