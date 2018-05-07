<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
    @include('backend.layouts.partials.htmlheader')
@show

<body class="skin-custom sidebar-mini">
<div class="wrapper">

    @include('backend.layouts.partials.mainheader')

    @include('backend.layouts.partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @include('backend.layouts.partials.contentheader')

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('backend.layouts.partials.footer')

</div><!-- ./wrapper -->

@section('scripts')
    @include('backend.layouts.partials.scripts')
@show

</body>
</html>
