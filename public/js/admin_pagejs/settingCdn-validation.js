var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
$('#err').html("");

$('#frm_new').validate({
       // onkeyup: false,
       // errorClass: 'error',
        //validClass: 'valid',
        rules: {
            selectId: {
                required: true
            },
            c_key: {
                required: true
            },
            //key_email:{ required: true,email:true,}
        },
        messages: {
            selectId: {
                required: 'Select institution'
            },
            c_key: {
                required: 'Enter customer lincense key'
            },
           //  key_email:{
           // required: 'Email-id is required',email: "Invalid email id!"
           //      },

        },
        highlight: function (element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {            
           $(element).closest('div').append(error);
        },
        submitHandler: function () {
            $('#err').html("");

            $.ajax({
                url: app_url + "/Api/dataSynchron",
                type: "POST",
                data: JSON.stringify({
                   "operation": "Synchrons",
                   "user": {
                      "cusId": $('#c_key').val(),
                      "institeType":$('#selectId').val(),
                      
                    }
                }),
                dataType: "JSON",
                  success: function (response) {
                    
                    if(response.status==1 && response.result=='success')
                        window.location.href = app_url + "/admin/setting";
                    else {
                       $('#err').html(response.result);
                    }
                }
            });

            
            
        }
    });
     

    
     
});







    

    






 




