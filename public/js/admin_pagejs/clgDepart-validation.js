var app_root = $('#app_url').val();

$(document).ready(function() {

    $('#hiddCkBox').val("");

    $('#sectionFrm').validate({
        onkeyup: false,
        // errorClass: 'error',
        //  validClass: 'valid',
        rules: {
            selectGrd: {
                required: true,
            },
            // selectDiv:{ required: true, },
            dname: {
                required: true,
            },
        },
        messages: {
            selectGrd: {
                required: 'Select Graduation',
            },
            /*selectDiv: {
                required: 'select Division',
                 
            },*/
            dname: {
                required: "Enter department name ",
            },
        },
        highlight: function(element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function(element) {
            $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function(error, element) {
            $(element).closest('div').append(error);
        }
    });

    $("#btn_add_dep").click(function() {
        // $('#sectionFrm').validate();
        var validated = $('#sectionFrm').valid();

        if (validated == true) {
            window.form.submit();
        }
    });

});