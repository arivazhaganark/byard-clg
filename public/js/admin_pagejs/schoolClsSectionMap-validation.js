var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#sectionFrm').validate({
         onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            selectCls: {
                required: true,
                             
            },
        selectSec:{ required: true, },
            
        },
        messages: {
            selectCls: {
                required: 'Select class',
                 
            },

            selectSec:{ required: "Select section ", },          
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

    

    $("#btn_add_section").click(function () {  
    $('#sectionFrm').validate();
    var validated = $('#sectionFrm').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});




});




