@extends('layouts.app')

@section('guest')
@if(\Request::is('login/forgot-password'))
@include('layouts.navbars.guest.nav')
@yield('content')
@else

@yield('content')
{{-- @include('layouts.footers.guest.footer') --}}
@endif
@endsection