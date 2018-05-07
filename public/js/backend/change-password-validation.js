var app_root = $('#app_url').val();
$('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
   });

$(document).ready(function () {
    $('#password_form').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            old_password: {
                required: true,
                remote:
                {
                    type: 'POST',
                    url: app_url + "/admin/change_password/password_check",
                    data: {'_token': $('input[name=_token]').val()},
                    async: false
                }
            },
            new_password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            old_password: {
                required: 'Old Password is required.',
                remote: 'Old Password mismatch.'
            },
            new_password: {
                required: "Password cannot be blank.",
                minlength: "Please enter at least 6 characters."
            },
            confirm_password: {
                required: "Confirm password cannot be blank.",
                equalTo: "Password mismatch."
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
    $('#password_form').validate();
    var validated = $('#password_form').valid();
    if (validated == true)
    {
        var placementFrom = 'top';
        var placementAlign = 'right';
        var animateEnter = 'animated rotateInUpRight';
        var animateExit = 'animated rotateOutUpRight';
        var colorName = 'bg-black';
        showNotification(colorName, 'Password has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
        setTimeout(function() { $('#password_form').submit(); }, 3000);
    }

});

