var app_root = $('#app_url').val();

 function show_video(val) {
    $('#myvid').modal('show');
    $("video").attr("src", app_url + '/uploads/file_manager/' + val);
 }

 
function ajaxfmStudent(clsId=null,SecId=null,subId=null,staffId=null,folderMode=null,rowId=null) { 

 //alert(clsId+'='+SecId+'='+subId+'='+staffId+'='+folderMode+'='+rowId);
     var resultIds='';
     var folderNameSrch='';
     var searchChar='';
     $('#search_txt').val("");
     //alert(rowId);
     if(rowId>0)
     {

       var slinkPathArr = new Array();
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[2];
      slinkPathArr.push(valSplitId);
      }  
       if(slinkPathArr.length>0)
       {

           resultIds=slinkPathArr.join();
       }

 
   

     }
     else
     {
         
     }

       
       $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagersclstudent/ajaxfm",
           //data: {'_token': $('input[name=_token]').val(), 'file_id': rowId, 'search_txt': search_txt,'flname':folderName,'pathIds':resultIds},
           data: {'_token': $('input[name=_token]').val(),'searchTxt':searchChar, 'cls_id': clsId,'section_id':SecId,'subId':subId ,'staffId': staffId,'flname':folderNameSrch,'pathIds':resultIds,'folderMode':folderMode,'fileRowId':rowId},
             success: function (objResponse) {

             
                 var response = $.trim(objResponse);
                 $("#ajax-fm").html(response);




                 }          
                


               
           
       });
     }

     function ajaxSearfm(searchChar=null)
     {
        
       var fileid=$('#search').attr('lang') ;
       var resultIdsSrch='';
       var clsId='';
       var SecId='';
       var subId='';
       var staffId=0;
       var resultIds='';
       var folderMode='';
       var rowId='';
        
     if(fileid!='')
     {
 
        if(brray.length==1) 
        {
            var rowId=brray[0].split('_')[2];
            rowIdSrch=0;
            //semIdSrch=valSplitId;
            folderMode='staff';
            resultIds=rowId;
            folderNameSrch='';
            subId=rowId;
        }
        else if(brray.length==2)
        {
 
          var rowId=brray[0].split('_')[2];
          staffId=brray[1].split('_')[2];
          
            resultIds='';
            folderMode='staffFile';
            subId=rowId;
             var slinkPathArr = new Array();
            for(var j=0;j<brray.length;j++)
            { 
            var valSplitId=brray[j].split('_')[2];
            slinkPathArr.push(valSplitId);
            }  
            if(slinkPathArr.length>0)
            {
            resultIds=slinkPathArr.join();
            }
            
            StaffIdSrch=0;
            subIdSrch=brray[1].split('_')[2];
 
        }
        else if(brray.length>=3)
        { 
            var rowId=brray[brray.length-1].split('_')[2];
            staffId=brray[1].split('_')[2];
            subId=brray[0].split('_')[2];
          
            resultIds='';
            folderMode='staffFileInner';
           
            var slinkPathArr = new Array();
            for(var j=0;j<brray.length;j++)
            { 
            var valSplitId=brray[j].split('_')[2];
            slinkPathArr.push(valSplitId);
            }  
            if(slinkPathArr.length>0)
            {
            resultIds=slinkPathArr.join();
            }
            //alert(rowId+'='+staffId+'='+subId+'='+resultIds+'=');
            //StaffIdSrch=brray[2].split('_')[2];
            //subIdSrch=brray[1].split('_')[2];
        }
     // else if(brray.length>=4)
     // {
        
     //        var valSplitId=brray[0].split('_')[2];
           
     //        semIdSrch=valSplitId;
     //        modeSrch='staffFile';
     //         var slinkPathArr = new Array();
     //        for(var j=0;j<brray.length;j++)
     //        { 
     //        var valSplitId=brray[j].split('_')[2];
     //        slinkPathArr.push(valSplitId);
     //        }  
     //        if(slinkPathArr.length>0)
     //        {
     //        resultIdsSrch=slinkPathArr.join();
     //        }
            
     //        StaffIdSrch=brray[2].split('_')[2];
     //        subIdSrch=brray[1].split('_')[2];
     //         rowIdSrch=brray[brray.length-1].split('_')[2];;
  
 
     // }
     else{
 
          location.href=app_url + "/user/filemanagersclstudent";

      }
      
    }
    else
    {  
        //location.href=app_url + "/user/filemanagersclstudent";

    }
      
        $.ajax({
        type: "POST",
        url: app_url + "/user/filemanagersclstudent/ajaxsearchfm",
        data: {'_token': $('input[name=_token]').val(),'searchTxt':searchChar, 'cls_id': clsId,'section_id':SecId,'subId':subId ,'staffId': staffId,'pathIds':resultIds,'folderMode':folderMode,'fileRowId':rowId},
        success: function (objResponse) {
        var response = $.trim(objResponse);
        $("#ajax-fm").html(response);
        }          

        });
     
     
     }

    function clsLink(a)
    {

      ajaxfmStudent('', a, '','');
    }

     function delete_file(cFileName=null,cFileIs=null,cFiledelPath=null) {
       
      var cPath=$('#upBtn').attr('lang');
      $.ajax({
          type: "POST",
          url: app_url + "/user/filemanagersclstudent/delete",
          data: {'_token': $('input[name=_token]').val(), 'c_path': cPath, 'file_name': cFileName,'file_id':cFileIs,'FiledelPath': cFiledelPath},
          success: function (objResponse) {

            if(objResponse=='success')
            {
             
            fnTs1($('#upBtn').attr('lang'));
            }
            else
            {
              alert('Could not be delte a file');
            }
            
          } 
      });
   }

   $('#search_txt').keyup(function(){ 
              
      var search_txt = $('#search_txt').val();
      $('#hid_file_search').val('yes');
      $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagersclstudent'+' >Home</a>&nbsp;&raquo;&nbsp;Search result');
      ajaxSearfm(search_txt);
      return false;
       });


   function createfolder(folderId=null,lkPathId=null) { 

       $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagersclstudent/create_folder",
           data: $('#folder_create').serialize()+"&fldrId="+folderId+"&allId="+lkPathId,
           success: function (resp) { 

           

                 if(resp=='success') {
                 $('#myModal').modal('hide');
                 $("#myModal").trigger('reset');
                 $('#folder_name').val("");
                 
                 ajaxfmStudent('', folderId, '','');
                
               }
               else if(resp=='fail'){

                $('#folerr').html('Folder is already added.');
            
               } else {
                 $('#folerr').html(resp);
                   
               }
               //$('#folder_name').val("")
           }
       });
   }

   function fnTs1(b,c)
   {

    $('#search_txt').val("")
     var bNavarray = new Array();
    
     for(var i=0; i<brray.length;i++)
     {  
          var valSplitId=brray[i].split('_');
          var valNavSplitId=valSplitId[2];
          var brkVal;
          if(valNavSplitId==b)
          {bNavarray.push(brray[i]);
              break;
          }
          else
          {
            bNavarray.push(brray[i]);
          }
           
     }

     brray = new Array();
     brray=bNavarray;
     $('#brdcrum').html("");
     var bPushVal='<a class="root" href='+app_url+'/user/filemanagersclstudent'+'>Home</a>&nbsp;&raquo;&nbsp;';

     for(var i=0; i<brray.length;i++)
         {

            if(i==0)
            {
              brdParam='subject' ;       
            }
            else if(i==1)
            {
              brdParam='staff';
            } 
            else if(i==2) 
            {
              brdParam='folder' ;
            }
            else  
            {
              brdParam='innerfolder' ;
            }
             
           
          var valSplitId=brray[i].split('_')[2];
          var valSplitTxt=brray[i].split('_')[0];
          
          bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";

          
           }
           $('#brdcrum').html(bPushVal);
          $('#crbtn,#upBtn,#btnRefresh').attr('lang',b);
          $('#search').attr('lang',brray.join());

           if(c=='subject')
          {
            var subId=brray[0].split('_')[2];
            ajaxfmStudent('','',subId,'','staff',subId);
          }
          else if(c=='staff')
          {
            var subId=brray[0].split('_')[2];
            var stfId=brray[1].split('_')[2];
            ajaxfmStudent('','',subId,stfId,'staffFile',stfId);
          }
          else if(c=='folder')
          {

            var subId=brray[0].split('_')[2];
            var stfId=brray[1].split('_')[2];
            var rowId=brray[2].split('_')[2];
            ajaxfmStudent('','',subId,stfId,'staffFileInner',rowId);


          }
          else if(c=='innerfolder')
          {

            var subId=brray[0].split('_')[2];
            var stfId=brray[1].split('_')[2];
            var rowId=brray[brray.length-1].split('_')[2];
            ajaxfmStudent('','',subId,stfId,'staffFileInner',rowId);

          }
          else
          {
              location.href=app_url + "/user/filemanagersclstudent";
          }
   }

   Array.prototype.unique= function ()
{
  return this.reduce(function(previous, current, index, array)
   {
     previous[current.toString()+typeof(current)]=current;
     return array.length-1 == index ? Object.keys(previous).reduce(function(prev,cur)
       {
          prev.push(previous[cur]);
          return prev;
       },[]) : previous;
   }, {});
};

