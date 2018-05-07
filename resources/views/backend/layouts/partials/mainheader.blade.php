<!-- Main Header -->
<style type="text/css">
    #aheader{background: #CA151C !important;}
</style>
<header class="main-header" > 
    <!-- Logo -->
    <a href="{{ url('/admin/home') }}" id="aheader" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"> Admin</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b> Admin</b></span>
    </a>
    <!-- Header Navbar -->
    <nav id="aheader" class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" id="aheader" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
       
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">               
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset('/images/user2-160x160.jpg') }}" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">Welcome {{auth()->guard('admin')->user()->name}}, </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('/images/user2-160x160.jpg') }}" class="img-circle" alt="User Image" />
                            <p><?php echo auth()->guard('admin')->user()->name ?>
                               <!--  <small>Member since <?php echo ''; ?></small> -->
                            </p>
                            
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('/admin/change_password') }}" class="btn btn-default btn-flat">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/admin/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="{{ url('/admin/logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
