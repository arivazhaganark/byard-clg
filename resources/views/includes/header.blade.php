
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>AT-Networks</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/logo-nav.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Antic+Slab|Heebo" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> 
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/thumbGrid.css') }}"/>
  </head>
  <body>
<div class="main_wrapper">
    <!-- Fixed navbar -->
  <!-- menu part -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/images/log3.png') }}" class="img-responsive w-200px"/>
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
         <ul class="nav navbar-nav p-0-60 font-18 font-14-320">
      <li class="dropdown mega-dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Expertise <span class="caret"></span></a>        
        <ul class="dropdown-menu mega-dropdown-menu">
          <li class="col-sm-3 mt-26 pl-0px">
            <ul>
              <li class="dropdown-header">Video Conferencing</li>
                  <li><a href="{{url('/product')}}" target="blank">Virtual Classroom</a></li>
                <li><a href="{{url('/subpage')}}" target="blank">Surgery Recording</a></li>
                <li><a href="#">Video Streaming</a></li>
                 <li><a href="#">E-Learning</a></li>
                      <li><a href="#">Telemedicine</a></li>
                 
            </ul>
          </li>
        </ul>       
      </li>
            <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">What we do <span class="caret"></span></a>        
        <ul class="dropdown-menu mega-dropdown-menu">
          <li class="col-sm-2 ml-36 pl-0px">
              <ul>
              <li class="dropdown-header">ICT & AV Design</li>
              <li><a href="">Audio </a></li>
                            <li><a href="#">Video </a></li>
                            <li><a href="#">GPON Network</a></li>
              <li><a href="#">Wireless Network</a></li>
                  <li><a href="#"> Network Security</a></li>
              <li><a href="#">Solar</a></li>
               <li><a href="#"> Wind</a></li>
               <li><a href="#"> ioT</a></li>
            </ul>
          </li>
          <li class="col-sm-2">
            <ul>
              <li class="dropdown-header">Systems Integration</li>
              <li><a href="#">Systems Integration</a></li>
              <li><a href="#">Onsite Technical Support</a></li>
              <li><a href="#">Networked AV Trainng</a></li>
               </ul>
          </li>
          <li class="col-sm-3">
            <ul>
              <li class="dropdown-header">Distribution</li>
              <li><a href="#">ICT & AV Design</a></li>
              <li><a href="#">Systems Integration</a></li>
              <li><a href="#">Distribution </a></li>
               <li><a href="#">Software </a></li>
                <li><a href="#">SaaS </a></li>                     
            </ul>
          </li>
        </ul>       
      </li>
              <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Verticals <span class="caret"></span></a>        
        <ul class="dropdown-menu mega-dropdown-menu">
        <li class="col-sm-2 ml-46">
              <ul>
         <li class="dropdown-header">Verticals</li>
              <li><a href="#">IT / AV Resellers</a></li>
              <li><a href="#">Education</a></li>
              <li><a href="#">Healthcare</a></li>
              <li><a href="#">Enterprise</a></li>
                <li><a href="#">Government</a></li>
                     <li><a href="#">Hotels </a></li>
                      <li><a href="#">Retail</a></li>
                       <li><a href="#">Media</a></li>
                           <li><a href="#">Event Management</a></li>
          </ul>
          </li>
          </ul>
          </li>
    </ul>
             <ul class="nav navbar-nav navbar-right font-18 font-14-320">
        <li class="w-50-320">
      <form class="search-box pull-right" action="#" method="post"> 
      <span class="icon-magnifier search-icon">
           <i class="fa fa-search p-22 c-pointer p-2-320"></i>
      </span>      
         
 
  <div class="form-group">
                <div class="icon-addon addon-lg">
              <input type="text" name="search-bar" class="search-bar form-control search-animate"  placeholder="Search">
                </div>
            </div>

</form>

        </li>
          <li class="dropdown mega-dropdown loginform w-30-320">
        <a href="#" class="dropdown-toggle login mt-0-320px" data-toggle="dropdown">
        <i class="fa fa-user font-18 v-a-t pr-5px"></i>
       <span class="v-a-s"> Login </span>
        </a>
        <ul class="dropdown-menu mega-dropdown-menu p-0" style="width:20% !important;right: 4px;">
        <div class="modal-body">
               <form>
                 <div class="form-group mb-0">
                <div class="icon-addon addon-md">
                    <input type="text" placeholder="user@domainname.com" class="form-control p-search" id="email">
                    <label for="email" class="glyphicon glyphicon-envelope" rel="tooltip" title="email"></label>
                </div>
                <div class="icon-addon addon-md">
                    <input type="text" placeholder="Password" class="form-control p-search" id="email">
                    <label for="passowrd" class="glyphicon glyphicon-lock" rel="tooltip" title="email"></label>
                </div>
            </div>
  <div class="form-group text-center">
    <p for="newuser" class=""><a href="" class="font-blue">New User</a></p>
  </div>
    <div class="modal-footer pt-5">
                    <button type="cancel" class="btn btn-default bg-blue border-orange font-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary bg-blue border-orange">Login</button>
                </div>
</form>
                </div>
        </ul>
        </li>
      </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<!-- menu end-->