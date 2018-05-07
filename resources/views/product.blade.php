@extends('layouts.frontpage')

@section('content')
<section class="breadcrum">
 <div class="bg-lit">
    <div class="container">
    <div class="row">
    <div class="mt-5">
    <a href=""><i class="fa fa-home p-2-320 p-15 fa-2x font-orange"></i></a>
    <span>/</span>
    <span>Our Product &nbsp;</span>
    <span>&nbsp; / &nbsp;</span>
    <span>Telly Video Confrencing &nbsp;/ &nbsp;</span>
    <span>Telly 2000</span>
      </div>
      </div>
      </div>
      </div>
      </section>

 <section class="Content">
 <div class="container">
 <div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <h3 class="font-orange">
      <strong>Telly Video Confrencing</strong>
      </h3>
 </div>
 </div>
    <div class="row"> 
      <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
      <div class="border-grey">
       <div class="clearfix" style="">
                        <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                            <li data-thumb="{{ asset('assets/images/thumb/product1.png') }} " class="fancybox"  href="{{ asset('assets/images/product1.png') }} " data-fancybox-group="gallery" title="model numbers"> 
                                <img src="{{ asset('assets/images/product1.png') }}"  class="img-responsive" data-highres="{{ asset('assets/images/product1.png') }}" data-caption="model numvers 90uu78" />
                            </li>
                            <li data-thumb="{{ asset('assets/images/thumb/product2.jpg') }} "  class="fancybox"  href="images/product2.jpg" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style="width: 125px;    height: 80px;"> 
                                <img src="{{ asset('assets/images/product2.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product2.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                               <li data-thumb="{{ asset('assets/images/thumb/product3.jpg') }}"  class="fancybox"  href="images/product3.jpg" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style=""> 
                                <img src="{{ asset('assets/images/product3.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product3.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                            <li data-thumb="{{ asset('assets/images/thumb/product4.jpg') }}"  class="fancybox"  href="{{ asset('assets/images/product4.jpg') }}" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style=""> 
                                <img src="{{ asset('assets/images/product4.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product4.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                            <li data-thumb="{{ asset('assets/images/thumb/product5.jpg') }}"  class="fancybox"  href="{{ asset('assets/images/product5.jpg') }}" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style=""> 
                                <img src="{{ asset('assets/images/product5.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product5.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                            <li data-thumb="{{ asset('assets/images/thumb/product6.jpg') }}"  class="fancybox"  href="{{ asset('assets/images/product6.jpg') }}" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style=""> 
                                <img src="{{ asset('assets/images/product6.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product6.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                             <li data-thumb="{{ asset('assets/images/thumb/product7.jpg') }}"  class="fancybox"  href="{{ asset('assets/images/product7.jpg') }}" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet" style=""> 
                                <img src="{{ asset('assets/images/product7.jpg') }}" class="img-responsive" data-highres="{{ asset('assets/images/product7.jpg') }}" data-caption="Parturient Bibendum Malesuada Etiam"/>
                            </li>
                        </ul>
                    </div>
      </div>
      </div>
       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                 <h3 class="font-black m-0">
      <strong>Telly 2000</strong>
      </h3>
          <h5 class="font-black">
      <strong>Features</strong>
      </h5>
   <p class="line-h-26">     
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque malesuada maximus orci eget vulputate. Donec ornare volutpat sapien vitae eleifend. Phasellus vitae dictum sem, eget sagittis purus. Curabitur lacus mauris, scelerisque aliquam quam eget, placerat posuere ligula. Donec luctus aliquet aliquam. Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.
   </p>
   <h5 class="font-black">
      <strong>Specification</strong>
      </h5>
      <div class="portable">
      <div class="col-xs-12 col-sm-2 col-md-4 col-lg-4 p-0">
        <h6>Portable:</h6>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-8">
        <p>Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.</p>
      </div>
      </div>
      <div class="portable">
      <div class="col-xs-12 col-sm-2 col-md-4 col-lg-4 p-0">
        <h6>Flexible:</h6>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-8">
        <p>Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.</p>
      </div>
      </div>
      <div class="cloudcontroll">
      <div class="col-xs-12 col-sm-2 col-md-4 col-lg-4 p-0">
        <h6>Cloud Controll:</h6>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-8">
        <p>Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.</p>
      </div>
      </div>
      <button class="bg-orange font-white p-button m-auto d-block">Buy Now</button>
      </div>
      </div>
      </div>
      </section>

      <section class="tabs">
        <div class="container"><h2>
        Description</h2></div>

<div id="exTab2" class="container"> 
<div class="mt-1 mb-5">
<ul class="nav nav-tabs">
      <li class="active">
        <a  href="#1" data-toggle="tab" class="border-r border-grey font-black">Overview</a>
      </li>
      <li><a href="#2" data-toggle="tab" class="border-r border-grey font-black">Without clearfix</a>
      </li>
      <li><a href="#3" data-toggle="tab" class="border-r border-grey font-black">Solution</a>
      </li>
    </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="1">
          <p class="mt-10">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque malesuada maximus orci eget vulputate.
          Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.
          </p>
        </div>
        <div class="tab-pane" id="2">
          <p class="mt-10">
           Donec ornare volutpat sapien vitae eleifend.
          Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.
          </p>
        </div>
        <div class="tab-pane" id="3">
        <p class="mt-10">
        Phasellus vitae dictum sem, eget sagittis purus. Curabitur lacus mauris, scelerisque aliquam quam eget, placerat posuere ligula. Suspendisse in arcu aliquam, blandit purus vel, pharetra tellus. Praesent aliquet tempus aliquet.
          </p>
        </div>
      </div>
  </div>
  </div>
      </section>

     
@endsection