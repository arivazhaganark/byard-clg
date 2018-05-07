
<!--footer part-->
    <footer class="footer">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bg-grey mt-5">
      <div class="container-fluid">
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 border-r border-r-320">
<h4 class="font-blue"><strong>Recent Feed</strong></h4>
<img src="{{ asset('assets/images/pencile.jpg') }}" class="img-responsive mb-2" style="width: 260px;height: 150px;" />
<p>Lorem ipsum dolor sit amet, In maximus rhoncus neque, vel ultricies eros viverra vitae. consectetur adipiscing elit. </p>
<ul class="social-icons pl-0px">
  <li>
   <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-facebook">
   <i class="fa fa-facebook"></i>
   </a>
  </li>
  <li>
    <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-twitter">
    <i class="fa fa-twitter"></i>
    </a>
  </li>
  <li>
    <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-linkedin"><i class="fa fa-linkedin"></i></a>
  </li>
   <li>
  <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-google-plus"><i class="fa fa-google-plus"></i></a>
  </li>
  <li>
    <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-pinterest"><i class="fa fa-pinterest"></i></a>
  </li>
  <li class="hidden-sm">
    <a href="#" class="btn azm-social azm-size-48 azm-r-square azm-youtube"><i class="fa fa-youtube"></i></a>
  </li>
 </ul>
        </div>
          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <div class="row">
        <div class="col-xs-6 col-sm-4 col-md-6 col-lg-3">
         <h4 class="font-blue"><strong>Resources</strong></h4>
         <p><a href="" class="font-black">Datasheets </a></p>
         <p><a href="" class="font-black">Brochures</a></p>
         <p><a href="" class="font-black">Videos</a></p>
         <p><a href="" class="font-black">Presentations</a></p>
         <p><a href="" class="font-black">Pictorial Diagrams</a></p>
         <p><a href="" class="font-black">Connectivity Diagrams</a></p>
         <p><a href="" class="font-black">Case Study Questionnaires</a></p>
         </div>
         <div class="col-xs-6 col-sm-4 col-md-4 col-lg-2 col-half-offset">
         <h4 class="font-blue"><strong>About Us</strong></h4>
         <p><a href="" class="font-black">Our Values</a></p>
           <p><a href="" class="font-black">Vision</a></p>
             <p><a href="" class="font-black">Awards & Achievements</a></p>
              <p><a href="" class="font-black">History</a></p>
                <p><a href="" class="font-black">A & T in Pictures</a></p>
         </div>

          <div class="col-xs-6 col-sm-4 col-md-4 col-lg-2 col-half-offset">
         <h4 class="font-blue"><strong>Case Studies</strong></h4>
         <p><a href="" class="font-black">Education</a></p>
           <p><a href="" class="font-black">Healthcare</a></p>
           <p><a href="" class="font-black">Enterprise</a></p>
            <p><a href="" class="font-black">Government</a></p>
            <p><a href="" class="font-black">Hotels</a></p>
         </div>
          <div class="col-xs-6 hidden-sm col-md-4 col-lg-2 col-half-offset">
         <h4 class="font-blue"><strong>Resellers</strong></h4>
         <p><a href="" class="font-black">Value Added Services / Sign UP </a></p>
         <p><a href="" class="font-black">Deal Registration</a></p>
         <p><a href="" class="font-black">Registered Deals</a></p>
         <p></p>
         </div>
          <div class="col-xs-6 hidden-sm col-md-4 col-lg-2 col-half-offset">
        <h4 class="font-blue"><strong>Connect</strong></h4>
         <p><a href="" class="font-black">Office </a></p>
         <p><a href="" class="font-black">Support</a></p>
         <p><a href="" class="font-black">Sales / Demo</a></p>
         <p><a href="" class="font-black">Careers</a></p>
         <p><a href="" class="font-black">Press</a></p>
         </div>
         </div>
          <div class="row">
           <p class="text-right"><a href="" class="font-black">Copy rigths. All rights reserved.</a></p>
</div> 
         </div>
        </div>
      </div>
     
    </footer>

</div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
 $("#myModal").modal({
        show: false,
        backdrop: 'static'
    }); 
    </script>
 <script>
jQuery(function($) {
  $('.dropdown').hover(function() {
    $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
  }, function() {
    $(this).find('.dropdown-menu').first().stop(true, true).delay(400).slideUp();
  });
});
/* after scroll color menu change */
$(document).ready(function(){       
   var scroll_start = 20;
   var startchange = $('#startchange');
   var offset = startchange.offset();
    if (startchange.length){
   $(document).scroll(function() { 
      scroll_start = $(this).scrollTop();
      if(scroll_start > offset.top) {
          $(".navbar-default").css('background-color', '#fff');
       } else {
          $('.navbar-default').css('background-color', 'transparent');
       }
   });
    }
});

$(window).scroll(function() {
  if ($(document).scrollTop() > 50) {
    $('nav').addClass('shrink');
  } else {
    $('nav').removeClass('shrink');
  }
});


 </script>
 <script type='text/javascript'>
      // When your page loads
      $(function(){
         // When the toggle areas in your navbar are clicked, toggle them
         $("#search-button, #search-icon").click(function(e){
             e.preventDefault();
             $("#search-button, #search-form").toggle();
         });
          });
           $('.search-box .icon-magnifier').on('click', function (e) {
           $('.search-bar').toggleClass("search-bar-open");
           $('.search-icon').toggleClass("search-icon-open");
           e.preventDefault();

       });
</script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
  <script src="{{ asset('assets/js/lightslider.js') }}"></script> 
            <script>
                $(document).ready(function () {
                    $("#content-slider").lightSlider({
                        loop: true,
                        keyPress: true
                    });
                    $('#image-gallery').lightSlider({
                        gallery: true,
                        item: 1,
                        thumbItem: 4,
                        slideMargin: 0,
                        speed: 500,
                        auto: true,
                        loop: true,
                        onSliderLoad: function () {
                            $('#image-gallery').removeClass('cS-hidden');
                        }
                    });
                    $('.fancybox').fancybox();
                });


$(function(){
$('a[title]').tooltip();
});

            </script>
   <!-- sub page -->
<script>
   $(document).ready(function(){

    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "all")
        {
            //$('.filter').removeClass('hidden');
            $('.filter').show('1000');
        }
        else
        {
//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });

});
</script>
  </body>
</html>