function fnTsSearch(clsId=null,sec_id=null,subId=null,staffId=null,fldermode=null,rowId=null,langVal=null)
 {
    brray=[];
    var expLanVal=langVal.split(',');
    for(var kk=0; kk<expLanVal.length;kk++)
    {
       brray.push(expLanVal[kk]);

    }
    $('#search').attr('lang',brray.join());
    $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagersclstudent'+' >Home</a>&nbsp;&raquo;&nbsp;Search result');
    ajaxfmStudent(clsId,sec_id,subId,staffId,fldermode,rowId);
 }
function fnTs(clsId=null,SecId=null,subId=null,staffId=null,folderMode=null,rowId=null)
 {    

       // alert(clsId+"=="+SecId+' ='+subId+'= '+staffId+'='+folderMode+'='+rowId);
        
        $('.flderLinks').prop('onclick',null).off('click');
        if(folderMode=='staff')
        {  
          $('#crbtn,#upBtn,#btnRefresh').attr('lang',subId);
          brray.push($('#getClassFlde_'+subId).html()+'_subject_'+subId); 
        //brray.unique();

        }
        else if(folderMode=='staffFile' )
        {  
         $('#crbtn,#upBtn,#btnRefresh').attr('lang',staffId);
        brray.push($('#getClassFlde_'+staffId).html()+'_Staff_'+staffId); 
        //brray.unique();

        }
        else
        {  
            $('#crbtn,#upBtn,#btnRefresh').attr('lang',rowId);
        brray.push($('#getClassFlde_'+rowId).html()+'_folder_'+rowId); 
        //brray.unique();

        }
      

        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagersclstudent'+' >Home</a>&nbsp;&raquo;&nbsp;';  
       
        for(var i=0; i<brray.length;i++)
        {

          var brdParam='';
         
            if(i==0)
            {
              brdParam= 'subject';
            } 
            else if(i==1) 
            {
              brdParam='staff' ;
            }
            else if(i==2) 
            {
              brdParam='folder' ;
            }
            else  
            {
              brdParam='innerfolder' ;
            }
         
        var valSplitId=brray[i].split('_')[2];
        var valSplitTxt=brray[i].split('_')[0];
        bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
        }

        if($('#hid_file_search').val()=='yes')
        {
          $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagersclstudent'+' >Home</a>&nbsp;&raquo;&nbsp;Search result');
        }
        else
        {

          $('#brdcrum').html(bPushVal);

        }
        
        $('#search').attr('lang',brray.join());
        ajaxfmStudent(clsId,SecId,subId,staffId,folderMode,rowId);
         
     }

     function fnTsOld(a)
     {    
        $('#crbtn,#upBtn,#btnRefresh').attr('lang',a);
        brray.push($('#getClassFlde_'+a).html()+'_'+a); 
 
        brray.unique();
 

        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagersclstudent'+' >Home</a>&nbsp;&raquo;&nbsp;';  
        for(var i=0; i<brray.length;i++)
        {

        var valSplitId=brray[i].split('_')[1];
        var valSplitTxt=brray[i].split('_')[0];
        bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
        }
        $('#brdcrum').html(bPushVal);
        $('#search').attr('lang',brray.join());

        ajaxfmStudent('', a, '','');
         
     }

     function rename_file(e) { 
      var new_value = e.value;
      var file_id = e.name;


      var slinkPathArr = new Array();
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[1];
      slinkPathArr.push(valSplitId);
      }  
       if(slinkPathArr.length>0)
       {

           pathIdsVals=slinkPathArr.join();
       }


       
      if(new_value !='') {
          $.ajax({
              type: "POST",
              url: app_url + "/user/filemanagersclstudent/rename",
              data: {'_token': $('input[name=_token]').val(), 'file_id': file_id, 'new_name': new_value, 'old_name': $("#hid_file_selected_name").val(), 'dir': $("#pwd").val(),'pathIds':pathIdsVals},
              success: function (objResponse) { 

                $('#hid_file_selected_name,#hid_file_selected_id').val("")
 
                 fnTs1($('#crbtn').attr('lang'));
                 // $("#file_name_area_"+file_id).html(new_value);
              } 
          });
      } else {
          alert("Please enter the value"); 
          $("#file_name_area_"+file_id).html($("#hid_file_selected_name").val());
      }
   }



