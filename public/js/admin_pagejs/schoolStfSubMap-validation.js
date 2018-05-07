var app_root = $('#app_url').val();


 

$(document).ready(function () {

    $('#hidcls,#hidsub').val();
    $('#sectName-error,#subName-error').html("");

 
        $('#subjectFrm').validate({
        rules: { 

            selectstaffCls: {
            required: true,

            },

            selectClass:{  required: true,},


        },
        messages: {

        selectstaffCls: {
        required: 'Select staff name',

        },
        selectClass: {
        required: 'Select class',

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

    $('#btn_map_subject').click(function () { 
    $('#subjectFrm').validate();
    var validated = $('#subjectFrm').valid();


    var valuesSectionArr = new Array();
    $.each($("input[name='optionClass[]']:checked"), function() {
    valuesSectionArr.push($(this).val());
    });

    var valuesSectionArr = new Array();
    $.each($("input[name='optionSection[]']:checked"), function() {
    valuesSectionArr.push($(this).val());
    }); 
    
    if(valuesSectionArr.length==0)
    {  
        $('#sectName-error').html("Select subject");
    }
    else
    {
      $('#sectName-error').html("");
    }

     if(valuesSectionArr.length==0)
    {
        $('#subName-error').html("Select subject");
       
    }
    else
    {
        $('#subName-error').html("");
    }



    $('#hidcls').val(valuesSectionArr);
    if (validated == true && valuesSectionArr.length>0 && valuesSectionArr.length>0)
    {
  
         $('#hidcls').val(valuesSectionArr);
          $('#hidsub').val(valuesSectionArr);

      return true; 

    } 
    else
    {
        return false; 
    }
    
    });



    $('#selectstaffCls').on('change', function() {

           $('#sectName-error,#subName-error').html("");
            var selectStaff=$(this).val();
            $('#sectName,#subName').html("");
            var class_options = $("#selectClass");
            class_options.empty();
            class_options.append($("<option />").val("").text("--Select Class--"));
            if(selectStaff>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/schstaffsubmapp/depdropbox',   
            data: $("#subjectFrm").serialize()+"&mode=single" , 
            dataType: 'JSON', 
            success: function(data) 
            { 
            $.each(data, function() {
            class_options.append($("<option />").val(this.sch_cls_id).text(this.sch_class));

            });

            }

            });

            }


            //


 })


     $('#selectClass').on('change', function() {
 
            $('#sectName-error,#subName-error').html("");
            var selectStaff=$(this).val();
           $('#sectName,#subName').html("");
            if(selectStaff>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/schstaffsubmapp/depdropboxSec',   
            data: $("#subjectFrm").serialize()+"&mode=single" , 
            dataType: 'JSON', 
            success: function(data) 
            { 

                var inputClassVal="";
                var inputSectionVal="";
                $.each(data.className, function() {
                    var sectionIds=this.sec_id;
                inputClassVal+='<label style="color:green;font-size:15px">Section '+this.section_name+'</label>';

                $.each(data.subName, function() {
                inputClassVal+='<label style="margin-left:10px;color:blue;font-size:15px"><input id="optionSection" name="optionSection[]" value="'+sectionIds+'@#@'+this.sub_id+'" type="checkbox">'+this.sub_name+'</label>';
                });


                });

                // $.each(data.subName, function() {
                // inputSectionVal+='<label><input id="optionSection" name="optionSection[]" value="'+this.sub_id+'" type="checkbox">'+this.sub_name+'</label>';
                // });


                $('#sectName').html(inputClassVal);
                //$('#subName').html(inputSectionVal);
                


            }

            });

            }


             


 });


 


 


});




