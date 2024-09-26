{{-- @extends('layouts.app')

@section('auth')


@if(\Request::is('static-sign-up'))
@include('layouts.navbars.guest.nav')
@yield('content')
@include('layouts.footers.guest.footer')

@elseif (\Request::is('static-sign-in'))
@include('layouts.navbars.guest.nav')
@yield('content')
@include('layouts.footers.guest.footer')

@else
@if (\Request::is('rtl'))
@include('layouts.navbars.auth.sidebar-rtl')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
    @include('layouts.navbars.auth.nav-rtl')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>

@elseif (\Request::is('profile'))
@include('layouts.navbars.auth.sidebar')
<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    @include('layouts.navbars.auth.nav')
    @yield('content')
</div>

@elseif (\Request::is('virtual-reality'))
@include('layouts.navbars.auth.nav')
<div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
    @include('layouts.navbars.auth.sidebar')
    <main class="main-content mt-1 border-radius-lg">
        @yield('content')
    </main>
</div>
@include('layouts.footers.auth.footer')

@else
@include('layouts.navbars.auth.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
@endif

@include('components.fixed-plugin')
@endif



@endsection --}}

@extends('layouts.app')

@section('auth')


    @if(\Request::is('static-sign-up')) 
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    
    @elseif (\Request::is('static-sign-in')) 
        @include('layouts.navbars.guest.nav')
            @yield('content')
        @include('layouts.footers.guest.footer')
    
    @else
        @if (\Request::is('rtl'))  
            @include('layouts.navbars.auth.sidebar-rtl')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
                @include('layouts.navbars.auth.nav-rtl')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>

        @elseif (\Request::is('profile'))  
            @include('layouts.navbars.auth.sidebar')
            <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
                @include('layouts.navbars.auth.nav')
                @yield('content')
            </div>

        @elseif (\Request::is('virtual-reality')) 
            @include('layouts.navbars.auth.nav')
            <div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                @include('layouts.navbars.auth.sidebar')
                <main class="main-content mt-1 border-radius-lg">
                    @yield('content')
                </main>
            </div>
            @include('layouts.footers.auth.footer')

        @else
            @include('layouts.navbars.auth.sidebar')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
                @include('layouts.navbars.auth.nav')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
        @endif

        {{-- This section will be conditionally included --}}
        @include('components.fixed-plugin')
    @endif
    <style>
        /* Default hide the fixed-plugin for screens smaller than 900px */
        .fixed-plugin {
            display: none;
        }
    
        /* Show fixed-plugin if screen width is 900px or more */
        @media (min-width: 900px) {
            .fixed-plugin {
                display: block;
            }
        }
    </style>
    <script>
        // For cases when page is loaded with a width below 900px, we need to toggle visibility on resize
        window.addEventListener('resize', function () {
            var fixedPlugin = document.querySelector('.fixed-plugin');
            if (window.innerWidth > 900) {
                fixedPlugin.style.display = 'none';  // Hide when screen is smaller than 900px
            } else {
                fixedPlugin.style.display = 'block'; // Show when screen is 900px or larger
            }
        });
    
        // Initial check on page load
        document.addEventListener('DOMContentLoaded', function () {
            var fixedPlugin = document.querySelector('.fixed-plugin');
            if (window.innerWidth > 900) {
                fixedPlugin.style.display = 'none';
            } else {
                fixedPlugin.style.display = 'block';
            }
        });
    </script>
@endsection


