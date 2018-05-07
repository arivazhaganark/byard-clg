
$('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
   });
$(document).ready(function () {
     $('#setting_form').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            
            from_email: {
                required: true,
                
            },
            from_email_display_name: {
                required: true
                
            },
            support_email: {
                required: true
            },
            facebook_link: {
                required: true
            },
           twitter_link: {
                required: true
            },
           youtube_link: {
                required: true
            },
            header_script: {
                required: true
            },
            footer_script: {
                required: true
            }
          
        },

        messages: {
            from_email: {
                required: 'Email is required'
            },
            from_email_display_name: {
                required: 'Name is required'
            },
            support_email: {
                required: 'Support email is required'
            },
             facebook_link: {
                required: 'Facebook link is required'
            },
            twitter_link: {
                required: 'Twitter link is required'
            },
            youtube_link: {
                required: 'Youtube link is required'
            },
            header_script: {
                required: 'Header script is required'
            },
            footer_script: {
                required: 'footer script is required'
            }

        },
        highlight: function (element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            $(element).closest('div').append(error);
        }
    });
});

$(".btn-primary").click(function () {
    $('#setting_form').validate();
    var validated = $('#setting_form').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Setting has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#setting_form').submit(); }, 3000);
    }
});