var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
$('#err').html("");

$('#frm_new').validate({
       // onkeyup: false,
       // errorClass: 'error',
        //validClass: 'valid',
        rules: {
            ckey: {
                required: true
            },
            pakkey: {
                required: true
            },
            //key_email:{ required: true,email:true,}
        },
        messages: {
            ckey: {
                required: 'Enter customer key'
            },
            pakkey: {
                required: 'Enter package key'
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
        var userCode =$('#hiddcode').val();
           
          $.ajax({
          url: app_url + "/Api/packUsedSynchron",
          type: "POST",
          data: JSON.stringify({
          "operation": "UsedSynchrons",
          "user": {
          "staffcode":$('#hiddcode').val(),
          "cusId":$('#ckey').val(),
          "pakageId":$('#pakkey').val(),
          }
          }),
          dataType: "JSON",
          success: function (response) {
          if(response.status==1 && response.result=='success')
          {
            $('#err').html("<font color='green'>Package used successfully</font>");
            $('#ckey,#pakkey').val()

          }    
          else {
            $('#err').html("<font color='red'>"+response.result+"</font>");
          }
          }
          });
            
        }
    });
     

    
     
});







    

    






 




