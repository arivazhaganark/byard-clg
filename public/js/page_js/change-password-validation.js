var app_root = $('#app_url').val();
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
                    url: app_url + "/user/change_password/password_check",
                    data: {'company_id': '', '_token': $('input[name=_token]').val()},
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
                      new_password: function() {
                        return $( "#password" ).val();
                      },
                      old_password: function() {
                        return $( "#old_password" ).val();
                      },
                      '_token': function() {
                        return $('input[name=_token]').val();
                      }
                    },
                    async: false
                }
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: 'Old password is required',
                remote: 'Old password Mismatch'
            },
            new_password: {
                required: 'New password is required',
                remote: 'New password should not be equal to old password'
            },
            confirm_password: {
                required: 'Confirm password is required',
                equalTo: 'New password & confirm password should be same'
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

$("#cPassword").click(function () {
    $('#password_form').validate();
    var validated = $('#password_form').valid();
    if (validated == true)
    {
       $.ajax({
            url: app_url + "/user/change_password/store", 
            type: "POST",             
            data: $("#password_form").serialize(),
            success: function(data) {
                alert('Password has been changed successfully.');
                window.location = app_url + '/user/logout';
            }
        });
        return false;
    }
});