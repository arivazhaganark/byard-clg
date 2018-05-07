var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#cusKeyFrm').validate({
         onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            selectKey: {
                required: true,
                             
            },
Licence:{ required: true, },
            
            
        },
        messages: {
            selectKey: {
                required: 'selectKey',
                 
            },

            Licence:{ required: "Check linece type", },
            


            
             
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

    // $('#cusUpdateFrm').validate({
    //     onkeyup: false,
    //     errorClass: 'error',
    //     validClass: 'valid',
    //      rules: {            
    //         iname: {
    //             required: true,
                             
    //         },

    //         emailid:{

    //            required: true,
    //            email:true,
    //            remote:
    //             {
    //                 type: 'POST',
    //                 url : app_url+'/admin/customer/check_mail',
    //                 data: {'id': $('#hidid').val(),'_token': $('input[name=_token]').val()},
    //                 async: false
    //             }

    //         }
            
    //     },
    //     messages: {
    //         iname: {
    //             required: 'Industrial is required',
                 
    //         },
    //         emailid:{
    //        required: 'Email-id is required',
    //        email: "Invalid email id!",
    //         remote : 'This email-id is already exists.'
    //             },


            
             
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

    $("#btn_add_customer").click(function () {  
    $('#cusKeyFrm').validate();
    var validated = $('#cusKeyFrm').valid();
                var favorite = [];
                var SuccessVal=[];
              $('.errorLicence').hide();
            $.each($("input[name='Licence']:checked"), function(){ 

               var getTxtMaxVal=$('#type_'+$(this).val()).val();       
               if(getTxtMaxVal>0)
              {
                 $('#type_'+$(this).val()+'-error').hide();
                  SuccessVal.push($(this).val());
              }
              else
              {  
                $('#type_'+$(this).val()+'-error').show();

                 favorite.push($(this).val());
              }        

                
            });

              
    if (validated == true && favorite.length==0)
    {  
        $('#hiddCkBox').val(SuccessVal.join(','))
          window.form.submit();
    }
    else
    {
         $('#hiddCkBox').val("");
        return false;
    }
});

$(".editCls").click(function () { 

  var liVal=$(this).attr('lang') ;

  $('#type_1-error,#type_2-error,#type_3-error,#type_4-error').hide();

  var getTxtMaxVal=$('#type_'+liVal).val();       
               if(getTxtMaxVal>0)
              {

  if(liVal>0)
  {

    $.ajax({  
        type: "POST",
        url : app_url+'/admin/keycustomer/update',   
        data: $("#cusKeyEditFrm").serialize()+"&licenceVal="+liVal, 
        dataType: 'HTML', 
        success: function(data) 
        {  
          alert(data)
          location.reload();
        }

        });


  }
   }
   else
   {

     $('#type_'+liVal+'-error').show();
     
   }

//return false;

});


});




// window.onload = function () {
// //Check File API support
//     if (window.File && window.FileList && window.FileReader)
//     {
//         $('#image').on("change", function (event) {
//             var files = event.target.files; //FileList object
//             var output = document.getElementById("result");
//             for (var i = 0; i < files.length; i++)
//             {
//                 var file = files[i];
//                 //Only pics
//                 // if(!file.type.match('image'))
//                 if (file.type.match('image.*')) {
//                     if (this.files[0].size < 2097152) {
//                         // continue;
//                         var picReader = new FileReader();
//                         picReader.addEventListener("load", function (event) {
//                             var picFile = event.target;
//                             var div = document.createElement("div");
//                             div.innerHTML = "<img class='thumb' src='" + picFile.result + "'" +
//                                     "title='preview image' width='100' />";
//                             output.insertBefore(div, null);
//                         });
//                         //Read the image
//                         $('#clear, #result').show();
//                         picReader.readAsDataURL(file);
//                     } else {
//                         alert("Image Size is too big. Minimum size is 2MB.");
//                         $(this).val("");
//                     }
//                 } else {
//                     alert("You can only upload image file.");
//                     $(this).val("");
//                 }
//             }
//         });

//     } else
//     {
//         console.log("Your browser does not support File API");
//     }
// }

// $('#offer_image').on("click", function () {
//     $('.thumb').parent().remove();
//     $('result').hide();
//     $(this).val("");
// });

// $('#clear').on("click", function () {
//     $('.thumb').parent().remove();
//     $('#result').hide();
//     $('#offer_image').val("");
//     $(this).hide();
// });