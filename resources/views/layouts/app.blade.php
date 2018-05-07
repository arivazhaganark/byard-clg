<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BRICKYARD</title>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
   
     <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <!--custom style -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
    <!-- jQuery 2.1.4 --> 
    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script> 
    <script src="{{ asset('js/jquery.validate.js') }}"></script> 

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>        
    </script>

    <script type="text/javascript">
        var app_url =  '{{ url('') }}';
    </script>

    <style type="text/css">.error { font-size: 11px; font-style: italic; color: crimson; }</style>
</head>
<body>
    <div id="app">
        @if(Auth::guard('user')->check())
        <nav class="navbar navbar-default navbar-static-top custom-navbar">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    @brandlogo
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if(auth()->guard('user')->user()->usertype=='CSF')

                    <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ url('user/home') }}">Home</a></li>
                    <li><a href="{{ url('user/filemanagerclgstf') }}">File Manager</a></li>
 		    <li><a href="http://139.59.25.31/upload/public/index.php?m=c" target='_blank'>Video Editor</a></li>
                    <li><a href="" data-toggle="modal" data-target="#feedback">Feedback</a></li>
 		    <li><a href="{{ url('user/help') }}">Help</a></li>
                  </ul>

                    @elseif(auth()->guard('user')->user()->usertype=='SSF')
                    <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ url('user/home') }}">Home</a></li>
                    <li><a href="{{ url('user/filemanager') }}">File Manager</a></li>
                    <li><a href="http://139.59.25.31/upload/public/index.php?m=s" target='_blank'>Video Editor</a></li>
		    <li><a href="" data-toggle="modal" data-target="#feedback">Feedback</a></li>
                    <li><a href="{{ url('user/help') }}">Help</a></li>
                    </ul>
		    @elseif(auth()->guard('user')->user()->usertype=='CS')
                    <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ url('user/home') }}">Home</a></li>
                    <li><a href="{{ url('user/filemanagerclgstudent') }}">File Manager</a></li>
 		   
                    <li><a href="{{ url('user/help') }}">Help</a></li>
                    </ul>
                    @elseif(auth()->guard('user')->user()->usertype=='SS')
                    <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ url('user/home') }}">Home</a></li>
                    <li><a href="{{ url('user/filemanagersclstudent') }}">File Manager</a></li>
                    <li><a href="{{ url('user/help') }}">Help</a></li>
                    </ul>
                    @endif
                  
                  

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php 
                        //dd(Auth::guard('user')->check() );
                        ?> 
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::guard('user')->user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="" data-toggle="modal" data-target="#change_password">Change Password</a></li>
                                <li>
                                    <a href="{{ url('user/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ url('user/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>                                

                            </ul>
                        </li> 
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        @yield('content')
    </div>

<!-- Change password Modal -->
<div class="modal fade" id="change_password" role="dialog" tabindex='-1'>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header user_form_subhead">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Change Password</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" name="password_form" id="password_form">
                    {{ csrf_field() }}
                    <!-- text input -->
                    <div class="form-group">
                        <label>Old Password<span class="text-red">*</span></label>
                        <input name="old_password" id="old_password" type="password" class="form-control" placeholder="Enter Current Password" value="" />
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label>New Password <span class="text-red">*</span></label>
                        <input  name="new_password" id="password" type="password" class="form-control" placeholder="Enter New Password" value="" />
                        <div style="color:#2A6692!important; font-size: 10px; font-weight: bold; font-style: italic; text-align: left; line-height: 14px;"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label>Confirm Password<span class="text-red">*</span></label>
                        <input name="confirm_password" id="confirm_password" type="password" class="form-control" placeholder="Enter Confirm New Password" value="" />
                    </div>

                    <div class="box-footer"><br/><center>
                        <button type="button" class="btn btn-primary" id="cPassword">
                            <i class="fa fa-btn"></i> Submit
                        </button></center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#change_password').on('hidden.bs.modal', function () {
            $("#password_form").trigger('reset');
        });
    });
</script>
<script src="{{ asset('js/page_js/change-password-validation.js?v=1.3') }}"></script>


<!-- Feedback Modal -->
<div class="modal fade" id="feedback" role="dialog" tabindex='-1'>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header user_form_subhead">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">We value your feedback!</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" name="feedback_form" id="feedback_form" action="{{ url('/user/feedback/store') }}" onsubmit="return false;">
                    {{ csrf_field() }}
                    <input type="hidden" name="current_url" id="current_url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
                    
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label" style="font-weight: normal;">Your opinion is very important to us and would like to get a feedback on how we are doing.</label>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                        <span id="message_count" style="font-size: 12px;font-style:normal;color: blue;"></span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-footer"><center>
                        <button type="submit" class="btn btn-primary" id="feedBack">
                            <i class="fa fa-btn"></i> Submit
                        </button></center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/page_js/feedback-validation.js?v=1.4') }}"></script>
<script type="text/javascript">
    $('#feedback').on('hidden.bs.modal', function () {
        $("#feedback_form").trigger('reset');
    });
</script>

<!-- Bootstrap 3.3.2 JS --> 
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script> 

</body>
</html>
