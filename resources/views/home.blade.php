@extends('layouts.frontpage')

@section('content')

<!-- banner part -->
<section>
<div class="banner" id="startchange">
<div id="myCarousel" class="carousel slide" data-ride="carousel"> 
  <!-- Indicators -->
  
<!--   <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol> -->
  <div class="carousel-inner" id="">
    <div class="item active"> 
    <img src="{{ asset('assets/images/banner1.jpg') }}" style="width:100%" alt="First slide" class="">
      <div class="container">
        <div class="carousel-caption">
          <h1 class="text-left font-white">Slide 1</h1>
          <p class="text-left font-white">Aenean a rutrum nulla. Vestibulum a arcu at nisi tristique pretium.</p>
        </div>
      </div>
    </div>
    <div class="item"> 
    <img src="{{ asset('assets/images/banner2.jpg') }}" style="width:100%" data-src="" alt="Second"  class="">
 <div class="container">
        <div class="carousel-caption">
          <h1 class="text-left font-white">Slide 2</h1>
          <p class="text-left font-white">Aenean a rutrum nulla. Vestibulum a arcu at nisi tristique pretium.</p>
        </div>
      </div>
    </div>
    <div class="item">
     <img src="{{ asset('assets/images/banner3.jpg') }}" style="width:100%" data-src="" alt="Third slide"  class="">
   <div class="container">
        <div class="carousel-caption">
          <h1 class="text-left font-white">Slide 3</h1>
          <p class="text-left font-white">Aenean a rutrum nulla. Vestibulum a arcu at nisi tristique pretium.</p>
        </div>
      </div>
    </div>
     <div class="item">
     <img src="{{ asset('assets/images/banner4.jpg') }}" style="width:100%" data-src="" alt="Third slide"  class="">
   <div class="container">
        <div class="carousel-caption">
          <h1 class="text-left font-white">Slide 4</h1>
          <p class="text-left font-white">Aenean a rutrum nulla. Vestibulum a arcu at nisi tristique pretium.</p>
        </div>
      </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
     <a class="right carousel-control" href="#myCarousel" data-slide="next">
     <span class="glyphicon glyphicon-chevron-right"></span>
     </a>
  </div>

  </div>
  </div>

  </section>
<section style="background:#efefe9;" class="expertise">
<div class="container">
<div class="row">
<h3 class="font-blue text-center mb-2">Expertise</h3>
<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 border-r p-28">
                <img src="{{ asset('assets/images/ar-glasses.png') }}" class="img-responsive m-auto mb-20" alt="">
                <h4 class="text-center pt-5 pb-5 m-0"><strong>Virtual Classroom</strong></h4>
                <p class="text-center line-h-28">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut. 
                </p>
                <a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</div>
<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 border-r p-28">
                <img src="{{ asset('assets/images/vintage-camcorder.png') }}" class="img-responsive m-auto mb-20" alt="">
                <h4 class="text-center pt-5 pb-5 m-0"><strong>
                Surgery Recording</strong></h4>
                <p class="text-center line-h-28">
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut. </p>
                <a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</div>
<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 p-28">
                <img src="{{ asset('assets/images/streaming.png') }}" class="img-responsive m-auto mb-20" alt="">
                <h4 class="text-center pt-5 pb-5 m-0"><strong>
                Video Streaming </strong></h4>
                <p class="text-center line-h-28">
             Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut. 
                </p>
             <a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</div>  
<div class="clearfix"></div>
<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 border-r p-28 mt-5 col-lg-offset-2">
                <img src="{{ asset('assets/images/e-learning.png') }}" class="img-responsive m-auto mb-20" alt="">
                <h4 class="text-center pt-5 pb-5 m-0"><strong>
                E-Learning </strong></h4>
                <p class="text-center line-h-28">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut. 
                </p>
             <a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</div>
