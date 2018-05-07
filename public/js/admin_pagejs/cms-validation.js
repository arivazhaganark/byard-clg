var app_root = $('#app_url').val();
$(document).ready(function () {
    $('#add_cms').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            title: {
                required: true
            },
            editor1: {
                required: true
            },
            position: {
                required: true
            },
            language_id: {
                required: true
            },
            menu_id: {
                required: true
            }
        },
        messages: {
            title: {
                required: 'Title is required'
            },
            editor1: {
                required: 'Content is required'
            },
            position: {
                required: 'Position is required'
            },
            language_id: {
                required: "Please choose the language"
            },
            menu_id: {
                required: "Please choose the CMS Menu"
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

    $('#edit_cms').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            title: {
                required: true
            },
            editor1: {
                required: true
            },
            position: {
                required: true
            },
            language_id: {
                required: true
            },
            menu_id: {
                required: true
            }
        },
        messages: {
            title: {
                required: 'Title is required'
            },
            editor1: {
                required: 'Content is required'
            },
            position: {
                required: 'Position is required'
            },
            language_id: {
                required: "Please choose the language"
            },
            menu_id: {
                required: "Please choose the CMS Menu"
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

$("#btn_add_cms").click(function () {
    $('#add_cms').validate();
});

$("#btn_update").click(function () {
    $('#edit_cms').validate();
});


function div_display_fn(val)
{
    if (val == 'content') {
        $('#contentdiv').show();
        $('#linkdiv').hide();
    } else {
        $('#contentdiv').hide();
        $('#linkdiv').show();
    }
}

function div_display_fn1(val)
{
    if (val == 'content') {
        $('#contentdiv1').show();
        $('#linkdiv1').hide();
    } else {
        $('#contentdiv1').hide();
        $('#linkdiv1').show();
    }
}