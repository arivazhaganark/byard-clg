var app_root = $('#app_url').val();

$(document).ready(function () {
 

    // $('input, textarea').blur(function() {
    //    var value = $.trim( $(this).val() );
    //    $(this).val( value );
    // });

    $('#cusFrm').validate({
       // onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            iname: {
                required: true,
                             
            },

            emailid:{

               required: true,
               email:true,

            }
            
        },
        messages: {
            iname: {
                required: 'Industrial is required',
                 
            },
            emailid:{
           required: 'Email-id is required',email: "Invalid email id!"
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

    $('#cusUpdateFrm').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            iname: {
                required: true,
                             
            },

            emailid:{

               required: true,
               email:true,
               remote:
                {
                    type: 'POST',
                    url : app_url+'/admin/customer/check_mail',
                    data: {'id': $('#hidid').val(),'_token': $('input[name=_token]').val()},
                    async: false
                }

            }
            
        },
        messages: {
            iname: {
                required: 'Industrial is required',
                 
            },
            emailid:{
           required: 'Email-id is required',
           email: "Invalid email id!",
            remote : 'This email-id is already exists.'
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

    // $('#testi').validate({
    //     onkeyup: false,
    //     errorClass: 'error',
    //     validClass: 'valid',
    //      rules: {            
    //         name: {
    //             required: true,
    //             remote:
    //             {
    //                 type: 'POST',
    //                 url : app_url+'/admin/check_name',
    //                 data: {'id': $('#hidid').val(),'_token': $('input[name=_token]').val()},
    //                 async: false
    //             }                
    //         },
    //         description: {
    //             required: true                
    //         }
    //     },
    //     messages: {
    //         name: {
    //             required: 'Name is required',
    //             remote : 'This name is already exists.'
    //         },
    //         description: {
    //             required: 'Description is required'                
    //         }
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
});


$(".btn_add_customer").click(function () {
    $('#cusFrm').validate();
    var validated = $('#cusFrm').valid();
    if (validated == true)
    {
        window.form.submit();
    }
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