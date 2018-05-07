var app_root = $('#app_url').val();

$(document).ready(function () {
 
$('#hiddCkBox').val("");
     

    $('#sectionFrm').validate({
         //onkeyup: false,
         rules: {            
            selectGrd: {
                required: true,
                             
            },
            selectDiv:{ required: true, },
            selectDept:{ required: true,},
            selectCourse:{ required: true, },
            rollno:{required: true,},
            sname: {required: true,},
            //selectSem:{required: true,},
            
        },
        messages: {
            selectGrd: {
                required: 'Select Graduation',
                 
            },

             selectDiv: {
                required: 'Select Division',
                 
            },
             selectDept:{ required: 'Select Department',},
            selectCourse:{ required: "Select Course name ", }, 
            rollno:{required: "Enter Roll name ",},
            sname:{required: "Enter Student name ",}, 
            //selectSem:{ required: "Select Semester ", },          
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




   $('#stuFrmBulk').validate({
         //onkeyup: false,
         rules: {            
            selectBulkGrd: {
                required: true,
                             
            },
            //selectBulkDiv:{ required: true, },
            selectBulkDept:{ required: true,},
            selectBulkCourse:{ required: true, },
            
            
        },
        messages: {
            selectBulkGrd: {
                required: 'Select Graduation',
                 
            },

            //  selectBulkDiv: {
            //     required: 'Select Division',
                 
            // },
            selectBulkDept:{ required: 'Select Department',},
            selectBulkCourse:{ required: "Select Course name ", }, 
                      
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

$("#btn_add_bulk").click(function () {  
  
    var validated = $('#stuFrmBulk').valid();
             
    if (validated == true)
    {  
        
          window.form.submit();
    }
     
});
    


    $('#selectGrd').on('change', function() {

            var selectGra=$(this).val();
            var classDep_options = $("#selectDept");
            classDep_options.empty();
            classDep_options.append($("<option />").val("").text("--Select Course--"));
            var cselectCourse_options = $("#selectCourse");
            cselectCourse_options.empty();
            cselectCourse_options.append($("<option />").val("").text("--Select Course--"));
            
            if(selectGra>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstudent/depdropbox',   
            data: $("#sectionFrm").serialize()+"&mode=single" , 
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
// $('#selectGrd').on('change', function() {

//  $('#selectDiv,#selectYear').val("");
//  var classDep_options = $("#selectDept");
//  classDep_options.empty();
//  classDep_options.append($("<option />").val("").text("--Select Department--"));

// });

$('#selectDept').on('change', function() { 

 var selectDep=$(this).val();
            var classCour_options = $("#selectCourse");
            var classSem_options=$('#classSem_options');
            classSem_options.empty();
            classSem_options.append($("<option />").val("").text("--Select Course--"));
            classCour_options.empty();
            classCour_options.append($("<option />").val("").text("--Select Course--"));
            if(selectDep>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstudent/courdropbox',   
            data: $("#sectionFrm").serialize()+"&mode=single" , 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() { 
            classCour_options.append($("<option />").val(this.course_id).text(this.course_name));

            });

            }

            });

            }

});



$('#selectBulkGrd').on('change', function() {

            var selectDivi=$(this).val();
            var classDep_options = $("#selectBulkDept");
            classDep_options.empty();
            classDep_options.append($("<option />").val("").text("--Select Department--"));

            var cselectCourse_options = $("#selectBulkCourse");
            cselectCourse_options.empty();
            cselectCourse_options.append($("<option />").val("").text("--Select Course--"));


            if(selectDivi>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstudent/depdropbox',   
            data: $("#stuFrmBulk").serialize()+"&mode=bulk" , 
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
$('#selectBulkGrd').on('change', function() {

 // $('#selectBulkDiv').val("");
 // var classDep_options = $("#selectBulkDept");
 // classDep_options.empty();
 // classDep_options.append($("<option />").val("").text("--Select Department--"));

});

$('#selectBulkDept').on('change', function() { 

 var selectDep=$(this).val();
            var classCour_options = $("#selectBulkCourse");
            // var classSem_options=$('#classSem_options');
            // classSem_options.empty();
            // classSem_options.append($("<option />").val("").text("--Select Course--"));
            classCour_options.empty();
            classCour_options.append($("<option />").val("").text("--Select Course--"));
            if(selectDep>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstudent/courdropbox',   
            data: $("#stuFrmBulk").serialize()+"&mode=bulk" , 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() { 
            classCour_options.append($("<option />").val(this.course_id).text(this.course_name));

            });

            }

            });

            }

});

$('#selectCourseOld').on('change', function() {  

            var selectDep=$(this).val();
            var classSem_options = $("#selectSem");
            classSem_options.empty();
            classSem_options.append($("<option />").val("").text("--Select Semester--"));
            if(selectDep>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstudent/semdropbox',   
            data: $("#sectionFrm").serialize() , 
            dataType: 'JSON', 
            success: function(data) 
            { 
            for(var i=1; i<=data; i++)
                {
                     classSem_options.append($("<option />").val(i).text(i));

                }

            }

            });

            }
});

    




});




