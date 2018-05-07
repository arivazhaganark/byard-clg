var app_root = $('#app_url').val();

$(document).ready(function () {

    $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
    });
    
    $('#add_menu').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            
            tname: {
                required: true,
                tname: true,
                remote:
                {
                    type: 'POST',
                    url : app_url+'admin/check_user',
                    data: {'_token': $('input[name=_token]').val()},
                    async: false
                }
                
            },

        },

        messages: {
            tname: {
                required: 'Name is required',
                remote : 'The menu has already been taken.'
            },
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


$(".btn_add_menu").click(function () {
    $('#add_menu').validate();
    var validated = $('#add_menu').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Menu has been added succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#add_menu').submit(); }, 3000);
    }

});


$(document).ready(function () {
    
    $('#edit_menu').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            
            name: {
                required: true,
                email: true,
                remote:
                {
                    type: 'POST',
                    url : 'admin/check_user',
                    data: {'_token': $('input[name=_token]').val()},
                    async: false
                }
                
            },

        },

        messages: {
            name: {
                required: 'Name is required',
                remote : 'The menu has already been taken.'
            },
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


$(".btn_updatemenu").click(function () {
    $('#edit_menu').validate();
    var validated = $('#edit_menu').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Menu has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#edit_menu').submit(); }, 3000);
    }

});