var app_root = $('#app_url').val();

 function show_video(val) {
    $('#myvid').modal('show');
    $("video").attr("src", app_url + '/uploads/file_manager/' + val);
 }

 
 function ajaxfmStudent(pwd=null, file_id=null, search_txt=null,folderName=null) { 

  alert(4);
     var resultIds='';
     $('#search_txt').val("")
     if(file_id>0)
     {

       var slinkPathArr = new Array();
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[1];
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

      alert(4);
       $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagersclstudent/ajaxfm",
           data: {'_token': $('input[name=_token]').val(), 'dir': pwd, 'file_id': file_id, 'search_txt': search_txt,'flname':folderName,'pathIds':resultIds},
             success: function (objResponse) {

             
                 var response = $.trim(objResponse);
                 $("#ajax-fm").html(response);




                 }          
                


               
           
       });
     }

     function ajaxSearfm(searchChar=null)
     {
       var fileid=$('#upBtn').attr('lang') ;
       var resultIds='';
     if(fileid>0)
     {

       var slinkPathArr = new Array();
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_');
      var valSplitIdVal=valSplitId[valSplitId.length-1];
      slinkPathArr.push(valSplitIdVal);
      }  
       if(slinkPathArr.length>0)
       {
           resultIds=slinkPathArr.join();
       }

     }
      
        $.ajax({
        type: "POST",
        url: app_url + "/user/filemanagersclstudent/ajaxsearchfm",
        data: {'_token': $('input[name=_token]').val(),'search_txt': searchChar,'file_id':fileid,'pathIds':resultIds },
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
             //var pwd = $('#frm_search #pwd').val();
             //var file_id = $('#frm_search #file_id').val();
             var search_txt = $('#search_txt').val();

             
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

   function fnTs1(b)
   {

    $('#search_txt').val("")
     var bNavarray = new Array();
      for(var i=0; i<brray.length;i++)
     {
          var valSplitId=brray[i].split('_');
          //var valSplitIdVal=valSplitId[valSplitId.length-1];
          var valNavSplitId=valSplitId[valSplitId.length-1];
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
       brray=bNavarray.slice();

$('#brdcrum').html("");
var bPushVal='<a class="root" href='+app_url+'/user/filemanagersclstudent'+'>Home</a>&nbsp;&raquo;&nbsp;';
  
          for(var i=0; i<brray.length;i++)
         {
          
         var valSplitId=brray[i].split('_')[1];
          var valSplitTxt=brray[i].split('_')[0];
          
          bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";

          
           }

       
          $('#brdcrum').html(bPushVal);
          $('#crbtn,#upBtn,#btnRefresh').attr('lang',b);
          $('#search').attr('lang',brray.join());



           
        //alert(brray);

     ajaxfmStudent('', b, '','');
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

     function fnTs(a)
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


      //var pathIdsVals=brray.join();

    //  alert(new_value);
     // alert(file_id)
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
   
  $(document).ready(function () { alert(433)

      $('#myvid').on('hidden.bs.modal', function () {
          var myPlayer = videojs(document.getElementById('example_video_1'), {}, function() { });        
          
              myPlayer.pause();           
           
      });
 
      ajaxfmStudent('', 0, '','');
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






