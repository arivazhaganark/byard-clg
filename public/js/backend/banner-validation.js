var app_url = $('#admin_access_url').val();
$(document).ready(function () {
    $("#add_banner").validate({
        ignore: [],
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            name: {
                required: true
            },
            hid_file_name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Name cannot be blank."
            },
            hid_file_name: {
                required: "Photo cannot be blank."
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

    $("#edit_banner").validate({
        ignore: [],
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            name: {
                required: true
            },
            hid_file_name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Name cannot be blank."
            },
            hid_file_name: {
                required: "Photo cannot be blank."
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

$("#add_submit_btn").click(function () {
    $('#add_banner').validate();
});

$("#update_submit_btn").click(function () {
    $('#edit_banner').validate();
});


function split(val) {
    return val.split(/,\s*/);
}
function extractLast(term) {
    return split(term).pop();
}

function slug_creator(str) {
    var $slug = '';
    var trimmed = $.trim(str);
    $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
    replace(/-+/g, '-').
    replace(/^-|-$/g, '');
    return $slug.toLowerCase();
}