function rename_folder(e) { 
      var new_value = e.value;
      var file_id = e.name;


      var slinkPathArr = new Array();
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[1];
      slinkPathArr.push(valSplitId);
      }  
       if(slinkPathArr.length>0)
       {

           pathIdsVals=slinkPathArr.join();
       }


      //var pathIdsVals=brray.join();

    //  alert(new_value);
     // alert(file_id)
      if(new_value !='') {

        if(/^[a-zA-Z0-9.-]+$/.test($.trim(new_value))) {
          $.ajax({
              type: "POST",
              url: app_url + "/user/filemanagersclstudent/renameflder",
              data: {'_token': $('input[name=_token]').val(), 'file_id': file_id, 'new_name': new_value, 'old_name': $("#hid_file_selected_name").val(), 'dir': $("#pwd").val(),'pathIds':pathIdsVals,'fileAccessId':$('#crbtn').attr('lang')},
              success: function (objResponse) { 

                $('#hid_file_selected_name,#hid_file_selected_id').val("");

                if(objResponse=='success')
                {
                  $("#getClassFlde_"+file_id).html(new_value);
                }

 
                 fnTs1($('#crbtn').attr('lang'));
                 // $("#file_name_area_"+file_id).html(new_value);
              } 
          });
        }
        else
        {

           alert('Special character/space are not allowed');
            $("#getClassFlde_"+file_id).html($("#hid_file_selected_name").val());
        }
      } else {
          alert("Please enter the value"); 
          $("#getClassFlde_"+file_id).html($("#hid_file_selected_name").val());
      }
   }


     function fnShowImg(filename) {  
        $('#mypic').modal('show'); 
        $('#imglang').attr('src',app_url+'/uploads/file_manager/'+filename);
   }
   function getBrdcrums(ids)
   {


    $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagersclstudent/getBrdcrum",
           data: $('#folder_create').serialize()+"&fid="+ids,
           success: function (resp) { 

            
           }
       });

   }


   var brray = new Array();
   var bNavarray = new Array();
   
  $(document).ready(function () { 

     
  $( "#subSelect" ).change(function() {

    $('#hid_file_search').val("");
     var subSelectId =$(this).val() ;
     var staffSelectVal=$('#staffSelect');
     $('#folerr2').html("");
     staffSelectVal.empty();
     staffSelectVal.append($("<option />").val("").text("--Select staff--"));
    $('#folerr2,#folerr1').html("");
    if(subSelectId>0)
    {
      $.ajax({
      type: "POST",
      url: app_url + "/user/filemanagersclstudent/staffSelect",
      dataType: 'JSON', 
      data: {'_token': $('input[name=_token]').val(), 'subSelectId':subSelectId},
        success: function (objResponse) {
       if(objResponse.error==1)
        {
        $.each(objResponse.output, function() {
      staffSelectVal.append($("<option />").val(this.scl_stf_id).text(this.staff_name));
        });
       }
      else
       {
        $('#folerr2').html("No staff found");
       //alert(objResponse.error);
        }
      }              
      });

    }
    else
   {
     $('#folerr1').html("Select subject");
    }
});

  $('#myModal').on('shown.bs.modal', function (e) { 
  $('#subSelect').val("");
  var staffSelectVal=$('#staffSelect');
  staffSelectVal.empty();
  staffSelectVal.append($("<option />").val("").text("--Select staff--"));
  });

  $('.btn-folder-create').click(function(){

    var subSelectVal=$('#subSelect').val();
    var staffSelectVal=$('#staffSelect').val();
   
    if(subSelectVal=="")
    {
       $('#folerr1').html("Select subject");
    }
    else
    {
      $('#folerr1').html("");
    }

    if(staffSelectVal=="")
    {
       $('#folerr2').html("Select staff");
    }
    else
    {
      $('#folerr2').html("");
    }

    if(subSelectVal>0 && staffSelectVal>0)
    {

        var SubVal=$("#subSelect option[value='"+subSelectVal+"']").text();
        var StfVal=$("#staffSelect option[value='"+staffSelectVal+"']").text();
        brray=[];
        brray.push(SubVal+'_subject_'+subSelectVal);
        brray.push(StfVal+'_Staff_'+staffSelectVal);
        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagersclstudent'+'>Home</a>&nbsp;&raquo;&nbsp;';

          for(var i=0; i<brray.length;i++)
          {

              if(i==0)
              {
              brdParam='subject' ;       
              }
              else if(i==1)
              {
              brdParam='staff';
              } 
              else if(i==2) 
              {
              brdParam='folder' ;
              }
              else  
              {
              brdParam='innerfolder' ;
              }
          var valSplitId=brray[i].split('_')[2];
          var valSplitTxt=brray[i].split('_')[0];
          bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
          }
          $('#brdcrum').html(bPushVal); 
          $('#search').attr('lang',brray.join());
          $('#myModal').modal('hide');
          $("#myModal").trigger('reset');
          ajaxfmStudent('','',subSelectVal,staffSelectVal,'staffFile',staffSelectVal);

      }
  });

      $('#myvid').on('hidden.bs.modal', function () {
          var myPlayer = videojs(document.getElementById('example_video_1'), {}, function() { });        
          myPlayer.pause();           
           
      });
 
      ajaxfmStudent('', 0, '','','');
      $('#upBtn').click(function(){ //alert($(this).attr('lang'))
      $('#current_url').val($(this).attr('lang'));
      var linkPathArr = new Array();

    // alert(brray.length);

      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[1];
      linkPathArr.push(valSplitId);
      }  
      $('#path_ids').val(linkPathArr);

      });
 

$('.close').click(function(){ 
$('#folder_name').val("");
$('.error').html("")
});

$('#btnRefresh').click(function(){ 
 fnTs1($('#btnRefresh').attr('lang'));
// ajaxfmStudent('', '', '',$(this).attr('lang'));
 });


Dropzone.options.imageUpload = {
           url: app_url +"/user/filemanagersclstudent/create_file",  
           maxFilesize: 10, //MB
           //acceptedFiles: ".jpeg,.jpg,.png,.gif"
           queuecomplete: function() {  
              $('div.dz-message').hide();
             // $('.dz-preview').html("");
              
           },
           success: function(file, response){

            if(response=='success')
            {

            }
            else
            {
               alert(response);
            } 
              
               
            }
       };
 
       $('#upFile').on('hidden.bs.modal', function () {   
        $('.dz-preview').html("");
           var pwd = $('#image-upload #pwd').val();
           var file_id = $('#image-upload #file_id').val();

           ajaxfmStudent('', $('#upBtn').attr('lang'), '','');
       });
   
      $('.btn-folder-create').click(function(){ 
 
      $("#folerr").show();
      var pwd = $('#pwd').val();
      var file_id = $('#file_id').val();
      if($.trim($('#folder_name').val())=='') {
       $('#folerr').html('Enter folder name.')
      } else {

        if(/^[a-zA-Z0-9.-]+$/.test($('#folder_name').val())) {

     
      var folderId=$('#crbtn').attr('lang');
      var linkPathArr = new Array();
     for(var j=0;j<brray.length;j++)
     {
        var valSplitId=brray[j].split('_')[1];
        linkPathArr.push(valSplitId);
     }  
      createfolder(folderId,linkPathArr);          
      }
      else
     {

      alert('Special character/space are not  allowed');
     }
     }



      });


  $.contextMenu({ 
  selector: '.context-menu-one', 
  build: function($trigger, e) { 
      var pwd = $('#frm_search #pwd').val();
      var file_id = $('#frm_search #file_id').val();  
      var search_txt = $('#frm_search #search_txt').val();
      //ajaxfmStudent(pwd, file_id, search_txt);
      var previous_file_id = $("#hid_file_selected_id").val(); 
      var previous_file_name = $("#hid_file_selected_name").val(); 
      if(previous_file_id !='') {
          $("#file_name_area_"+previous_file_id).html(previous_file_name);
      }
      var fileNameDel=$.trim(e.currentTarget.innerText) ;

      var fileId=e.currentTarget.id;
      var DelPath=e.currentTarget.lang ;
      

      $("#hid_file_selected_id").val(e.currentTarget.id);
      $("#hid_file_selected_name").val(e.currentTarget.innerText);                      
      return {
          callback: function(key, options) {  
              var file_id = $("#hid_file_selected_id").val();
              var file_name = $("#hid_file_selected_name").val();
              if(key == "edit") { 
                    /**rename for file **/
                    $("#file_name_area_"+fileId).html("<input type='text' name='"+fileId+"' id='file_"+fileId+"' value='"+fileNameDel+"' autocomplete='off' onblur='rename_file(this)' class='filenam' style='width:auto; height:auto;' />");
                   /**rename for folder **/
                   $("#getClassFlde_"+fileId).html("<input type='text' name='"+fileId+"' id='file_"+fileId+"' value='"+fileNameDel+"' autocomplete='off' onblur='rename_folder(this)' class='filenam' style='width:auto; height:auto;' />");


              } else if(key == "delete") {


       
                  if (confirm("Are you sure?")) {  
                     delete_file(fileNameDel,fileId,DelPath);
                  }
              }
          },
          items: {
               "edit": {name: "Rename"}, 
              "delete": {name: "Delete"},
              //"sep1": "---------",
              //"quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon'; }}
          }
      };
    }
  });
  });






