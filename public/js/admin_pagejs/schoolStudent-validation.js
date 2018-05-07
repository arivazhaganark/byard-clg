var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#studentFrm').validate({
         onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            selectCls: {
                required: true,
                             
            },
            selectClsSec:{
                required: true,
            },

            rollno:{ required: true,
            // remote:
            //     {
            //         type: 'POST',
            //         url : app_url+'/admin/schstudent/checkroll',
            //         data: $("#studentFrm").serialize(),
            //         async: false
            //     }   

             },
        sname:{ required: true, },
        ayear:{ required: true, },
            
        },
        
        messages: {
            selectCls: {
                required: 'select class',
                 
            },
            selectClsSec:{required: 'select section', },
            rollno:{ required: "Enter roll no "}, 
             // rollno:{ required: "Enter roll no ", remote : 'This email-id is already exists.'}, 

            sname:{ required: "Enter name ", }, 
             ayear:{ required: "Enter year ", },          
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

    

    $("#btn_Student").click(function () {  
    $('#studentFrm').validate();
    var validated = $('#studentFrm').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});


$('#stuFrmBulk').validate({
         onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {            
            selectbulkCls: {
                required: true,
                             
            },
              selectbulkClsSec:{
                  required: true,
             },

            
        
        // ayear:{ required: true, },
            
        },
        
        messages: {
            selectbulkCls: {
                required: 'select class',
                 
            },
              selectbulkClsSec:{required: 'select section', },
            // rollno:{ required: "Enter roll no "}, 
            //  // rollno:{ required: "Enter roll no ", remote : 'This email-id is already exists.'}, 

            // sname:{ required: "Enter name ", }, 
            //  ayear:{ required: "Enter year ", },          
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




    $("#btn_bulk_Student").click(function () {  
     
    //alert($('#stuFrmBulk').validate());
    var validated = $('#stuFrmBulk').valid();
     
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
 
     
});

    

$('#selectCls').on('change', function() {
   var selectClsVal=$(this).val();
   var classSec_options = $("#selectClsSec");
   classSec_options.empty();
   classSec_options.append($("<option />").val("").text("--Select section--"));
if(selectClsVal>0)
{

     $.ajax({  
            type: "POST",
            url : app_url+'/admin/schstudent/depdropbox',   
            data: $("#studentFrm").serialize()+"&mode=single", 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() {
                classSec_options.append($("<option />").val(this.sec_id).text(this.section_name));
                
                });
               
            }

        });

}


})

$('#selectbulkCls').on('change', function() {
   var selectClsVal=$(this).val();
    
   var classSec_options = $("#selectbulkClsSec");
   classSec_options.empty();
   classSec_options.append($("<option />").val("").text("--Select section--"));
if(selectClsVal>0)
{

     $.ajax({  
            type: "POST",
            url : app_url+'/admin/schstudent/depdropbox',   
            data: $("#stuFrmBulk").serialize()+"&mode=blk", 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() {
                classSec_options.append($("<option />").val(this.sec_id).text(this.section_name));
                
                });
               
            }

        });

}


})




});




