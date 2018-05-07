<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SclStudentModel;
// use App\Model\backend\SchSectionModel;
// use App\Model\backend\SchClassModel;
// use App\Model\backend\SclSubjectModel;
//use App\Model\backend\SclstaffClassMappModel;
// use App\Model\backend\SclstaffClassMasterModel;
//  use App\Model\backend\SchStaffModel;
//use App\Model\backend\SchClsSectionMapModel;
// use App\Model\backend\SclStfSubMasterModel;
// use App\Model\backend\SclStfSubMapModel;
use Redirect;
use Session;
use DB;
use File;


class SclStudentFileManagerController extends Controller
  {


   public function index(){

      $getStuRollNo=auth()->guard('admin')->user()->email;
      $userType=auth()->guard('admin')->user()->usertype;

       if($userType=='SS') //School student
       {

        $ChkStuExits=SclStudentModel::where(['roll_no'=>$getStuRollNo])->count();  
        at_school_student_master


       }

  }

     
//       public function index(){

//          return view('backend.filemanager.add');
//     }
//     public function ajaxfm(Request $request) {

//       $getStffCode=auth()->guard('admin')->user()->email;

//       $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
//       if($chkStfMapCnt==0)
//       {
//         echo "Permission denied";
//         exit;
//       }
//       else
//       {
 
//         $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
//           $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

//         $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

//         if($chkSubMasterCnt==0)
//         {
          
//           echo "Staff yet not mapped subject";
//           exit;

//         }
//         else{

//          $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

//          if($chkActiveCnt[0]->active==0)
//          {

//             echo "Staff mode is inactive";
//             exit;

//          }
          
//           $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
//         }

//       }

//        $inputs=$request->all();
             
//        $flname=$inputs['flname'];

//        if($flname=="")
//        {
//            $path = base_path()."/public/uploads/file_manager/$staffNameFolder";
//        }
//        else
//        { 

//              $path = base_path()."/public/uploads/file_manager/$staffNameFolder".$flname;
         
//        }
            
//               if(1) {

//               $files = array();


//               if(file_exists($path)){

//               foreach(scandir($path) as $f) {

//               if(!$f || $f[0] == '.') {
//                 continue; // Ignore hidden files
//               }

//               if(is_dir($path . '/' . $f)) {

//                 $fileName="'".$path.$f."'";
//                 $filepathSplit=str_replace(base_path()."/public/uploads/file_manager/$staffNameFolder",'',$path.'/'.$f);
//                 $fileName="'".$filepathSplit."'";
//                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
//               <a href="javascript:"  onclick="return fnTs('.$fileName.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a>
//               <span>'.$f.'</span>
//               <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$f.'" style="display: none;">
//               </div>
//               </div>';

//               }

//               else {


//               $dir='';
//               $file = $path . $f;
//               if($flname=="")
//               {
//                 $filename = url('uploads/file_manager/'.$staffNameFolder.'/'.$f);
//                 $imgName="'".$staffNameFolder.$f."'";
//               }
//               else
//               { 

//                 $filename = url('uploads/file_manager/'.$staffNameFolder.$flname.'/'.$f);
//                 $imgName="'".$staffNameFolder.$flname.'/'.$f."'";

//               }


//               $ext = pathinfo($file, PATHINFO_EXTENSION);
//               if(strtolower($ext)=='jpg'||strtolower($ext)=='jpeg'||strtolower($ext)=='png'||strtolower($ext)=='gif'||strtolower($ext)=='bmp') 
//               $ic = '<a href="javascript:" onclick="return fnShowImg('.$imgName.');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
//               else if(strtolower($ext)=='pdf')
//               $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
//               else if(strtolower($ext)=='doc')
//               $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>'; 
//               else if(strtolower($ext)=='ods' || strtolower($ext)=='xlsx')
//               $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>'; 
//               else if(strtolower($ext)=='ppt' || strtolower($ext)=='pptx' || strtolower($ext)=='odp')
//               $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>'; 
//               else if(strtolower($ext)=='mp3' || strtolower($ext)=='mp4' || strtolower($ext)=='mpeg')
//               $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>'; 
//               else 
//               $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';

//               echo '<div class="col-sm-2 mb-3p" id="">
//               <div style="text-align: center;" class="context-menu-one">
//               '.$ic.'
//               <span>'.$f.'</span>
//               <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$f.'" style="display: none;">
//               </div>
//               </div>';


//               }
//               }

//               }

//               }

// }


//     public function create_folder(Request $request) {
//     $folderPath=$request->flpath;
//     $folderName=strtolower($request->folder_name);
//     if($folderPath !="" && $folderName !="")
//     {

//     $getUserOrginPath=$this->getuserAccessPath(); 

//     if($getUserOrginPath !='0' && $getUserOrginPath !="")
//     {


//     $destinationPath = base_path().'/public/uploads/file_manager/'.$getUserOrginPath.$folderPath.'/'.$folderName;
//     if (!file_exists($destinationPath)) {
//     mkdir($destinationPath, 0777, true);

//     echo   "success";

//     } else {
//     echo  'fail';
//     }

//     }
//     else
//     {

//     echo "Invalid request";

//     }
//     }
//     }

//    public function delete(Request $request) {
//         $inputs = $request->all();
//         $c_path = $inputs['c_path'];
//         $file_name = trim($inputs['file_name']);
//         $getUserOrginPath=$this->getuserAccessPath();

//         if($getUserOrginPath !='0' && $c_path !="" && $file_name !="")
//         {

//            $dir = base_path().'/public/uploads/file_manager/'.$getUserOrginPath.$c_path.'/'.$file_name;


//            if(is_dir($dir)) {
//              echo 'dir';
//         } else {
//             unlink($dir);
//             echo "success";
//         }  


//         }
//         else{

//           echo "failed";

//         } 

        
//     }


//       public function create_file(Request $request) {

      
//           $inputs=$request->all();
//           $curUploadPath=$inputs['current_url'];
//           $getUserOrginPath=$this->getuserAccessPath(); 
//           if($getUserOrginPath !='0' &&  $getUserOrginPath !=""  && $curUploadPath!="" ){
//           $dir = base_path().'/public/uploads/file_manager/'.$getUserOrginPath.$curUploadPath;

//           if (!empty($_FILES)) {
//           $tmpFile = $_FILES['file']['tmp_name'];
//           $filename = $dir.'/'.$_FILES['file']['name'];       
//           if (!file_exists($filename)) {
//           $actual_filename = time().'-'.$_FILES['file']['name'];    
//           $filename = $dir.'/'.$actual_filename;
           
//           move_uploaded_file($tmpFile,$filename);
//           } else {
//           $actual_filename = time().'-'.$_FILES['file']['name'];            
//           $filename = $dir.'/'.$actual_filename;
           
//           move_uploaded_file($tmpFile,$filename);
//           }
//           echo 'success';
//           }
//           }
//           else
//           {
//           echo "invalid parameters";
//           }

//       }
         
    

//     public function getuserAccessPath() {

//         $getStffCode=auth()->guard('admin')->user()->email;

//         $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
//         if($chkStfMapCnt==0)
//         {
            
//             return 0;
//              exit;
//         }
//     else
//     {

//         $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
//         $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

//         $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

//     if($chkSubMasterCnt==0)
//     {

//      return 0;
//      exit;

//     }
//     else{

//      $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

//     if($chkActiveCnt[0]->active==0)
//     {

//       return 0;
//      exit;

//     }
//     return $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
//     }


//     }

//     }
}