<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 p-28 mt-5">
                <img src="{{ asset('assets/images/video-cam.png') }} " class="img-responsive m-auto mb-20" alt="">
                <h4 class="text-center pt-5 pb-5 m-0"><strong>
                Telemedicine </strong></h4>
                <p class="text-center line-h-28">
             Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut. 
                </p>
            <a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</div>
</div>
</section>

 <section class="expertise">
 <div class="mt-5 mb-5">
    <div class="container">
    <div class="row">
         <h3 class="font-blue text-center mb-2">What We Do</h3>
         <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2-320">
         <div class="border software p-12 col-lg-12" style="/*background-color: rgba(187, 120, 36, 0.1);*/     background-color: #ff757c;">
<h4 class="font-white"><strong>Distribution </strong></h4>
<img src="{{ asset('assets/images/cargo-ship-white.png') }}" class="img-responsive m-auto"/>
<ul class="distribution col-sm-6 mt-6 mb-4">
<li>
   <a href="" class="font-white">Video Conferencing</a>
   </li>
   <li>
   <a href="" class="font-white">Video Recording</a>
   </li>
   <li>
   <a href="" class="font-white">Video Streaming</a>
   </li>
   <li>
   <a href="" class="font-white">Video Cameras</a>
   </li>
   <li>
   <a href="" class="font-white">Audio Microphones</a>
   </li>
       <li>
   <a href="" class="font-white">Visualisers</a>
   </li>
   </ul>
   <ul class="distribution col-sm-6 mt-6 mb-4">

       <li>
   <a href="" class="font-white">Presentation Scalar Switchers</a>
   </li>
   <li>
   <a href="" class="font-white">Operation Theatre </a>
   </li>
   <li>
   <a href="" class="font-white">ICU Integration</a>
   </li>
   <li>
   <a href="" class="font-white">Mobile Video Conferencing</a>
   </li>
     <li class="text-right pb-5 mb-2">
   <a href="" class="font-grey read-arrow-white"><i class="fa fa-angle-right"></i></a>
   </li>
            </ul>
         </div> 
            </div> 
          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
<div class="row pb-15">
 <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2-320">
 <div class="border software p-12" style="/*background-color: rgb(232, 246, 243);*/
     background-color: rgb(41, 116, 168);">
<h4 class="font-white"><strong>Software </strong></h4>
<img src="{{ asset('assets/images/software-white.png') }}" class="img-responsive pull-right"/>
<div class="clearfix"></div>
<ul class="software1">
<li>
   <a href="" class="font-white">eGuru- audio</a>
   </li>
   <li>
   <a href="" class="font-white">eGuru - audio & Video</a>
   </li>
     <li>
   <a href="" class="font-white">eGuru - Audio & Screen</a>
   </li>
    <li>
   <a href="" class="font-white">eGuru - Audio, Video & Screen </a>
   </li>
    <li class="text-right">
   <a href="" class="font-grey read-arrow-white"><i class="fa fa-angle-right"></i></a>
   </li>
   </ul>
    </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2-320">
     <div class="border software p-12" style="/*background-color: rgb(243, 239, 230)*/     background-color: rgb(0, 182, 137);">
<h4 class="font-white"><strong>SaaS </strong></h4>
<img src="{{ asset('assets/images/virtual-reality-white.png') }}" class="img-responsive pull-right"/>
<div class="clearfix"></div>
<ul class="software3">
<li>
   <a href="" class="font-white">Video Conferencing</a>
   </li>
   <li>
   <a href="" class="font-white">Video Streaming</a>
   </li>
     <li>
   <a href="" class="font-white">Telemedicine Network</a>
   </li>
    <li>
   <a href="" class="font-white">Teleeducation Network</a>
   </li>
     <li class="text-right">
   <a href="" class="font-grey read-arrow-white"><i class="fa fa-angle-right"></i></a>
   </li>
   </ul>
    </div> 
    </div>

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 mb-2-320">
     <div class="border software p-12" style="/*background-color: rgb(242, 239, 237);*/ background-color: rgb(249, 186, 72);white">
