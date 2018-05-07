
var app_root = $('#app_url').val();

$(document).ready(function () {
     $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
       });
    $('#add_cms').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            
            title: {
                required: true,
                
            },
            menu_id: {
                required: true
                
            },
            editor1: {
                required: true
            },
            page_link: {
                required: true
            },

           atnet_title: {
                required: true
            },
           atnet_description: {
                required: true
            },
           atnet_keywords: {
                required: true
            },
             position: {
                required: true
            }

        },

        messages: {
            title: {
                required: 'Title is required'
            },
            menu_id: {
                required: 'Menu is required'
            },
            editor1: {
                required: 'Content is required'
            },
             page_link: {
                required: 'link is required'
            },
            atnet_title: {
                required: ' Atnet title is required'
            },
            atnet_description: {
                required: 'Atnet description is required'
            },
            atnet_keywords: {
                required: 'Atnet keywords is required'
            },
             position: {
                required: 'position is required'
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


$(".btn_add_cms").click(function () {
    $('#add_cms').validate();
    var validated = $('#add_cms').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Cms has been added succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#add_cms').submit(); }, 3000);
    }

});



    $(document).ready(function () {
         $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
       });
    $('#edit_cms').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            
            title: {
                required: true,
                
            },
            menu_id: {
                required: true
                
            },
            editor1: {
                required: true
            },
            page_link: {
                required: true
            },
           atnet_title: {
                required: true
            },
           atnet_description: {
                required: true
            },
           atnet_keywords: {
                required: true
            },
             position: {
                required: true
            }

        },
        messages: {
            title: {
                required: 'Title is required'
            },
            menu_id: {
                required: 'Menu is required'
            },
            editor1: {
                required: 'Content is required'
            },
             page_link: {
                required: 'link is required'
            },
            atnet_title: {
                required: ' Atnet title is required'
            },
            atnet_description: {
                required: 'Atnet description is required'
            },
            atnet_keywords: {
                required: 'Atnet keywords is required'
            },
             position: {
                required: 'position is required'
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
   




$(".btn_update").click(function () {
    $('#edit_cms').validate();
    var validated = $('#edit_cms').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Cms has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#edit_cms').submit(); }, 3000);
    }

});

function div_display_fn(val)
{
    if(val=='content') {
        $('#contentdiv').show();
        $('#linkdiv').hide();
    } else {
        $('#contentdiv').hide();
        $('#linkdiv').show();
    }
}

function div_display_fn1(val)
{
    if(val=='content') {
        $('#contentdiv1').show();
        $('#linkdiv1').hide();
    } else {
        $('#contentdiv1').hide();
        $('#linkdiv1').show();
    }
}

