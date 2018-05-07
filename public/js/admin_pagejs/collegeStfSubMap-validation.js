var app_root = $('#app_url').val();


 

$(document).ready(function () {

    $('#hidcls,#hidsub').val();
    $('#sectName-error,#subName-error').html("");

 
        $('#subjectFrm').validate({
        rules: { 

            selectstaffCls: {
            required: true,

            },

            selectCour:{  required: true,},


        },
        messages: {

        selectstaffCls: {
        required: 'Select staff name',

        },
        selectCour: {
        required: 'Select course',

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


    // var valuesSemesterArr = new Array();
    // $.each($("input[name='optionClass[]']:checked"), function() {
    // valuesSemesterArr.push($(this).val());
    // });

    var valuesSemesterArr = new Array();
    $.each($("input[name='optionSemester[]']:checked"), function() {
    valuesSemesterArr.push($(this).val());
    }); 
    
    if(valuesSemesterArr.length==0)
    {  
        $('#sectName-error').html("Select subject");
    }
    else
    {
      $('#sectName-error').html("");
    }
//alert(valuesSemesterArr)
    //  if(valuesSemesterArr.length==0)
    // {
    //     $('#subName-error').html("Select subject");
       
    // }
    // else
    // {
    //     $('#subName-error').html("");
    // }



    $('#hidcls').val(valuesSemesterArr);
    if (validated == true && valuesSemesterArr.length>0)
    {
  
         $('#hidcls').val(valuesSemesterArr);
         // $('#hidsub').val(valuesSemesterArr);

      return true; 

    } 
    else
    {
        return false; 
    }
    
    });

    $('#selectstaffCls').on('change', function() {
        $('#selectCour').val("");
        $('#sectName').html("");
    });

      $('#selectCour').on('change', function() {
 
            $('#sectName-error,#subName-error').html("");
            var selectStaff=$(this).val();
           $('#sectName,#subName').html("");
            if(selectStaff>0)
            {

            $.ajax({  
            type: "POST",
            url : app_url+'/admin/clgstaffsubmapp/depdropboxSec',   
            data: $("#subjectFrm").serialize()+"&mode=single" , 
            dataType: 'JSON', 
            success: function(data) 
            { 
 
                var inputClassVal="";
                var inputSectionVal="";
                $.each(data, function() {
                     var semIds=this.semester_id;
                 inputClassVal+='<label style="color:green;font-size:15px">Semester '+this.semester_id+'</label>';
                 var splitSubject=this.subNames.split(',');
 
                 for (var i = 0; i < splitSubject.length; i++) {
                    var splitSubId=splitSubject[i].split('@@@');
                    var subId=splitSubId[splitSubId.length-1];
                    var SubName=splitSubId[0];
                    inputClassVal+='<label style="margin-left:10px;color:blue;font-size:15px"><input id="optionSemester" name="optionSemester[]" value="'+this.semester_id+'@#@'+subId+'" type="checkbox">'+SubName+'</label>'
                 }
                  
                 });
                $('#sectName').html(inputClassVal);
            }

            });

            }

 });

});




