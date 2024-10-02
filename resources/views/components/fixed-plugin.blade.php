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
