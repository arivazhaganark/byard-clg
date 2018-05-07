var app_root = $('#app_url').val();
$(document).ready(function () {
     $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
       });
    $('#add_test').validate({
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
                    url : app_url+'admin/check_testuser',
                    data: {'_token': $('input[name=_token]').val()},
                    async: false
                }
                
            },
             add_desc: {
                required: true
                
            }

        },

        messages: {
            tname: {
                required: 'Name is required',
                remote : 'The menu has already been taken.'
            },
             add_desc: {
                required: 'Description is required'
                
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


$(".btn_add_test").click(function () {
    $('#add_test').validate();
    var validated = $('#add_test').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Menu has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#add_test').submit(); }, 3000);
    }

});


$(document).ready(function () {
     $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
       });
    $('#edit_test').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            
             
            ename: {
                required: true,
                ename: true,
                remote:
                {
                    type: 'POST',
                    url : app_url+'admin/check_testuser',
                    data: {'_token': $('input[name=_token]').val()},
                    async: false
                }
                
            },
             edesc: {
                required: true,
                
            }

        },

        messages: {
            ename: {
                required: 'Name is required',
                remote : 'The menu has already been taken.'
            },
             edesc: {
                required: 'Description is required',
                
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


$(".btn_updatetest").click(function () {
    $('#edit_test').validate();
    var validated = $('#edit_test').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Menu has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#edit_test').submit(); }, 3000);
    }

});