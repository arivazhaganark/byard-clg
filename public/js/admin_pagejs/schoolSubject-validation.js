var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#subjectFrm').validate({
        // onkeyup: false,
       // errorClass: 'error',
      //  validClass: 'valid',
         rules: { 
         selectCls:{required: true},           
            subname: {
                required: true,
                             
            },
       // subcode:{ required: true, },
            
        },
        messages: {
            selectCls:{required: 'Select class'}, 
            subname: {
                required: 'Enter subject name',
                 
            },

           // subcode:{ required: "Enter subject code ", },          
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

    

    $("#btn_add_subject").click(function () {  
    //$('#subjectFrm').validate();
    var validated = $('#subjectFrm').validate();//$('#subjectFrm').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});




});




