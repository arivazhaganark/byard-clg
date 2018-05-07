$(document).ready(function () { 
    $('#feedback_form').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        rules: {
            subject: {
                required: false
            },
            message: {
                required: true,
                maxlength: 250
            }
        },
        messages: {
            message: {
                required: 'Message is required'
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

    $('input, textarea').blur(function() {
        var value = $.trim( $(this).val() );
        $(this).val( value );
    });
    
    $("#message").each(function(){    
        $(this).bind("keyup",function(event) {             
            var msg_character = $("#message").val().length;              
            var result = 250 - parseInt(msg_character) ;                 
            if(result <= 0){ 
                $('#message_count').attr("style","padding-top:2px;float:right;font-size: 11px;font-style:normal;color: #ff0000;");
                $('#message_count').html("You have reached your maximum limit."); 
                $('#message_count').fadeIn('slow');
                $('#message_count').css("margin-top","3px"); 
            } else if(result == 1){
                $('#message_count').attr("style","padding-top:2px;float:right;font-size: 12px;font-weight:bold;color: blue;");
                $('#message_count').html(result+' '+"character remaining."); 
            } else if(result > 1){
                $('#message_count').attr("style","padding-top:2px;float:right;font-size: 12px;font-weight:bold;color: blue;");
                $('#message_count').html(result+' '+"character remaining."); 
            }
        });
    });
});


$("#feedBack").click(function () {
    $('#feedback_form').validate();
    var validated = $('#feedback_form').valid();
    if (validated == true)
    {
        window.feedback_form.submit();
    }
});
