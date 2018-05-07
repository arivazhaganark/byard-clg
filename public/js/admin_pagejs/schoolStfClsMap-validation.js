var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#subjectFrm').validate({
        // onkeyup: false,
       // errorClass: 'error',
      //  validClass: 'valid',
         rules: { 
         selectMulCls:{required: true},           
            selectstaffCls: {
                required: true,
                             
            },
       // subcode:{ required: true, },
            
        },
        messages: {
            selectMulCls:{required: 'Select class'}, 
            selectstaffCls: {
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

     var typVal=$('#selectMulCls').val();
     chkclassValidation=0;

     if(typVal==null)
        {
            $('.error1').show();
        }
        else
        {
           $('.error1').hide();
           chkclassValidation=1;
           
        }
             
    if (validated == true && chkclassValidation==1)
    {  
        
          window.form.submit();
    }
     
});



$('.multiselect-ui').multiselect({
        includeSelectAllOption: true
    });


});




