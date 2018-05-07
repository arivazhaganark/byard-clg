var app_root = $('#app_url').val();

$(document).ready(function () {
    

$('#sUser').on('change', function() {
   var selectUsrVal=$(this).val();
   $('#errMsg').html("");
    if(selectUsrVal>0)
    {
     
         $.ajax({  
                type: "POST",
                url : app_url+'/admin/userClgFile/userPermissionAjax',   
                data: $("#userPerFrm").serialize()+"&mode=single", 
                dataType: 'JSON', 
                success: function(data) 
                { 
                    
                    if(data.status==1)
                    {
                        var tblHeader='<thead><tr class="bg-gray color-palette"><th class="head_text">#</th><th class="head_text">Page Name</th><th class="head_text">Add</th><th class="head_text">Edit</th><th class="head_text">View</th><th class="head_text">Delete</th></thead>';
                        var innTr="";
                        //alert(data.output.length)
                       
                        if(data.output.length>0)
                        {
                            for(var i=0;i<data.output.length;i++)
                            {
                                var addEveNo=i%2;
                                var rowClass='odd'
                                var AddId='add_'+data.output[i].m_c_id;
                                var DelId='del_'+data.output[i].m_c_id;
                                var EditId='edit_'+data.output[i].m_c_id;
                                var ViewId='view_'+data.output[i].m_c_id;
                                var AddBox="";
                                var EditBox="";
                                var DelBox="";
                                var ViewBox="";

                                if(addEveNo)
                                {
                                  rowClass='even';
                                }
                                if(data.output[i].file_add==1)
                                {
                                   AddBox="checked";
                                }
                                if(data.output[i].file_edit==1)
                                {
                                    EditBox="checked";
                                }
                                if(data.output[i].file_delete==1)
                                {
                                    DelBox="checked";
                                }
                                if(data.output[i].file_view==1)
                                {
                                    ViewBox="checked";
                                }



                                innTr+="<tr class="+rowClass+" role='row'><td>"+(i+1)+"</td><td>"+data.output[i].menu_name+"</td><td><input id='"+AddId+"' class='chkall' name='"+AddId+"' "+AddBox+" value='"+data.output[i].m_c_id+"' type='checkbox'></td><td><input id='"+EditId+"' class='chkall' "+EditBox+" name='"+EditId+"' value='"+data.output[i].m_c_id+"' type='checkbox'></td><td><input id='"+ViewId+"' class='chkall' "+ViewBox+" name='"+ViewId+"' value='"+data.output[i].m_c_id+"' type='checkbox'></td><td><input id='"+DelId+"' class='chkall' "+DelBox+" name='"+DelId+"' value='"+data.output[i].m_c_id+"' type='checkbox'></td><tr>"
                            }
                        }
                         $('#userPermission').html(tblHeader+innTr);

                         $('.chkall').on('change', function() {
                       var attrId=$(this).attr('id');
                       var splitArr=attrId.split('_');
                       var perMode=splitArr[0];
                       var rowId=$(this).val();
                       var addChk=0;
                       var editChk=0;
                       var delChk=0;
                       var viewChk=0;
                       if($('#add_'+rowId).prop("checked"))
                       {
                          addChk=1;
                       }
                       if($('#edit_'+rowId).prop("checked"))
                       {
                          editChk=1;
                       }
                       if($('#del_'+rowId).prop("checked"))
                       {
                          delChk=1;
                       }
                       if($('#view_'+rowId).prop("checked"))
                       {
                          viewChk=1;
                       }

                       $.ajax({  
                        type: "POST",
                        url : app_url+'/admin/userClgFile/userPermissionCurdAjax',   
                        data: $("#userPerFrm").serialize()+"&rowId="+rowId+"&ad="+addChk+"&ed="+editChk+"&del="+delChk+"&view="+viewChk, 
                        dataType: 'HTML', 
                        success: function(data) 
                        { 

                          if(data=='Updated successfully')
                          {
                             $('#errMsg').html("<font color='green'>"+data+"</font>")
                          }
                          else
                          {
                             $('#errMsg').html("<font color='red'>"+data+"</font>");
                          }

                          setTimeout(function(){$('#errMsg').html("") }, 2000);
                         

                        }

                        });


    });

                    }
                    else
                    {
                        alert(data.errMsg);
                    }

                   
                   
                }

            });

    }
    else
    {
      $('#userPermission').html("");
    }


})








});




