var app_root = $('#app_url').val();

function show_video(val) {
   
    $('#myvid').modal('show');
    $("video").attr("src", app_url + '/uploads/file_manager/' + val);
 }
function ajaxfm(pwd=null, file_id=null, search_txt=null,folderName=null) { 
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
    
       $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagerclgstf/ajaxfm",
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
     
      if(searchChar !=null)
       {
        fileid='';
       }    
      
        $.ajax({
        type: "POST",
        url: app_url + "/user/filemanagerclgstf/ajaxsearchfm",
        data: {'_token': $('input[name=_token]').val(),'search_txt': searchChar,'file_id':fileid,'pathIds':resultIds },
        success: function (objResponse) {

         
        var response = $.trim(objResponse);
        $("#ajax-fm").html(response);
        }          

        });
     
     
     }

    function clsLink(a)
    {

      ajaxfm('', a, '','');
    }
     function publish_file(pFileId=null,pFileIdMode=null) {
     
       $.ajax({
              type: "POST",
              url: app_url + "/user/filemanagerclgstf/filePublish",
              data: {'_token': $('input[name=_token]').val(), 'file_id': pFileId, 'mode': pFileIdMode},
              success: function (objResponse) { 
                if(objResponse=='success'){


                }
                else
                {
                  alert(objResponse);

                }
                fnTs1($('#crbtn').attr('lang'));
                  
              } 
          });

     }

     function delete_file(cFileName=null,cFileIs=null,cFiledelPath=null) {
       
      var cPath=$('#upBtn').attr('lang');
      $.ajax({
          type: "POST",
          url: app_url + "/user/filemanagerclgstf/delete",
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
       $('#crbtn,#upBtn,#btnRefresh').attr('lang','');
       $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagerclgstf'+'>Home</a>&nbsp;&raquo;&nbsp;Search result');
       $('#searchChk').val('yes'); 
       ajaxSearfm(search_txt);
       return false;
       });


   function createfolder(folderId=null,lkPathId=null) { 

       $.ajax({
           type: "POST",
           url: app_url + "/user/filemanagerclgstf/create_folder",
           data: $('#folder_create').serialize()+"&fldrId="+folderId+"&allId="+lkPathId,
           success: function (resp) { 

           

                 if(resp=='success') {
                 $('#myModal').modal('hide');
                 $("#myModal").trigger('reset');
                 $('#folder_name').val("");
                 
                 ajaxfm('', folderId, '','');
                
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
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstf'+'>Home</a>&nbsp;&raquo;&nbsp;';
  
          for(var i=0; i<brray.length;i++)
         {
          
         var valSplitId=brray[i].split('_')[1];
          var valSplitTxt=brray[i].split('_')[0];
          
          bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";

          
           }


         var brdcrumHide=$('#searchChk').val();
       
          $('#brdcrum').html(bPushVal);
           if(brdcrumHide=='yes')
        {
          $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagerclgstf'+' >Home</a>&nbsp;&raquo;&nbsp;Search result');
        }
          $('#crbtn,#upBtn,#btnRefresh').attr('lang',b);
          $('#search').attr('lang',brray.join());



           
        //alert(brray);

     ajaxfm('', b, '','');
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

      function fnTsSearch(rowId=null)
      { 
        $('#crbtn,#upBtn,#btnRefresh').attr('lang',rowId);
        //$('#searchChk').val('yes'); 
        ajaxfm('', rowId, '','');
      }
     function fnTs(a)
     {    var brdcrumHide=$('#searchChk').val() ;
        $('#crbtn,#upBtn,#btnRefresh').attr('lang',a);
        brray.push($('#getClassFlde_'+a).html()+'_'+a); 
        $('.flderLinks').prop('onclick',null).off('click');
        /*Above function for avoid double click in folder no need to array unique need to test and remove array unique function*/
        brray.unique();//need to test b4 remove
 

        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstf'+' >Home</a>&nbsp;&raquo;&nbsp;';  
        for(var i=0; i<brray.length;i++)
        {

        var valSplitId=brray[i].split('_')[1];
        var valSplitTxt=brray[i].split('_')[0];
        bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
        }
        $('#brdcrum').html(bPushVal);
        $('#search').attr('lang',brray.join());
        if(brdcrumHide=='yes')
        {
          $('#brdcrum').html('<a class="root" href='+app_url+'/user/filemanagerclgstf'+' >Home</a>&nbsp;&raquo;&nbsp;Search result');
        }

         ajaxfm('', a, '','');
         //ajaxSearfm();
         
     }

     function rename_file(e) { 
      var new_value = e.value;
      var file_id = e.name;
      var pathIdsVals='';

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
              url: app_url + "/user/filemanagerclgstf/rename",
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


function update_desc(e)
{
    var desc_value = e.value;
    var descId = e.name;
    $.ajax({
    type: "POST",
    url: app_url + "/user/filemanagerclgstf/updatedesc",
    data: {'_token': $('input[name=_token]').val(), 'file_id': descId, 'new_name': desc_value},
    success: function (objResponse) { 
      fnTs1($('#crbtn').attr('lang'));
    } 
    });

}

function rename_folder(e) { 
      var new_value = e.value;
      var file_id = e.name;
       var pathIdsVals='';


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
        if(/^[a-zA-Z0-9.-]+$/.test($.trim(new_value))) {
          $.ajax({
              type: "POST",
              url: app_url + "/user/filemanagerclgstf/renameflder",
              data: {'_token': $('input[name=_token]').val(), 'file_id': file_id, 'new_name': new_value, 'old_name': $("#hid_file_selected_name").val(), 'dir': $("#pwd").val(),'pathIds':pathIdsVals,'fileAccessId':$('#crbtn').attr('lang')},
              success: function (objResponse) { 

                $('#hid_file_selected_name,#hid_file_selected_id').val("");

                if(objResponse=='success')
                {
                  $("#getClassFlde_"+file_id).html(new_value);
                }
                fnTs1($('#crbtn').attr('lang'));
               } 
          });

        }
        else{

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
           url: app_url + "/user/filemanagerclgstf/getBrdcrum",
           data: $('#folder_create').serialize()+"&fid="+ids,
           success: function (resp) { 

            
           }
       });

   }


   var brray = new Array();
   var bNavarray = new Array();
   
  $(document).ready(function () {


  $('#myvid').on('hidden.bs.modal', function () {
          var myPlayer = videojs(document.getElementById('example_video_1'), {}, function() { });        
          
              myPlayer.pause();           
           
      }); 
 
 
      ajaxfm('', 0, '','');
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

$('#myModal').on('hidden.bs.modal', function () {
$('#folder_name').val("");
$('.error').html("")
            });

$('#btnRefresh').click(function(){ 
 fnTs1($('#btnRefresh').attr('lang'));
// ajaxfm('', '', '',$(this).attr('lang'));
 });


Dropzone.options.imageUpload = {
           url: app_url +"/user/filemanagerclgstf/create_file",  
           maxFilesize: 10000, //MB
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

           ajaxfm('', $('#upBtn').attr('lang'), '','');
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
        else{
            alert('Special character/space are not  allowed');
        }
            
        }
      });


  $.contextMenu({ 
  selector: '.context-menu-one', 
  build: function($trigger, e) { 
//alert(5);
//debugger;

      var pwd = $('#frm_search #pwd').val();
      var file_id = $('#frm_search #file_id').val();  
      var search_txt = $('#frm_search #search_txt').val();
      //ajaxfm(pwd, file_id, search_txt);
      var previous_file_id = $("#hid_file_selected_id").val(); 
      var previous_file_name = $("#hid_file_selected_name").val(); 
      if(previous_file_id !='') {
          $("#file_name_area_"+previous_file_id).html(previous_file_name);
      }
      //alert(JSON.stringify($(this).attr('class','aliSearchCls')))
      var fileNameDel=$.trim(e.currentTarget.innerText) ;

      //alert(fileNameDel);

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
              }else if(key=="published"){

                  publish_file(fileId,'publish')  
              }else if(key=="desc"){
                var descVal=$("#desc_name_area_"+fileId).html();
                $("#desc_name_area_"+fileId).html("<input type='text' name='"+fileId+"' id='desc_"+fileId+"' value='"+descVal+"' autocomplete='off' onblur='update_desc(this)'  style='width:auto; height:auto;' />");
                $("#desc_name_area_"+fileId).show();
              }
              

          },
          items: {
               "published": {name: "Publish"},
               "edit": {name: "Rename"}, 
              "delete": {name: "Delete"},
              "desc": {name: "Description"},
              //"sep1": "---------",
              //"quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon'; }}
          }
      };
    }
  });


  $.contextMenu({ 
  selector: '.context-menu-one-unpub', 
  build: function($trigger, e) { 
      var pwd = $('#frm_search #pwd').val();
      var file_id = $('#frm_search #file_id').val();  
      var search_txt = $('#frm_search #search_txt').val();
      //ajaxfm(pwd, file_id, search_txt);
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
              else if(key=="unpublished"){

                 publish_file(fileId,'unpublish')  
              }
              else if(key=="desc"){
                var descVal=$("#desc_name_area_"+fileId).html();
                $("#desc_name_area_"+fileId).html("<input type='text' name='"+fileId+"' id='desc_"+fileId+"' value='"+descVal+"' autocomplete='off' onblur='update_desc(this)'  style='width:auto; height:auto;' />");
                $("#desc_name_area_"+fileId).show();
              }
          },
          items: {
               "unpublished": {name: "Un-publish"},
               "edit": {name: "Rename"}, 
              "delete": {name: "Delete"},
              "desc": {name: "Description"},
              //"sep1": "---------",
              //"quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon'; }}
          }
      };
    }
  });


  });






