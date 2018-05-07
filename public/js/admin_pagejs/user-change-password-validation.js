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
                minlength: 6,
                remote:
                {
                    type: 'POST',
                    url: app_url + "/user/change_password/new_password_check",
                    type: "post",
                    data: {
                      '_token': function() {
                        return $('input[name=_token]').val();
                      },
                      new_password: function() {
                        return $( "#new_password" ).val();
                      },
                      old_password: function() {
                        return $( "#old_password" ).val();
                      }
                    },
                    async: false
                }
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
                minlength: "Please enter at least 6 characters.",
                remote: "New password should not be equal to old password."
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
         window.password_form.submit();
    }

});

