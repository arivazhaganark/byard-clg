var app_root = $('#app_url').val();

$(document).ready(function () {
  
    // $('input, textarea').blur(function() {
    //    var value = $.trim( $(this).val() );
    //    $(this).val( value );
    // });

    $('#sclFrm').validate({
       // onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            sname: {
                required: true,
                             
            },

             
            
        },
        messages: {
            sname: {
                required: 'School name is required',
                 
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

    // $('#sclUpdateFrm').validate({
    //     onkeyup: false,
    //     errorClass: 'error',
    //     validClass: 'valid',
    //      rules: {            
    //         sname: {
    //             required: true,
                             
    //         },

          
            
    //     },
    //     messages: {
    //         iname: {
    //             required: 'School name is required',
                 
    //         },
            


            
             
    //     },        
    //     highlight: function (element) {
    //         $(element).closest('div').addClass("f_error");
    //     },
    //     unhighlight: function (element) {
    //         $(element).closest('div').removeClass("f_error");
    //     },
    //     errorPlacement: function (error, element) {
    //         $(element).closest('div').append(error);
    //     }
            
    // });



$(".btn_add_sclool").click(function () {
  
    $('#sclFrm').validate();
    
    var validated = $('#sclUpdateFrm').valid();
    if (validated == true)
    {
        window.form.submit();
    }


});



// $(".btn_add_customer").click(function () {
//     alert(5)
//     $('#sclFrm').validate();
     
//     var validated = $('#sclFrm').valid();
//     if (validated == true)
//     {
//         window.form.submit();
//     }
// });

    
});



