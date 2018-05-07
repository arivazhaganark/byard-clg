<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @yield('htmlheader_title', '') 
        <small>@yield('contentheader_description')</small>
    </h1>
    <ol class="breadcrumb">
        @if (Request::segment(2) === 'home')
        <li><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i> Home </a></li>
        <li class="active">Here</li>
        @endif
       
        @if (Request::segment(2) === 'doctor' && Request::segment(3) == '')
        <li><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i> Home  </a></li>
        <li class="active">List Doctor</li>
        @endif
        @if (Request::segment(2) === 'doctor' && Request::segment(3) === 'create')
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home  </a></li>
        <li><a href="{{ url('/admin/doctor') }}"><i class="fa fa-fighter-jet"></i> Doctor  </a></li>
        <li class="active"> Add Doctor </li>
        @endif 
        @if (Request::segment(2) === 'doctor' && Request::segment(3) === 'show')
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home  </a></li>
        <li><a href="{{ url('/admin/doctor') }}"><i class="fa fa-fighter-jet"></i> Doctor  </a></li>
        <li class="active"> View Doctor </li>
        @endif 

    </ol>
</section>