<h4 class="font-white"><strong>Systems Integration </strong></h4>
<img src="{{ asset('assets/images/3d-printer-white.png') }}" class="img-responsive pull-right"/>
<div class="clearfix"></div>
<ul class="software">
<li>
   <a href="" class="font-white">Systems Integration</a>
   </li>
   <li>
   <a href="" class="font-white">Onsite Technical Support</a>
   </li>
     <li class="pb-26">
   <a href="" class="font-white">Networked AV Trainng</a>
   </li>
   <li class="text-right">
   <a href="" class="font-grey read-arrow-white"><i class="fa fa-angle-right"></i></a>
   </li>
   </ul>
    </div> 
    </div>
</div>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <div class="border software p-12 col-lg-12  col-lg-12 col-sm-12 col-md-12 col-xs-12" style="/*background: rgba(246, 142, 52, 0.14);*/    background: rgb(18, 116, 129);">
<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
<h4 class="font-white"><strong>ICT & AV Design</strong></h4>
<img src="{{ asset('assets/images/pencil-case-white.png') }}" class="img-responsive m-auto"/>
</div>
<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
<ul class="distribution1 col-sm-5">
<li>
   <a href="" class="font-white">Audio</a>
   </li>
   <li>
   <a href="" class="font-white">Video</a>
   </li>
   <li>
   <a href="" class="font-white">GPON Network</a>
   </li>
   <li>
   <a href="" class="font-white">Wireless Network</a>
   </li>
   </ul>
   <ul class="distribution1  col-sm-7">
      <li>
   <a href="" class="font-white">Network Security</a>
   </li>
    <li>
   <a href="" class="font-white">Solar</a>
   </li>
       <li>
   <a href="" class="font-white">Wind</a>
   </li>
   <li>
   <a href="" class="font-white">ioT
   </a>
   </li>
   <li class="line-h-0">
        <span  class="ml-10 pull-right">
   <a href="" class="font-grey read-arrow-white">
   <i class="fa fa-angle-right"></i></a></span>
   </li>
            </ul>
            </div>
            </div>
            </div>
