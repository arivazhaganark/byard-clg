var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#sectionFrm').validate({
         onkeyup: false,
       // errorClass: 'error',
      //  validClass: 'valid',
         rules: {            
            selectGrd: {
                required: true,
                             
            },
            // selectDiv:{ required: true, },

            selectDept:{ required: true,},
            selectYear:{ required: true,},
        cname:{ required: true, },
            
        },
        messages: {
            selectGrd: {
                required: 'Select Graduation',
                 
            },

            //  selectDiv: {
            //     required: 'Select Division',
                 
            // },
            selectDept:{ required: 'Select Department',},
            selectYear:{ required: 'Select Year',},

            cname:{ required: "Enter department name ", },          
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

    

    $("#btn_add_dep").click(function () {  
   // $('#sectionFrm').validate();
    var validated = $('#sectionFrm').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});


    $('#selectDiv').on('change', function() {

            var selectDivi=$(this).val();
            var classDep_options = $("#selectDept");
            classDep_options.empty();
            classDep_options.append($("<option />").val("").text("--Select Department--"));
            if(selectDivi>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgcourse/depdropbox',   
            data: $("#sectionFrm").serialize() , 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() {
            classDep_options.append($("<option />").val(this.dep_id).text(this.depart_name));

            });

            }

            });

            }


 })
$('#selectGrd').on('change', function() {  

 
     var selectGra=$(this).val();

    var classDep_options = $("#selectDept");
    classDep_options.empty();
    classDep_options.append($("<option />").val("").text("--Select Department--"));
    if(selectGra>0)
    {
 

        $.ajax({  
        type: "POST",
        url : app_url+'/admin/clgcourse/depdropbox',   
        data: $("#sectionFrm").serialize() , 
        dataType: 'JSON', 
        success: function(data) 
        { 
        $.each(data, function() {
        classDep_options.append($("<option />").val(this.dep_id).text(this.depart_name));

        });

        }

        });


    }



 // $('#selectDiv,#selectYear').val("");
 // var classDep_options = $("#selectDept");
 // classDep_options.empty();
 // classDep_options.append($("<option />").val("").text("--Select Department--"));

});
    




});




