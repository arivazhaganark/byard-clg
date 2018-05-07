var app_root = $('#app_url').val();

$(document).ready(function () {
 
 
     

    $('#staffFrm').validate({
         onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            scode: {
                required: true,
                             
            },
        sname:{ required: true, },
            
        },
        messages: {
            scode: {
                required: 'Enter staff code',
                 
            },

            sname:{ required: "Enter staff name ", },          
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

    

    $("#btn_staff").click(function () {  
    $('#staffFrm').validate();
    var validated = $('#staffFrm').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});




});




