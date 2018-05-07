var app_root = $('#app_url').val();


 function ajaxfm(pwd=null, file_id=null, search_txt=null,folderName=null) { 
 //$("#ajax-fm").html("sdfadfasdfasdfasdfdfd dddddddddddddddd");
     //$("#ajax-fm").html("asdfasdfas dfa sdfa sdf asdfasdf");
 
       $.ajax({
           type: "POST",
           url: app_url + "/admin/schstffolder/ajaxfm",
           data: {'_token': $('input[name=_token]').val(), 'dir': pwd, 'file_id': file_id, 'search_txt': search_txt,'flname':folderName},
           success: function (objResponse) {
               var response = $.trim(objResponse);
               $("#ajax-fm").html(response);

               var SplArr=folderName.split('/');
               $('#headMenu').hide();
               if(SplArr.length>=4)
               {
                  $('#headMenu').show();
               }
               $('#brdcrum').html("");
               var jointLink="";
               var spanLnk='';
               for(var i=0; i<SplArr.length; i++)
               {
 
               if(SplArr[i] !=""){
               var sprLink= "'"+SplArr[i]+"'" ;
                jointLink+="/"+SplArr[i];

               spanLnk+='<span class="root" onclick=" return clsLink('+"'"+jointLink+"'"+');">'+SplArr[i]+'</span>&nbsp;&raquo;&nbsp;';


                 
}


               }
               //app_url + "/admin/schstffolder/ajaxfm",
$('#brdcrum').html("<a href='"+app_url+"/admin/schstffolder'>Home </a>&nbsp;&raquo;&nbsp;"+spanLnk);

              
                


               
           }
       });
     }

    function clsLink(a)
    {
      ajaxfm('', '', '',a);
    }

     function delete_file(cFileName=null) {
       
      var cPath=$('#upBtn').attr('lang');
      $.ajax({
          type: "POST",
          url: app_url + "/admin/schstffolder/delete",
          data: {'_token': $('input[name=_token]').val(), 'c_path': cPath, 'file_name': cFileName,},
          success: function (objResponse) {
               
             ajaxfm('', '', '',cPath);
          } 
      });
   }


   function createfolder(flderPath=null) { 
       $.ajax({
           type: "POST",
           url: app_url + "/admin/schstffolder/create_folder",
           data: $('#folder_create').serialize()+"&flpath="+flderPath,
           success: function (resp) {  
               if(resp=='success') {
                 $('#myModal').modal('hide');
                 $("#myModal").trigger('reset');
                 ajaxfm('', '', '',flderPath);
                
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

     function fnTs(a)
     { 
        $('#crbtn,#upBtn,#btnRefresh').attr('lang',a);
        ajaxfm('', '', '',a);
        $('.root').html(a);
     }

     function fnShowImg(filename) {  
     

       $('#mypic').modal('show'); 
       $('#imglang').attr('src',app_url+'/uploads/file_manager/'+filename);
   }
   
  $(document).ready(function () { 
                ajaxfm(); 

      $('#upBtn').click(function(){

      $('#current_url').val($(this).attr('lang'));

      });

       
      
// $('.aflder').click(function(){

  

// alert($(this).attr('lang'));

// });

$('.close').click(function(){ 
$('#folder_name').val("");
$('.error').html("")
 
});

$('#btnRefresh').click(function(){ 


 ajaxfm('', '', '',$(this).attr('lang'));


 });


Dropzone.options.imageUpload = {
           url: app_url +"/admin/schstffolder/create_file",  
           maxFilesize: 10, //MB
           //acceptedFiles: ".jpeg,.jpg,.png,.gif"
           queuecomplete: function() {  
              $('div.dz-message').hide();
           }
       };
 
       $('#upFile').on('hidden.bs.modal', function () {  
           var pwd = $('#image-upload #pwd').val();
           var file_id = $('#image-upload #file_id').val();
            ajaxfm('', '', '',$('#current_url').val());
       });
   
      $('.btn-folder-create').click(function(){ 
      $("#folerr").show();
      var pwd = $('#pwd').val();
      var file_id = $('#file_id').val();
      if($.trim($('#folder_name').val())=='') {
       $('#folerr').html('Enter folder name.')
      } else {

     // alert($('#crbtn').attr('lang'))
      var folderPath=$('#crbtn').attr('lang');
      createfolder(folderPath);          
      }
      });


  $.contextMenu({ 
  selector: '.context-menu-one', 
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
      $("#hid_file_selected_id").val(e.currentTarget.id);
      $("#hid_file_selected_name").val(e.currentTarget.innerText);                      
      return {
          callback: function(key, options) {  
              var file_id = $("#hid_file_selected_id").val();
              var file_name = $("#hid_file_selected_name").val();
              if(key == "edit") { 
                  //$("#file_name_area_"+file_id).html("<input type='text' name='"+file_id+"' id='file_"+file_id+"' value='"+file_name+"' autocomplete='off' onblur='rename_file(this)' class='filenam' style='width:70px; height:20px;' />");
              } else if(key == "delete") {
                  if (confirm("Are you sure?")) {  
                    delete_file(fileNameDel);
                  }
              }
          },
          items: {
             // "edit": {name: "Rename"}, 
              "delete": {name: "Delete"},
              //"sep1": "---------",
              //"quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon'; }}
          }
      };
    }
  });
  });