</div>
    </div>
    </div>
    </div>
    </div>
    </section>

    <!-- verticals-->
    <section class="Verticals bg-grey">
    <div class="mt-5 mb-5">
    <div class="container">
    <div class="row">
    <h3 class="font-blue text-center">Verticals</h3>
      <ul class="work-withUs-points clear">
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/agent.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>IT / AV Resellers</strong></h6>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.</p>
 <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
                        </div>
                    </li>
                    <li class="clear">
        <div class="workWith-img"><img src="{{ asset('assets/images/presentation.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Education</strong></h6>
                            <p>
                       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.  </p>
 <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
                          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/blood-bag.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Healthcare</strong></h6>
                            <p>
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/area-chart.png') }} " alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Enterprise</strong></h6>
                            <p>
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/government.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Government</strong></h6>
                            <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/hotel.png') }} " alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Hotels</strong> </h6>
                            <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/online-store.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Retail </strong></h6>
                            <p>
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img"><img src="{{ asset('assets/images/network.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Media</strong> </h6>
                            <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                    <li class="clear">
                        <div class="workWith-img">
        <img src="{{ asset('assets/images/calendar.png') }}" alt=""></div>
                        <div class="workWith-txt">
                            <h6><strong>Event Management</strong></h6>
                            <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec tempor enim. Aliquam interdum nisl libero, non aliquam urna aliquam ut.Proin dapibus, nulla eu blandit semper, magna ligula fermentum quam.
                            </p>
                             <span>
<a href="" class="font-grey read-arrow pull-right"><i class="fa fa-angle-right"></i></a>
</span>
          
                        </div>
                    </li>
                </ul>
                </div>
                </div>
                </div>
    </section>
     <section class="testimonial">
    <div class="mt-5 mb-5">
    <div class="container">
    <div class="row">
      <h3 class="font-blue text-center mb-2">Testimonial & <span class="font-black">Our Clients</span></h3>  
    <div class="col-lg-12">
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 border mb-2-320">
      <div class="carousel slide" data-ride="carousel" id="quote-carousel">
        <!-- Bottom Carousel Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#quote-carousel" data-slide-to="0" class="active"></li>
          <li data-target="#quote-carousel" data-slide-to="1"></li>
          <li data-target="#quote-carousel" data-slide-to="2"></li>
          <li data-target="#quote-carousel" data-slide-to="3"></li>
        </ol>
        
        <div class="carousel-inner">
        
           <div class="item active">
    
              <div class="quotation-mark">
                <div class="pt-8">
          <!--       <span class="quotation-mark pull-left">“</span> -->
                  <p class="font-16 font-14-320">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut rutrum elit in arcu.
                  Maecenas tempus interdum elit, in tincidunt leo auctor vel. Duis tristique, lectus quis.
                  </p>
                  <small>Name | <span class="font-blue">Desination</span></small>
                </div>
              </div>
           
          </div>
          <div class="item">
                   <div class="quotation-mark">
                <div class="pt-8">
      <!--           <span class="quotation-mark pull-left">“</span> -->
                  <p class="font-16 font-14-320">
             Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut rutrum elit in arcu.
                  Maecenas tempus interdum elit, in tincidunt leo auctor vel. Duis tristique, lectus quis.
                  </p>
                  <small>Name | <span class="font-blue">Desination</span></small>
                </div>
              </div>
          </div>
          <div class="item">
                   <div class="quotation-mark">
                <div class="pt-8">
              <!--   <span class="quotation-mark pull-left">“</span> -->
                   <p class="font-16 font-14-320">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut rutrum elit in arcu.
                  Maecenas tempus interdum elit, in tincidunt leo auctor vel. Duis tristique, lectus quis.
                   </p>
                  <small>Name | <span class="font-blue">Desination</span></small>
                </div>
              </div>
          </div>
          <div class="item">
                 <div class="quotation-mark">
                <div class="pt-8">
              <!--   <span class="quotation-mark pull-left">“</span> -->
               <p class="font-16 font-14-320">
             Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut rutrum elit in arcu.
                  Maecenas tempus interdum elit, in tincidunt leo auctor vel. Duis tristique, lectus quis.
               </p>
                <small>Name | <span class="font-blue">Desination</span></small>
                </div>
              </div>
          </div>
        </div>
  </div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 pl-6">
    <section class="content">
   <div id="myCarousel5" class="fade-carousel carousel-fade carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active">
                    <ul class="fff">
                        <li class="col-sm-4 pr-0 pl-5">
                <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/at-logo1.jpg') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/at-logo2.jpg') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/at-logo3.jpg') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/at-logo4.jpg') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/at-logo5.jpg') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/at-logo6.jpg') }}" alt=""></a>
                </div>
                            </div>
                        </li>

                    </ul>
              </div><!-- /Slide1 --> 
            <div class="item">
                    <ul class="fff">
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo2.png') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo3.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo3.png') }}" alt=""></a>
                </div>
              <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo2.png') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo2.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                    </ul>
              </div><!-- /Slide2 --> 
            <div class="item">
                    <ul class="fff">
                        <li class="col-sm-4 pr-0 pl-5"> 
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo2.png') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo5.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo3.png') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                        <li class="col-sm-4 pr-0 pl-5">
              <div class="fff">
                <div class="thumbnail">
                  <a href="#"><img src="{{ asset('assets/images/logo5.png') }}" alt=""></a>
                </div>
                <div class="thumbnail">
                <a href="#"><img src="{{ asset('assets/images/logo3.png') }}" alt=""></a>
                </div>
                            </div>
                        </li>
                    </ul>
              </div><!-- /Slide3 --> 
        </div>
        </div>       
     <!-- /.control-box -->   
                              
    </div><!-- /#myCarousel -->

</section>

     
@endsection