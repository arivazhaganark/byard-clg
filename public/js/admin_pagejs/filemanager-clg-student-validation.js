var app_root = $('#app_url').val();

function show_video(val) {

    $('#myvid').modal('show');
    $("video").attr("src", app_url + '/uploads/file_manager/' + val);
 }
    function ajaxfm(semId=null,subId=null,StaffId=null,folderName=null,mode=null,rowId=null) { 

     //alert(semId+"=="+subId+"=="+StaffId+"=="+folderName+"=="+mode+"=="+rowId)
//alert(brray.length);
    var resultIds='';
    $('#search_txt').val("")
    if(semId>0)
    {  
    var slinkPathArr = new Array();
    for(var j=0;j<brray.length;j++)
    {// alert(brray[j]);
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

    if(rowId=='null' || rowId==null)
    {
    rowId=0;
    }

    
    $.ajax({
    type: "POST",
    url: app_url + "/user/filemanagerclgstudent/HomeAjxDir",
    data: {'_token': $('input[name=_token]').val(), 'file_id': semId,'subId':subId ,'staffId': StaffId,'flname':folderName,'pathIds':resultIds,'folderMode':mode,'fileRowId':rowId},
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
       var semIdSrch='';
       var subIdSrch='';
       var StaffIdSrch='';
       var folderNameSrch='';
       var modeSrch='';
       var rowIdSrch='';
     if(fileid!='')
     {
 
        if(brray.length==1) 
        {
            var valSplitId=brray[0].split('_')[2];
            rowIdSrch=0;
            semIdSrch=valSplitId;
            modeSrch='subject';
            resultIdsSrch=valSplitId;
            StaffIdSrch=0;
            subIdSrch=0;
             
        }
        else if(brray.length==2)
        {

          var valSplitId=brray[0].split('_')[2];
            rowIdSrch=0;
            semIdSrch=valSplitId;
            modeSrch='staff';
             var slinkPathArr = new Array();
            for(var j=0;j<brray.length;j++)
            { 
            var valSplitId=brray[j].split('_')[2];
            slinkPathArr.push(valSplitId);
            }  
            if(slinkPathArr.length>0)
            {
            resultIdsSrch=slinkPathArr.join();
            }
            
            StaffIdSrch=0;
            subIdSrch=brray[1].split('_')[2];
 
        }
        else if(brray.length==3)
        {
            var valSplitId=brray[0].split('_')[2];
            rowIdSrch=0;
            semIdSrch=valSplitId;
            modeSrch='staffFile';
            var slinkPathArr = new Array();
            for(var j=0;j<brray.length;j++)
            { 
            var valSplitId=brray[j].split('_')[2];
            slinkPathArr.push(valSplitId);
            }  
            if(slinkPathArr.length>0)
            {
            resultIdsSrch=slinkPathArr.join();
            }
            
            StaffIdSrch=brray[2].split('_')[2];
            subIdSrch=brray[1].split('_')[2];
        }
     else if(brray.length>=4)
     {
        
            var valSplitId=brray[0].split('_')[2];
           
            semIdSrch=valSplitId;
            modeSrch='staffFile';
             var slinkPathArr = new Array();
            for(var j=0;j<brray.length;j++)
            { 
            var valSplitId=brray[j].split('_')[2];
            slinkPathArr.push(valSplitId);
            }  
            if(slinkPathArr.length>0)
            {
            resultIdsSrch=slinkPathArr.join();
            }
            
            StaffIdSrch=brray[2].split('_')[2];
            subIdSrch=brray[1].split('_')[2];
             rowIdSrch=brray[brray.length-1].split('_')[2];;
  
 
     }
     else{
   
          location.href=app_url + "/user/filemanagerclgstudent";

      }
      
    }
    else
    {
         //location.href=app_url + "/user/filemanagerclgstudent";

         

    }
      $.ajax({
      type: "POST",
      url: app_url + "/user/filemanagerclgstudent/searchHomeAjxDir",
      data: {'_token': $('input[name=_token]').val(),'searchTxt':searchChar, 'file_id': semIdSrch,'subId':subIdSrch ,'staffId': StaffIdSrch,'flname':folderNameSrch,'pathIds':resultIdsSrch,'folderMode':modeSrch,'fileRowId':rowIdSrch},
      success: function (objResponse) {

       
      var response = $.trim(objResponse);
      $("#ajax-fm").html(response);
      }          

      });
     
     }

    
  $('#search_txt').keyup(function(){ 
    var search_txt = $('#search_txt').val();
     $('#searchVals').val("yes");
     var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+'>Home</a>&nbsp;&raquo;&nbsp;Search result';
     $('#brdcrum').html(bPushVal);
    ajaxSearfm(search_txt);
    return false;
  });

  

   function fnTs1(b,c)
   {
   //alert(b+'=='+c+'---'+brray.length);
   //alert(brray);

    $('#search_txt').val("")
     var bNavarray = new Array();
     for(var i=0; i<brray.length;i++)
     {  
          var valSplitId=brray[i].split('_');
          var valNavSplitId=valSplitId[2];
          var brkVal;
 
            if(b=='subject')
            {
              //alert(b)
            }



//           if(b=='subject')
//           { alert(i);
//             bNavarray.push(brray[i]);
//             break;

//           }
//           else if(b=='staff')
//           {
// alert(i);
//             if(i==1)
//             {bNavarray.push(brray[i]);
//                break;
//            }
//            else
//           {
//               bNavarray.push(brray[i]);
//            }
             
//           }
//           else
//           {


//             if(valNavSplitId==b)
//           {bNavarray.push(brray[i]);
//               break;
//           }
//           else
//           {
//             bNavarray.push(brray[i]);
//           }


//           }

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
        //brray=bNavarray.slice();
      brray=bNavarray;
      // alert(brray)

        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+'>Home</a>&nbsp;&raquo;&nbsp;';
  
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
              brdParam='subfolder' ;
            }
             
           
          var valSplitId=brray[i].split('_')[2];
          var valSplitTxt=brray[i].split('_')[0];
          
          bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";

          
           }

          var searchTxtHead=$('#searchVals').val();
          if(searchTxtHead=="yes")
          {
             var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+'>Home</a>&nbsp;&raquo;&nbsp;Search result';
             $('#brdcrum').html(bPushVal);
          }
          else
          {
             $('#brdcrum').html(bPushVal);
          }
          

          $('#crbtn,#upBtn,#btnRefresh').attr('lang',b);
          $('#search').attr('lang',brray.join());

          if(c=='subject')
          {
            //ajaxfm('', b, '','','');
            //1==0==0====subject==0

            ajaxfm(b, 0, 0,'','subject',0);


          }
          else if(c=='staff')
          {
              //1==1==0====staff==0

               var smId=brray[0].split('_')[2];
                 

              ajaxfm(smId, b, 0,'','staff',0);
          }
          else  
          {
            var smId=brray[0].split('_')[2];
            var subId=brray[1].split('_')[2];
            var stfId=brray[2].split('_')[2];
            //alert(smId+' '+subId+' '+stfId)
           // 1==1==91====staffFile==null
           if(c=='folder')
            ajaxfm(smId, subId, stfId,'','staffFile','');
            else
              ajaxfm(smId, subId, stfId,'','staffFile',b);

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

    
 function fnTsSearch(semId=null,subId=null,staffId=null,folderMode=null,rowId=null,path=null)
 { 
   
  //alert(semId+' ='+subId+'= '+staffId+'='+folderMode+'='+rowId);

   $('.flderLinks').prop('onclick',null).off('click');
 
   brray=[];
   $('#crbtn,#upBtn,#btnRefresh').attr('lang',rowId);
   brray=path.split(',');
   $('#search').attr('lang',path);
   var slinkPathSearArr = new Array();;
   var resultValIds='';
    for(var j=0;j<brray.length;j++)
    { 
      var valSplitId=brray[j].split('_')[2];
      slinkPathSearArr.push(valSplitId);
    }  
    if(slinkPathSearArr.length>0)
    {

      resultValIds=slinkPathSearArr.join();
    }
    ajaxfm(semId,subId,staffId,'','staffFile',rowId);

  }

     function fnTs(semId=null,subId=null,staffId=null,folderMode=null,rowId=null)
     {    

          //alert(semId+' ='+subId+'= '+staffId+'='+folderMode+'=ss'+rowId);
        //$('.flderLinks').attr('disabled',true);
       //$('.flderLinks').attr('disabled', true);
       $('.flderLinks').prop('onclick',null).off('click');
        if(folderMode=='subject'){    
        $('#crbtn,#upBtn,#btnRefresh').attr('lang',semId);
        brray.push($('#getClassFlde_'+semId).html()+'_semester_'+semId); 
        brray.unique();
        }
        else if(folderMode=='staff')
        {  
          $('#crbtn,#upBtn,#btnRefresh').attr('lang',subId);
        brray.push($('#getClassFlde_'+subId).html()+'_subject_'+subId); 
        brray.unique();

        }
        else if(folderMode=='staffFile' && rowId==null )
        {  
         $('#crbtn,#upBtn,#btnRefresh').attr('lang',staffId);
        brray.push($('#getClassFlde_'+staffId).html()+'_Staff_'+staffId); 
        brray.unique();

        }
        else
        {  
            $('#crbtn,#upBtn,#btnRefresh').attr('lang',rowId);
        brray.push($('#getClassFlde_'+rowId).html()+'_folder_'+rowId); 
        brray.unique();



        }

        $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+' >Home</a>&nbsp;&raquo;&nbsp;';  
     
        for(var i=0; i<brray.length;i++)
        {

          var brdParam='';
         
        if(i==0)
            {
              brdParam='subject' ;       
            }
            else if(i==1)
            {
              brdParam= 'staff';
            } 
            else if(i==2) 
            {
              brdParam='folder' ;
            }
            else  
            {
              brdParam='subfolder' ;
            }
         
        var valSplitId=brray[i].split('_')[2];
        var valSplitTxt=brray[i].split('_')[0];
        bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
        }
 
        var searchTxtHead=$('#searchVals').val();
          if(searchTxtHead=="yes")
          {
             var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+'>Home</a>&nbsp;&raquo;&nbsp;Search result';
             $('#brdcrum').html(bPushVal);
          }
          else
          {
            $('#brdcrum').html(bPushVal);
          }

        
        $('#search').attr('lang',brray.join());
        ajaxfm(semId,subId,staffId,'',folderMode,rowId);
         
     }
  function fnShowImg(filename) {  
        $('#mypic').modal('show'); 
        $('#imglang').attr('src',app_url+'/uploads/file_manager/'+filename);
   }
   


   var brray = new Array();
   var bNavarray = new Array();
   
  $(document).ready(function () {

  $('#myvid').on('hidden.bs.modal', function () {
          var myPlayer = videojs(document.getElementById('example_video_1'), {}, function() { });        
          myPlayer.pause();           
           
      }); 

  $( "#semSelect" ).change(function() {
     
   var semSelectId =$(this).val() ;
   var subSelectval = $('#subSelect');
   subSelectval.empty();
   subSelectval.append($("<option />").val("").text("--Select Subject--"));

   var staffSelectVal=$('#staffSelect');
   staffSelectVal.empty();
   staffSelectVal.append($("<option />").val("").text("--Select staff--"));
   if(semSelectId>0){
 $('#folerr1').html("");
    $.ajax({
    type: "POST",
    url: app_url + "/user/filemanagerclgstudent/subjectSelect",
    dataType: 'JSON', 
    data: {'_token': $('input[name=_token]').val(), 'semSelectId': semSelectId},
    success: function (objResponse) {
      if(objResponse.status==1)
      {
      $.each(objResponse.result, function() {
            subSelectval.append($("<option />").val(this.sub_id).text(this.subject_name));
       });
      }
      else
      {
      alert(objResponse.error);
      }
    }              
    });
  }
  else
  {
     $('#folerr1').html("Select semester");
  }
});

    $('.btn-folder-create').click(function(){ 

      var semId=$('#semSelect').val();
      var subId=$('#subSelect').val();
      var staffId=$('#staffSelect').val();
      $('#folerr1,#folerr2,#folerr2').html("");

      if(semId>0)
      {

      }
      else
      {
        $('#folerr1').html("Select semester");
      }
      if(subId>0)
      {
           
      }
      else
      {
         $('#folerr2').html("Select subject");
      }
      if(staffId>0)
      {

      }
      else
      {
        $('#folerr3').html("Select staff");
      }

      if(semId>0 && subId>0 && staffId>0)
      {

        var SubVal=$("#subSelect option[value='"+subId+"']").text();
        var StfVal=$("#staffSelect option[value='"+staffId+"']").text();
        brray=[];
 
         brray.push('Semester-'+semId+'_semester_'+semId);
         brray.push(SubVal+'_subject_'+subId); 
         brray.push(StfVal+'_Staff_'+staffId); 
       

         $('#crbtn,#upBtn,#btnRefresh').attr('lang',staffId);

         $('#brdcrum').html("");
        var bPushVal='<a class="root" href='+app_url+'/user/filemanagerclgstudent'+' >Home</a>&nbsp;&raquo;&nbsp;';  
       
        for(var i=0; i<brray.length;i++)
        {

          var brdParam='';
         
        if(i==0)
            {
              brdParam='subject' ;       
            }
            else if(i==1)
            {
              brdParam= 'staff';
            } 
            else if(i==2) 
            {
              brdParam='folder' ;
            }
            else  
            {
              brdParam='subfolder' ;
            }
         
        var valSplitId=brray[i].split('_')[2];
        var valSplitTxt=brray[i].split('_')[0];
        bPushVal+="<span class='root' onclick='return fnTs1("+valSplitId+","+'"'+brdParam+'"'+")'  >"+valSplitTxt+"</span>&nbsp;&raquo;&nbsp;";
        }
        $('#brdcrum').html(bPushVal);
        $('#search').attr('lang',brray.join());

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


      $('#myModal').modal('hide');
      $("#myModal").trigger('reset');

      $.ajax({
      type: "POST",
      url: app_url + "/user/filemanagerclgstudent/HomeAjxDir",
      data: {'_token': $('input[name=_token]').val(), 'file_id': semId,'subId':subId ,'staffId': staffId,'flname':'','pathIds':resultIds,'folderMode':'staffFile','fileRowId':''},
      success: function (objResponse) {
      var response = $.trim(objResponse);
      $("#ajax-fm").html(response);
      }              
      });
      }
    });

    

    $( "#staffSelect" ).change(function() {
        var staffSelectVal=$('#staffSelect').val();
        if(staffSelectVal>0)
        {
          $('#folerr3').html(""); 
        }else
        {
          $('#folerr3').html("Select staff");
        }
    });

$( "#subSelect" ).change(function() { 
   var subSelectId =$(this).val() ;
   var semSelectId =$('#semSelect').val(); 
   var staffSelectVal=$('#staffSelect');
   staffSelectVal.empty();
   staffSelectVal.append($("<option />").val("").text("--Select staff--"));
 $('#folerr2').html("");
   if(semSelectId>0 && subSelectId>0)
   {
      $.ajax({
      type: "POST",
      url: app_url + "/user/filemanagerclgstudent/staffSelect",
      dataType: 'JSON', 
      data: {'_token': $('input[name=_token]').val(), 'semSelectId': semSelectId,'subSelectId':subSelectId},
      success: function (objResponse) {
       if(objResponse.status==1)
        {
        $.each(objResponse.result, function() {
      staffSelectVal.append($("<option />").val(this.cl_stf_id).text(this.staff_name));
        });
       }
      else
       {
       alert(objResponse.error);
        }
      }              
      });

   }
   else
   {
     $('#folerr2').html("Select subject");
   }
});


      $('#myModal').on('shown.bs.modal', function (e) { 
      $('#semSelect').val('');
      var subSelectval = $('#subSelect');
      subSelectval.empty();
      subSelectval.append($("<option />").val("").text("--Select Subject--"));
      var staffSelectVal=$('#staffSelect');
      staffSelectVal.empty();
      staffSelectVal.append($("<option />").val("").text("--Select staff--"));
      });
 
       ajaxfm('', 0, '','');
      $('#upBtn').click(function(){  
      $('#current_url').val($(this).attr('lang'));
      var linkPathArr = new Array();

    
      for(var j=0;j<brray.length;j++)
      {
      var valSplitId=brray[j].split('_')[1];
      linkPathArr.push(valSplitId);
      }  
      $('#path_ids').val(linkPathArr);

      });
 

$('.close').click(function(){  //ajaxfm('', 0, '','');
$('#folder_name').val("");
$('.error').html("")
});

$('#btnRefresh').click(function(){ 
 fnTs1($('#btnRefresh').attr('lang'));
// ajaxfm('', '', '',$(this).attr('lang'));
 });


  Dropzone.options.imageUpload = {
  url: app_url +"/user/filemanagerclgstudent/create_file",  
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
  ajaxfm('', $('#upBtn').attr('lang'), '','');
});
});






