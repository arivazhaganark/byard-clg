<head>
    <meta charset="UTF-8">
    <title> Admin - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/css/Admin.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap-datepicker.css') }}" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/admin_page.css') }}" />    
    
    <link href="{{ asset('/css/skins/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />

    <!-- jQuery 2.1.4 --> 
    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script> 
    <script src="{{ asset('js/jquery.validate.js') }}"></script> 
    <script src="{{ asset('js/common.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('/js/bootstrap-datepicker.js') }}"></script>
    <script>
        var app_url =  '{{ url('') }}'; 
    </script>

</head>
