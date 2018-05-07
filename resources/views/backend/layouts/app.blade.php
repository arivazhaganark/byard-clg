<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
    @include('backend.layouts.partials.htmlheader')
@show
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <body class="login-page">
	  
	   @yield('content')
	  
@section('scripts')
    @include('backend.layouts.partials.scripts')
@show

</body>
</html>
