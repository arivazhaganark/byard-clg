<?php

namespace App\Http\Controllers\user;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\ClgDepartModel;
use App\Model\backend\GraduateModel;
use App\Model\backend\DivisionModel;
use App\Model\backend\ClgYearModel;
use App\Model\backend\ClgCourseModel;
use App\Model\backend\ClgStudentModel;
use App\Model\backend\ClgSubjectModel;
use App\Model\backend\ClgAcademicYearModel;
use App\Model\backend\ClgStfSubMasterModel;
use App\Model\backend\ClgStfSubMapModel;
use App\Model\backend\ClgStaffModel;
use App\Model\backend\ClgFileManagerModel;
//use App\Model\backend\Admin;
use App\Model\backend\User;
use App\Model\backend\Setting;
 
use Redirect;
use Session;
use DB;
use File;
//use Image;
class FileManagerClgStfDBController extends Controller
{
  public function index()
  {
    $settingCnt=Setting::select('*')->where('id', 1)->count();
    $logoImgPath='';
    if($settingCnt>0)
    {
      $setting=Setting::select('*')->where('id', 1)->get(); 
      $logoImgPath=isset($setting[0]->img_path)?$setting[0]->img_path:'' ;
    } 
    return view('user.filemanagerClgStf',compact('logoImgPath'));
  }
 
  public function ajaxsearchfm(Request $request) {

    $chkUserAndSubMapped=$this->chkUserAndSubMapped();
    if($chkUserAndSubMapped['status']==1)
    {

        $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
        $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
        $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
        $inputs = $request->all();
        $fid = $inputs['file_id'];
        $sTxt = trim($inputs['search_txt']);
        $pathIds = $inputs['pathIds'];

        if ($fid=='') {
            $chkParentchildCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
            if ($chkParentchildCnt > 0) {
              $chkParentchild = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id])->where('file_name', 'like', '%' . $sTxt . '%')->get();

              $i = 0;
              $fileFolderCnt=0;
              $desc='';
              foreach($chkParentchild as $key => $chkParentchildvalue) {

               
                //$pathIds = $chkParentchildvalue->clg_stf_file_id;
                $i++;
                $urlPath = '';
                $imgName = '';
                $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                //$getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
                $pathIds=$chkParentchildvalue->path_folder_ids;
                if($pathIds=="")
                {
                    $getFileManagerPath=$chkParentchildvalue->file_path;
                }
                else
                {
                    $getFileManagerPath =$this->getFileManagerPathIds($pathIds);

                }
                //echo $pathIds.'======';
                 
               //echo $getFileManagerPath = $chkParentchildvalue->file_path.$chkParentchildvalue->file_name;
                $phyRes = '';
                $chkPathVal = '';
                if ($getFileManagerPath != '0') {

                  if($pathIds=="")
                {
                  $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath;
                     
                }
                else
                {
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath.$chkParentchildvalue->file_path;
                    //$getFileManagerPath =$this->getFileManagerPathIds($pathIds);

                }

                 $chkThumbPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->thumb_img;

                  $chkThumbUrlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->thumb_img);
                  $desc=$chkParentchildvalue->description;


                 


                  $imgName = "'" . $getFileManagerPath . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                  
                  $urlPath = url('uploads/file_manager/' . $getFileManagerPath);
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }

                if ($chkParentchildvalue->file_type == 'folder') {
                  /**chk physically folder exists or not **/

                    
                      $fileFolderCnt++;
                  echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTsSearch(' . $clg_stf_file_id . ');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                <span id="getClassFlde_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
                                <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';
                              
                }
                else
                {


                 // $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
                 //  $imgName="";
                 //  $phyRes='';

                  if (1) {
                  $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
                  $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                   $urlPathFile = url('uploads/file_manager/' . $getFileManagerPath );
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }


                  if ($phyRes == 'phy@no') {
                    $imgName = '';
                    $urlPath = '';
                  }
 
                  $imgName= "'" . base_path() . "/public/uploads/file_manager/".$getFileManagerPath.$chkParentchildvalue->file_name."'" ; 

                   $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                   $urlPath="'" . $getFileManagerPath.$chkParentchildvalue->file_name."'" ; 
                  $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                  if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp')
                  { 

                    $ic = '<a href="javascript:" onclick="return fnShowImg(' . $urlPath . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                    if (file_exists($chkThumbPath)) {

                       $ic = '<a href="javascript:" onclick="return fnShowImg(' . $urlPath . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  ></a>';

                    }
                }
                  else
                  if (strtolower($ext) == 'pdf'){ $ic = '<a href="' . $urlPathFile.'/'.$chkParentchildvalue->file_name . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              }
                  else
                  if (strtolower($ext) == 'doc'){ $ic = '<a href="' . $urlPathFile.'/'.$chkParentchildvalue->file_name . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              }
                  else
                  if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls'){ $ic = '<a href="' . $urlPathFile.'/'.$chkParentchildvalue->file_name . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              }
                  else
                  if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp'){ $ic = '<a href="' . $urlPathFile.'/'.$chkParentchildvalue->file_name . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              }
                  else
                  if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') { 
                    $ic = '<a href="javascript:" onclick="return show_video(' . $urlPath . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';

                    if (file_exists($chkThumbPath)) {
                        
                       $ic = '<a href="javascript:" onclick="return show_video(' . $urlPath . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';
 
                   } 

              }
                  else
                  if (strtolower($ext) == 'mp3'){ 

                    $ic = '<a href="javascript:" onclick="return show_video(' . $urlPath . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';

                     if (file_exists($chkThumbPath)) {

                       $ic = '<a href="javascript:" onclick="return show_video(' . $urlPath . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';
                       
                   } 

              }

                  else { $ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>'; }

                if (file_exists($chkPath)) {
                  $fileFolderCnt++;
                  echo '<div class="col-sm-2 mb-3p" id="2">
                    <div style="text-align: center;"   lang=' . $urlPath . ' id=' . $clg_stf_file_id . ' class="" >
                    ' . $ic . '
                    <span class="aliSearchCls" lang=' .  $imgName . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
                      <span class="des_content">' . $desc. '</span>
                    <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
                    </div>
                    </div>';
                  }

                }
                
              }
              if($fileFolderCnt==0)
                {
                  echo "Search result is empty";
                }
            }
            else {
              echo "No files or folder found";
            }
          }
          else
          {

 
         
             $chkParentchildCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $fid,'active'=>1])->get()->count();
             if($chkParentchildCnt>0)
             {

              $chkParentchild=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $fid,'active'=>1])->orderBy('semester_id')->get();
               $i=0;
              foreach($chkParentchild as $key => $chkParentchildvalue) {
                $i++;
                $urlPath = '';
                $imgName = '';
                 $phyRes='';
                  $desc=$chkParentchildvalue->description;
                if ($chkParentchildvalue->file_type == 'folder') {


                  $getAccessFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $fid])->get()->count();
                  $acessCls = '';
                  if ($getAccessFlderCnt > 0) //function for folder rename and delete
                  {
                    $getAccessFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $fid])->get();
                    if ($getAccessFlder[0]->folder_access == 1) {
                      $acessCls = "context-menu-one";
                    }
                  }

                  $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
                  $imgName="";
                  $phyRes='';

                  if ($getFileManagerPath != '0') {
                  $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
                  $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                  $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }

                  $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;

                       echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '" lang=' . $imgName . '   id=' . $clg_stf_file_id . '>

                      <a href="javascript:" class="flderLinks" onclick="return fnTsSearch(' . $clg_stf_file_id . ');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
                      <span class="aliSearchCls" lang=' . $imgName . '   id="getClassFlde_' . $clg_stf_file_id . '">' . strtolower($chkParentchildvalue->file_name) . '</span>
                      <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                      </div> 
                      </div>';

                 

                }
                else
                {


                 $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
                  $imgName="";
                  $phyRes='';

                  if ($getFileManagerPath != '0') {
                  $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
                  $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                  $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }


                  if ($phyRes == 'phy@no') {
                    $imgName = '';
                    $urlPath = '';
                  }
                   $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                  $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                  if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp'){ 

                    $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>'; 

                        if (file_exists($chkThumbPath)) {

                            $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  ></a>'; 

                        }

                  }
                  else
                  if (strtolower($ext) == 'pdf') { $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              } 
                  else
                  if (strtolower($ext) == 'doc') { $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                   }
                  else
                  if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') { $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>'; }
                  else
                  if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') { $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
              }
                  else
                  if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') { $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';
                if (file_exists($chkThumbPath)) {

                  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';

                        }
              }
                else
                  if (strtolower($ext) == 'mp3'){ $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>'; 

                if (file_exists($chkThumbPath)) {

                  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>'; 

                        }


              }
               else { $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
             }
 
                  echo '<div class="col-sm-2 mb-3p" id="2">
                    <div style="text-align: center;"   lang=' . $imgName . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
                    ' . $ic . '
                    <span class="aliSearchCls" lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
                    <span class="des_content">' . $desc. '</span>
                    <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
                    </div>
                    </div>';



                }


              }




             }
             else
             {

              echo "Folder is empty";
             }
             
          }


    }
    else
    {

    echo $chkUserAndSubMapped['errMsg'];
    exit;

    }

  }

//   public function ajaxsearchfmOLD(Request $request)
//   {

//     $chkUserAndSubMapped=$this->chkUserAndSubMapped();
//     if($chkUserAndSubMapped['status']==1)
//     {

//         $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
//         $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
//         $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
//         $inputs = $request->all();
//         $fid = $inputs['file_id'];
//         $sTxt = trim($inputs['search_txt']);
//         $pathIds = $inputs['pathIds'];

//         if ($fid == '') {
//             $chkParentchildCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
//             if ($chkParentchildCnt > 0) {
//               $chkParentchild = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0])->where('file_name', 'like', '%' . $sTxt . '%')->get();

//               $i = 0;
//               foreach($chkParentchild as $key => $chkParentchildvalue) {
//                 $pathIds = $chkParentchildvalue->clg_stf_file_id;
//                 $i++;
//                 $urlPath = '';
//                 $imgName = '';
//                 $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
//                 $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                 $phyRes = '';
//                 $chkPathVal = '';
//                 if ($getFileManagerPath != '0') {
//                   $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath;
//                   $imgName = "'" . $getFileManagerPath . "'";
//                   $chkPathVal = "'" . $chkPath . "'";
//                   $urlPath = url('uploads/file_manager/' . $getFileManagerPath);
//                   if (file_exists($chkPath)) {
//                     $phyRes = 'phy@yes';
//                   }
//                   else {
//                     $phyRes = 'phy@no';
//                   }
//                 }

//                 if ($chkParentchildvalue->file_type == 'folder') {
//                   /**chk physically folder exists or not **/
//                   echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
//                                 <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
//                                 <span id="getClassFlde_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                                 <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
//                                 <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                                 </div>
//                                 </div>';
//                 }
//                 else {
//                 }
//               }
//             }
//             else {
//               echo "No files or folder found";
//             }
//           }
//           else
//           {

//             if ($pathIds != "") 
//             {

//                $chkParentchildCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $fid])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();

//                if($chkParentchildCnt>0)
//                {

//                 $chkParentchild = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $fid])->where('file_name', 'like', '%' . $sTxt . '%')->get();

//                 $getAccessFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $fid])->get()->count();
//                 $acessCls = '';
//                 if ($getAccessFlderCnt > 0) //function for folder rename and delete
//                 {
//                   $getAccessFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $fid])->get();
//                   if ($getAccessFlder[0]->folder_access == 1) {
//                     $acessCls = "context-menu-one";
//                   }
//                 }


//                 $i = 0;
//                 foreach($chkParentchild as $key => $chkParentchildvalue) {
//                         $i++;
//                         $urlPath = '';
//                         $imgName = '';
//                         $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
//                         $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                         $phyRes = '';
//                         $chkPathVal = '';
//                         if ($getFileManagerPath != '0') {
//                           $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
//                           $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
//                           $chkPathVal = "'" . $chkPath . "'";
//                           $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
//                           if (file_exists($chkPath)) {
//                             $phyRes = 'phy@yes';
//                           }
//                           else {
//                             $phyRes = 'phy@no';
//                           }
//                         }
//                         if ($chkParentchildvalue->file_type == 'folder') {
//                     echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '"  lang=' . $imgName . ' id=' . $clg_stf_file_id . '>

//                                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
//                                       <span lang=' . $imgName . '   id="getClassFlde_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                                       <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
//                                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                                       </div> 
//                                       </div>';
//                   }
//                   else
//                   {

//                   if ($phyRes == 'phy@no') {
//                                   $imgName = '';
//                                   $urlPath = '';
//                                 }
//                    $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
//                   $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
//                   if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
//                   else
//                   if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
//                   else
//                   if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
//                   else
//                   if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
//                   else
//                   if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
//                   else
//                   if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
//                   else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';

//                  // echo $chkParentchildvalue->file_name;
//                   echo '<div class="col-sm-2 mb-3p" id="2">
//                     <div style="text-align: center;"   lang=' . $imgName . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
//                     ' . $ic . '
//                     <span lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                     <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
//                     <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
//                     </div>
//                     </div>';





//                   }

//                 }



//                }
//                else
//                {
//                 echo "No files or folder found";
//                }





//             }
//             else
//             {

//               echo "Invalid search";
//             }



//           }


//     }
//     else
//     {

//     echo $chkUserAndSubMapped['errMsg'];
//     exit;

//     }
// }
  

public function ajaxfm(Request $request)
{
        $chkUserAndSubMapped=$this->chkUserAndSubMapped();
        if($chkUserAndSubMapped['status']==1)
        {

          $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
          $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
          $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];

          $inputs = $request->all();
          $flId = $inputs['file_id']; //file manager table primary id
          $pathIds = $inputs['pathIds'];
          if($flId==0)
          {

            $chkParentCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0,'active'=>1])->get()->count();
            if($chkParentCnt>0)
            {

              $chkParent = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0,'active'=>1,'file_type'=>'folder'])->get();
               foreach($chkParent as $key => $chkParentValue) { 
                 $coureId=$chkParentValue->course_id;
                 $clg_stf_file_id = $chkParentValue->clg_stf_file_id;
                 $getCourseModelCnt=ClgCourseModel::where('course_id',$coureId)->get()->count();
                if ($getCourseModelCnt>0) {  
                    $getCourseModel=ClgCourseModel::where('course_id',$coureId)->get();
                   $courseName=strtolower($getCourseModel[0]->course_name);

                   echo '<div class="col-sm-2 mb-3p" id="25"><a class="brdcum" href=""> </a><div style="text-align: center;">
                      <a href="javascript:" class="flderLinks"  onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                      <span id="getClassFlde_' . $clg_stf_file_id . '">' .$courseName . '</span>
                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                      </div>
                      </div>';
                     
                }
              }
               

            }
            else
            {

              echo "No folder found";
              exit;
            }


          }
          else
          {
         
             $chkParentchildCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $flId,'active'=>1])->get()->count();
             if($chkParentchildCnt>0)
             {

              $chkParentchild=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $flId,'active'=>1])->orderBy('semester_id')->get();
               $i=0;
               $fileFolderCnt=0;
              foreach($chkParentchild as $key => $chkParentchildvalue) {
                $i++;
                $urlPath = '';
                $imgName = '';
                $phyRes='';
                $chkPath='';
                 $desc=$chkParentchildvalue->description;
                $pathIds=$chkParentchildvalue->path_folder_ids;

                if ($chkParentchildvalue->file_type == 'folder') {


                  $getAccessFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $flId])->get()->count();
                  $acessCls = '';
                  if ($getAccessFlderCnt > 0) //function for folder rename and delete
                  {
                    $getAccessFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $flId])->get();
                    if ($getAccessFlder[0]->folder_access == 1) {



                      if($chkParentchildvalue->file_published==1)
                      {
                         $acessCls = "context-menu-one-unpub";
                      }
                      else
                      {
                          $acessCls = "context-menu-one";
                      }

                     
                    

                    }
                  }

                   
                   if($pathIds=='')
                   {
                     $pathIds=$flId;
                   }
                  $getFileManagerPath =$this->getFileManagerPathIds($pathIds);
                  $imgName="";
                  $phyRes='';

                  if ($getFileManagerPath != '0') {
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
                   $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                  $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }

                  $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;

                   if (file_exists($chkPath)) {

                    $fileFolderCnt++;


                       echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '" lang=' . $imgName . '   id=' . $clg_stf_file_id . '>

                      <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
                      <span class="aliSearchCls" lang=' . $imgName . '   id="getClassFlde_' . $clg_stf_file_id . '">' . strtolower($chkParentchildvalue->file_name) . '</span>
                      <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                      </div>
                     
                      </div>';

                    }

                 

                }
                else
                {
                   $acessCls='';
                  if($chkParentchildvalue->file_published==1)
                      {
                         $acessCls = "context-menu-one-unpub";
                      }
                      else
                      {
                          $acessCls = "context-menu-one";
                      }
                  $getFileManagerPath =$this->getFileManagerPathIds($pathIds);
                  $imgName="";
                  $phyRes='';

                  if ($getFileManagerPath != '0') {
                  $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
                  $chkThumbPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->thumb_img;

                  $chkThumbUrlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->thumb_img);

                  $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
                  $chkPathVal = "'" . $chkPath . "'";
                  $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
                  if (file_exists($chkPath)) {
                    $phyRes = 'phy@yes';
                  }
                  else {
                    $phyRes = 'phy@no';
                  }
                }


                  if ($phyRes == 'phy@no') {
                    $imgName = '';
                    $urlPath = '';
                  }

                  $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                  $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                  if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp')
                  {

                    $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>'; 
                     if (file_exists($chkThumbPath)) {
                  
                       $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  > </a>'; 

                     }
                  
                  }
                  else
                  if (strtolower($ext) == 'pdf') {
                    $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                  }
                  else
                  if (strtolower($ext) == 'doc'){
                   $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                 }
                  else
                  if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                  else
                  if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp'){ $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                  }
                  else
                  if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg'){
                     $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';

                   if (file_exists($chkThumbPath)) {
                       $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';
 
                   } 

                   

                 }
                else
                  if (strtolower($ext) == 'mp3' ){ $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';
                 }
                  else{ $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                }

                  if (file_exists($chkPath)) {
                    $fileFolderCnt++;
                  echo '<div class="col-sm-2 mb-3p" id="2">
                    <div style="text-align: center;"   lang=' . $imgName . ' id=' . $clg_stf_file_id . ' class="'.$acessCls.'" >
                    ' . $ic . '
                    <span class="aliSearchCls" lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
                     <span class="DescCls" lang=' . $chkPathVal . '  id="desc_name_area_' . $clg_stf_file_id . '" style="display: none;">' . $desc. '</span>
                    <span class="des_content">' . $desc. '</span>
                    <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
                    </div>
                   
                    </div> 
                    </div>';
                  }



                }


              }
              if($fileFolderCnt==0)
          {
            echo "No files/folder found";
            exit;
          }




             }
             else
             {

              echo "Folder is empty";
             }


             
          }




        }
        else
        {

         echo $chkUserAndSubMapped['errMsg'];
         exit;

        }

       //  print_r($chkUserAndSubMapped);
  }

  public function filePublish(Request $request)
  {
    $inputs = $request->all();
    $file_id = $inputs['file_id'];
    $pMode=$inputs['mode'];
    $cl_stf_id=0;
    $clg_stf_sub_id=0;
    if($file_id>0)
    {  
       $getUserOrginPath = $this->chkUserAndSubMapped();

    // print_r($getUserOrginPath);

       if($getUserOrginPath['status']==1)
       {

        $cl_stf_id=$getUserOrginPath['cl_stf_id'];
        $clg_stf_sub_id=$getUserOrginPath['clg_stf_sub_id'];
       }
       else
       {

        echo $getUserOrginPath['errMsg'];
        exit;
       }
      if($pMode=='publish')
      {

        $chkFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $file_id])->get()->count();
        if($chkFlderCnt>0)
        {

          $chkFlderupdate = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $file_id])->update(['file_published'=>1]) ;

          echo "success";


        }
        else
        {
          echo "Invalid request";
          exit;
        }



      }
      elseif($pMode=='unpublish')
      {


         $chkFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $file_id])->get()->count();
        if($chkFlderCnt>0)
        {

          $chkFlderupdate = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $file_id])->update(['file_published'=>0]);

          echo "success";


        }
        else
        {
          echo "Invalid request";
          exit;
        }

      }
      else
      {

         echo "Invalid request";
          exit;
 
      }



    }
    else
    {
        echo "Invalid request";
          exit;
    }
     
  }

  public function renameflder(Request $request)
    {
     $inputs = $request->all();
      $file_id = $inputs['file_id'];
      $new_file_name = trim(strtolower($inputs['new_name']));
      $old_file_name = trim(strtolower($inputs['old_name']));
      $pathIds = $inputs['pathIds'];
    if($new_file_name == "" && $old_file_name == "" && $file_id == "" && $pathIds == "") {
       echo "Please try again";
       exit;
      }

     $getUserOrginPath = $this->chkUserAndSubMapped();
     if($getUserOrginPath['status']==0)
     {
      echo "Invalide user";
      exit;

     }
     $chkPhysicallyFlderExit='';
     $chkFilemanageChk=ClgFileManagerModel::where(['clg_stf_file_id' => $file_id,'file_type'=>"folder"])->get()->count();
     if($chkFilemanageChk>0)
     {

       $chkFilemanage=ClgFileManagerModel::where(['clg_stf_file_id' => $file_id,'file_type'=>"folder"])->get();
        $pathIds=isset($chkFilemanage[0]->path_folder_ids)?$chkFilemanage[0]->path_folder_ids:'';
        $chkPhysicallyFlderExit =$this->getFileManagerPathIds($pathIds);

       //$chkPhysicallyFlderExit=isset($chkFilemanage[0]->file_path)?$chkFilemanage[0]->file_path:'';


     }
     else
     {
      echo "denied for rename";
      exit;
     }
    
      if ($chkPhysicallyFlderExit != '') {
        $old_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $old_file_name;
        $new_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $new_file_name;
        if (file_exists($old_path)) {
           if (!file_exists($new_path)) {
             rename($old_path, $new_path);
             $updateFileName = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->update(['file_name' => $new_file_name]);
            echo "success";
             exit;
          }
          else {
           echo "new folder name already exists";
              exit;
            }
         }
         else {
           echo "old folder name not exists";
            exit;
      
        }
  }
}

  public function rename(Request $request) //Rename for file
  { 
    $inputs = $request->all();
    $file_id = $inputs['file_id'];
    $new_file_name = trim(strtolower($inputs['new_name']));
    $old_file_name = trim(strtolower($inputs['old_name']));
    $pathIds = $inputs['pathIds'];
    $data = array(
      'file_name' => $new_file_name
    );
    $getUserOrginPath = $this->chkUserAndSubMapped();
     if($getUserOrginPath['status']==0)
     {
      echo "Invalide user";
      exit;

     }
     $chkPhysicallyFlderExit='';
     $chkFilemanageChk=ClgFileManagerModel::where(['clg_stf_file_id' => $file_id,'file_type'=>"file"])->get()->count();
     if($chkFilemanageChk>0)
     {
       $chkFilemanage=ClgFileManagerModel::where(['clg_stf_file_id' => $file_id,'file_type'=>"file"])->get();


        $pathIds=isset($chkFilemanage[0]->path_folder_ids)?$chkFilemanage[0]->path_folder_ids:'';
        $chkPhysicallyFlderExit =$this->getFileManagerPathIds($pathIds);
 
      //$chkPhysicallyFlderExit=isset($chkFilemanage[0]->file_path)?$chkFilemanage[0]->file_path:'';

       
     }
     else
     {
      echo "denied for rename";
      exit;
     }

    //$chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($pathIds);
    if ($chkPhysicallyFlderExit != '') {
      $old_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $old_file_name;
      $new_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $new_file_name;
      if (file_exists($old_path)) {
        if (!file_exists($new_path)) {
          rename($old_path, $new_path);
          $updateFileName = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->update(['file_name' => $new_file_name]);
          echo "success";
          exit;
        }
        else {
          echo "new file name already exists";
          exit;
        }
      }
      else {
        echo "old file name not exists";
        exit;
      }
    }
    else {
      echo "Invalid file path";
      exit;
    }
  }

  public function getBrdcrum(Request $request)
  {
    $inputs = $request->all();
    $fid = $inputs['fid'];
    if ($fid > 0) {
      echo $this->getFileManagerPath($fid);
    }
    else {
      echo "";
    }
  }

 public function create_folder(Request $request)
  {
    $rowId = $request->fldrId;
    $linkPathIds = $request->allId;
    $folderNameParam = strtolower(trim($request->folder_name));
    if ($rowId > 0 && $folderNameParam != "") {

      $chkUserAndSubMapped=$this->chkUserAndSubMapped();

        if($chkUserAndSubMapped['status']==1)
        {

          $cl_stf_id=$chkUserAndSubMapped['cl_stf_id'] ;
          $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'] ;
          $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
          $chkParentchildCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' =>$clg_stf_sub_id, 'clg_stf_file_id' => $rowId, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
          if($chkParentchildCnt>0)
          {
            $getParentchkFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' =>$clg_stf_sub_id, 'parent_id' => $rowId, 'file_type' => 'folder'])->get();

            foreach ($getParentchkFlder as $key => $fldValue) {

              $fValue=strtolower($fldValue->file_name);
              if($folderNameParam==$fValue)
              {
                  echo "Folder name already exists";
                  exit;
              }
             
            }

          
            $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($linkPathIds);

            if (1) {

               $chkParentchildval = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $rowId, 'file_type' => 'folder'])->get(); 

               $chkPhysicallyFlderExit=isset($chkParentchildval[0]->file_path)?$chkParentchildval[0]->file_path:"" ;
               $underFolderName=isset($chkParentchildval[0]->file_name)?$chkParentchildval[0]->file_name:"";


              $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit .$underFolderName.'/'. $folderNameParam;
              if (!file_exists($destinationPath)) {
                 mkdir($destinationPath, 0777, true);
                $course_id=$chkParentchildval[0]->course_id;
                $semester_id =$chkParentchildval[0]->semester_id;
                $sub_id=$chkParentchildval[0]->sub_id;
                $parent_id = $chkParentchildval[0]->parent_id;
                $academic_year = $chkParentchildval[0]->academic_year;
                $insertPath=$chkPhysicallyFlderExit.$underFolderName.'/';
                $pathFolIds=$chkParentchildval[0]->path_folder_ids;
                $insertPathIds='';
                if($pathFolIds=='')
                {
                     $insertPathIds=$rowId;
                }
                else
                {
                    $insertPathIds=$pathFolIds.','.$rowId;
                }
                $InserFlder = ClgFileManagerModel::insert(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'course_id' => $course_id, 'semester_id' => $semester_id, 'sub_id' => $sub_id, 'parent_id' => $rowId, 'academic_year' => $academic_year, 'file_type' => 'folder', 'file_name' => $folderNameParam, 'folder_access' => 1,'file_path'=>"$insertPath",'path_folder_ids'=>"$insertPathIds"]);
                echo "success";

               }


            }
            else
            {
               echo "Invalid request";
               exit;
            }



          }
          else
          {
            echo "Folder creation denied";
            exit;
          }


        }
        else
        {

          echo "Invalid subject mapping";
          exit;

        }

    }
    else
    {
      echo "Invalid request";
      exit;
    }

  }

  public function updatedesc(Request $request)
  {
    $inputs = $request->all();
    $file_id=$inputs['file_id']; 
    $new_name=trim($inputs['new_name']);
    $updateDesc = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->update(['description' => $new_name]); 
    echo 'success';
  }


  public function delete(Request $request)
  {
    $inputs = $request->all();
    $c_path = $inputs['c_path'];
    $file_id = $inputs['file_id'];
    $FiledelPath = $inputs['FiledelPath'];
    $file_name = trim($inputs['file_name']);
    $chkUserAndSubMapped=$this->chkUserAndSubMapped();
 
    if($chkUserAndSubMapped['status']==1)
    {

        $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
        $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
        $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
        $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
        $chkFileOrFolderCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->get()->count();
        $getFileNameArr=[];
        if($chkFileOrFolderCnt>0)
        {
 
           $chkFileOrFolder = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->get();
           if ($chkFileOrFolder[0]->file_type == 'folder') {

            $cl_stf_id=$chkFileOrFolder[0]->cl_stf_id; 
            $course_id=$chkFileOrFolder[0]->course_id;
            $semester_id=$chkFileOrFolder[0]->semester_id; 
            $subject_id=$chkFileOrFolder[0]->sub_id;
            $this->rrmdir($dir,$cl_stf_id,$course_id,$semester_id,$subject_id,$file_id);
            $DelParentFile = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->delete();
            echo "success";
            exit;


           }
           else
           {
              $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;

               if(file_exists($dir)) {

                if (is_dir($dir)) {
                unlink($dir);
                }
                else
                {
                   unlink($dir);
                }


               }
 
              $DelFile = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->delete();
                echo "success";
          }


        }
        else
        {
          echo "Invalid request";
          exit;
        }


    }
    else
    {
      echo "Invalid folder access";
      exit;
    }

  }

 

  public function deleteold(Request $request)
  {
    $inputs = $request->all();
    $c_path = $inputs['c_path'];
    $file_id = $inputs['file_id'];
    $FiledelPath = $inputs['FiledelPath'];
    $file_name = trim($inputs['file_name']);
    $getUserOrginPath = $this->getFileManagerChkValidUser($file_id);
    if ($getUserOrginPath == 1) {
      $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
      $chkFileOrFolderCnt = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->get()->count();
      $fileorFlder = '';
      if ($chkFileOrFolderCnt == 1) {
        $chkFileOrFolder = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->get();
        if ($chkFileOrFolder[0]->file_type == 'folder') {
          if (is_dir($dir)) {
            $this->rrmdir($dir);
            $DelChildFile = SchFileManagerModel::where(['parent_id' => $file_id])->delete();
            $DelParentFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
            echo "success";
            exit;

            //    if (!is_dir($dir)) { //chk physically folder available or not
            //       $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
            //       $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
            //       echo "success";
            //       exit;
            // }
            // else
            // {
            //   //Here table only maintaining  the files we need file both physical and table
            //   $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
            //   $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
            //   echo "success";
            //   exit;
            // }

          }
          else {
            $DelChildFile = SchFileManagerModel::where(['parent_id' => $file_id])->delete();
            $DelParentFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
            echo "success";
            exit;
          }
        }
        else {
          $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
          if (is_dir($dir)) {
            unlink($dir);
          }

          $DelFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
          echo "success";
        }
      }
      else {
        echo "Invalid user access";
        exit;
      }

      //   $dir = base_path().'/public/uploads/file_manager/'.$FiledelPath;
      //    if(is_dir($dir)) {
      //       $this->rrmdir($dir);
      //       if (!is_dir($dir)) {
      //          $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
      //          $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
      //          echo "success";
      //          exit;
      //       }
      // } else {
      //    // unlink($dir);
      //    // $DelFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
      //    // echo "success";
      //  }

    }
    else {
      echo "Invalid user";
      exit;
    }
  }

  function rrmdir($dir,$cl_stf_id,$course_id,$semester_id,$subject_id,$file_id)
  {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir . "/" . $object) == "dir") {
            $this->rrmdir($dir . "/" . $object,$cl_stf_id,$course_id,$semester_id,$subject_id,$file_id);

            // $file = FileManagerModel::where('file_name', $object)->select('file_id')->first();
            // $file_id = @$file->file_id;
            //  ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->delete();

             $getCnt= ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->count();
             if($getCnt>0)
             {
               $getFile=ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->get();

              foreach ($getFile as $key => $value) {
                $clg_stf_file_id=$value->clg_stf_file_id;
                $explodeArr=explode(',',$value->path_folder_ids);

                if(count($explodeArr)>0)
                {

                   for($i=0;$i<count($explodeArr);$i++)
                   {

                    if($explodeArr[$i]==$file_id)
                    {

                     ClgFileManagerModel::where(['clg_stf_file_id'=> $clg_stf_file_id])->delete();
                    }

                   }

                }
                           
              }

             }
             


          }
          else {

            $getCntFile= ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->count();
            if($getCntFile>0)
            {

               $getFile= ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->get();

                 foreach ($getFile as $key => $value) {
                $clg_stf_file_id=$value->clg_stf_file_id;
                $explodeArr=explode(',',$value->path_folder_ids);

                if(count($explodeArr)>0)
                {

                   for($i=0;$i<count($explodeArr);$i++)
                   {

                    if($explodeArr[$i]==$file_id)
                    {

                     ClgFileManagerModel::where(['clg_stf_file_id'=> $clg_stf_file_id])->delete();
                    }

                   }

                }
                           
              }


            }
            // ClgFileManagerModel::where(['cl_stf_id'=> $cl_stf_id,"course_id"=>"$course_id","semester_id"=>"$semester_id","sub_id"=>"$subject_id","file_name"=>"$object"])->delete();
            unlink($dir . "/" . $object);
          }
        }
      }

      reset($objects);
      rmdir($dir);
    }
  }

  public function create_file(Request $request)
  {

    $inputs = $request->all();
    $curUploadPath = $inputs['current_url']; //file manager Id
    $path_ids = $inputs['path_ids']; //Path ids
    $fileRowId=$inputs['file_id'];
    if ($curUploadPath > 0  ) {

      $chkUserAndSubMapped=$this->chkUserAndSubMapped();

        if($chkUserAndSubMapped['status']==1)
        {

          $cl_stf_id=$chkUserAndSubMapped['cl_stf_id'] ;
          $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'] ;
          $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
                   
           if (1) {

          $chkParentchildvalCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder'])->get()->count();
              if ($chkParentchildvalCnt == 0) {
                echo "Invalid user";
                exit;
              }
              else
              {
               $chkParentchildvalAccessCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
                if ($chkParentchildvalAccessCnt == 0) //chk the folder path uploaded is posible
                {
                  echo "File upload denied";
                  exit;
                }

                $chkParentchildvalAccessVal = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get();
                  $course_id=$chkParentchildvalAccessVal[0]->course_id;
                  $semester_id=$chkParentchildvalAccessVal[0]->semester_id;
                  $sub_id=$chkParentchildvalAccessVal[0]->sub_id;
                  $parent_id= $chkParentchildvalAccessVal[0]->parent_id;
                  $academic_year=$chkParentchildvalAccessVal[0]->academic_year;
                  $filePath=$chkParentchildvalAccessVal[0]->file_path.$chkParentchildvalAccessVal[0]->file_name.'/';//for save file path in file manager table
                  //echo $chkPhysicallyFlderExit=$chkParentchildvalAccessVal[0]->file_path.$chkParentchildvalAccessVal[0]->file_name.'/';
                  //exit;
                  $pathFolIds=$chkParentchildvalAccessVal[0]->path_folder_ids;

                if($pathFolIds=='')
                {
                     $insertPathIds=$curUploadPath;
                }
                else
                {
                    $insertPathIds=$pathFolIds.','.$curUploadPath;
                }
                $chkPhysicallyFlderExit = $this->getFileManagerPathIds($insertPathIds);
               $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit;
              
                 $output='';

                  if (!empty($_FILES)) {
                  $tmpFile = $_FILES['file']['tmp_name'];
                   $filename = $destinationPath . $_FILES['file']['name'];
                  $time=time();
                  $ftype=strtolower($_FILES['file']['type']);
                  $ftypeVal=explode('/',strtolower($_FILES['file']['type']));
                  $ftype=isset($ftypeVal[0])?$ftypeVal[0]:'';
                  if (!file_exists($filename)) {
                  $actual_filename = $time . '-' . strtolower($_FILES['file']['name']);
                  $filename = $destinationPath . $actual_filename;
                  move_uploaded_file($tmpFile, $filename);
                  }
                  else {
                  $actual_filename = $time . '-' . strtolower($_FILES['file']['name']);
                    $filename = $destinationPath . $actual_filename;
                    move_uploaded_file($tmpFile, $filename);
                  } //chk below physically file upload or not
                  if (file_exists($destinationPath . $actual_filename)) {
                    $thumbnailName='';

                    if($ftype=='video' || $ftype=='audio')
                    {

                      $video =$destinationPath . $actual_filename;//'/var/www/html/brickyard-college/public/uploads/file_manager/ct500_ramanan/SampleVideo_1280x720_1mb.mp4';//$destinationPath . $actual_filename; //'uploads/SampleVideo_1280x720_1mb.mp4';
                    $thumbnailName=$time.'_thumb.png';
                    $thumbnail =$destinationPath. $thumbnailName;  //'/var/www/html/brickyard-college/public/uploads/file_manager/ct500_ramanan/samp_dddd/'. time().'_ddd3.jpg'; //'uploads/thumbnailnew34.jpg';
                    $thumbSize       = '125x125';
                    $output=shell_exec("ffmpeg -i '$video' -deinterlace -an -s  $thumbSize -ss 1 -t 00:00:01     -r 1 -y -vcodec mjpeg -f mjpeg '$thumbnail' 2>&1");
                    }
                    elseif($ftype=='image')
                    {
                       $thumbnailName=$time.'.'.pathinfo(strtolower($_FILES['file']['name']), PATHINFO_EXTENSION);;
                       $img = Image::make($destinationPath.$actual_filename)->resize(125,125)    ->save($destinationPath.$thumbnailName);
                    }
                  $InserFlder=ClgFileManagerModel::insert(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'course_id' => $course_id, 'semester_id' => $semester_id, 'sub_id' => $sub_id, 'parent_id' => $curUploadPath, 'academic_year' => $academic_year, 'file_type' => 'file', 'file_name' => $actual_filename,"file_path"=>"$filePath","path_folder_ids"=>"$insertPathIds","thumb_img"=>"$thumbnailName"]);
                  echo "success";//.$ftype. $output;//$thumbnail.'@@@@'.$video.'$$$$$'.$output;
                  exit;
                  }
                  else {
                    echo "File not uploaded successfully";
                    exit;
                  }
                  }
                  else {
                    echo "Upload file is empty";
                    exit;
                  }
 
              }
           }
           else
           {
               echo "Invalid request";
               exit;

           }


        }
        else
        {


         echo "Invalid subject mapping";
          exit;

        }
    }
    else
    {
      echo "Invalid request / file upload is  denied";
      exit;
    }
  }

  public function create_fileOLD(Request $request)
  {

    $inputs = $request->all();
    $curUploadPath = $inputs['current_url']; //file manager Id
    $path_ids = $inputs['path_ids']; //Path ids
    $fileRowId=$inputs['file_id'];
    if ($curUploadPath > 0 && $path_ids != "" ) {

      $chkUserAndSubMapped=$this->chkUserAndSubMapped();

        if($chkUserAndSubMapped['status']==1)
        {

          $cl_stf_id=$chkUserAndSubMapped['cl_stf_id'] ;
          $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'] ;
          $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
          $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($path_ids);
           if ($chkPhysicallyFlderExit != '0') {

            $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit;

          $chkParentchildvalCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder'])->get()->count();
              if ($chkParentchildvalCnt == 0) {
                echo "Invalid file path";
                exit;
              }
              else
              {

                // $chkParentchildval =clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder'])->get();

                $chkParentchildvalAccessCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
                if ($chkParentchildvalAccessCnt == 0) //chk the folder path uploaded is posible
                {
                  echo "File upload denied";
                  exit;
                }

                $chkParentchildvalAccessVal = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get();

                  $course_id=$chkParentchildvalAccessVal[0]->course_id;
                  $semester_id=$chkParentchildvalAccessVal[0]->semester_id;
                  $sub_id=$chkParentchildvalAccessVal[0]->sub_id;
                  $parent_id= $chkParentchildvalAccessVal[0]->parent_id;
                  $academic_year=$chkParentchildvalAccessVal[0]->academic_year;
                  $filePath=$chkParentchildvalAccessVal[0]->file_path.$chkParentchildvalAccessVal[0]->file_name.'/';//for save file path in file manager table 

                  if (!empty($_FILES)) {
                  $tmpFile = $_FILES['file']['tmp_name'];
                  $filename = $destinationPath . $_FILES['file']['name'];
                  if (!file_exists($filename)) {
                  $actual_filename = time() . '-' . strtolower($_FILES['file']['name']);
                  $filename = $destinationPath . $actual_filename;
                  move_uploaded_file($tmpFile, $filename);
                  }
                  else {
                  $actual_filename = time() . '-' . strtolower($_FILES['file']['name']);
                    $filename = $destinationPath . $actual_filename;
                    move_uploaded_file($tmpFile, $filename);
                  } //chk below physically file upload or not
                  if (file_exists($destinationPath . $actual_filename)) {
                  
                  $InserFlder=ClgFileManagerModel::insert(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'course_id' => $course_id, 'semester_id' => $semester_id, 'sub_id' => $sub_id, 'parent_id' => $curUploadPath, 'academic_year' => $academic_year, 'file_type' => 'file', 'file_name' => $actual_filename,"file_path"=>"$filePath"]);
                  echo "success";
                  exit;
                  }
                  else {
                    echo "File not uploaded successfully";
                    exit;
                  }
                  }
                  else {
                    echo "Upload file is empty";
                    exit;
                  }

 
 
              }
           }
           else
           {
               echo "Invalid request";
               exit;

           }


        }
        else
        {


         echo "Invalid subject mapping";
          exit;

        }
    }
    else
    {
      echo "Invalid request / file upload is  denied";
      exit;
    }
  }

  public function getuserAccessPath()
  {
    $getStffCode = auth()->guard('user')->user()->email;
    $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
    if ($chkStfMapCnt == 0) {
      return 0;
      exit;
    }
    else {
      $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
      $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
      $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
      if ($chkSubMasterCnt == 0) {
        return 0;
        exit;
      }
      else {
        $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
        if ($chkActiveCnt[0]->active == 0) {
          return 0;
          exit;
        }

        return $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
      }
    }
  }

  public function getFileManagerPath($fmRowId)
  {
    $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get()->count();
    if ($getUrlPathCnt > 0) {
      $getUrlPath = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get();
      $sch_cls_id = $getUrlPath[0]->sch_cls_id;
      $sec_id = $getUrlPath[0]->sec_id;
      $sub_id = $getUrlPath[0]->sub_id;
      $parentId = $getUrlPath[0]->parent_id;
      if ($sch_cls_id == 0 && $sec_id == 0 && $sub_id == 0) {
        return 0;
        exit;
      }
      else {
        $Path = '';
        if ($sch_cls_id > 0) {
          $getSclClsMaster = SchClassModel::where('sch_cls_id', $sch_cls_id)->get();
          if (isset($getSclClsMaster[0]->sch_class) && $getSclClsMaster[0]->sch_class != "") {
            $Path = $getSclClsMaster[0]->sch_class;
          }
        }

        if ($sec_id > 0) {
          $getSection = SchSectionModel::where(['sec_id' => $sec_id])->get();
          if (isset($getSection[0]->section_name) && $getSection[0]->section_name != "") {
            $Path.= '/' . $getSection[0]->section_name;
          }
        }

        if ($sub_id > 0) {
          $getSubject = SclSubjectModel::where(['sub_id' => $sub_id])->get();
          if (isset($getSubject[0]->sub_name) && $getSubject[0]->sub_name != "") {
            $Path.= '/' . $getSubject[0]->sub_name;
          }
        }

        $getRootPath = $this->getuserAccessPath();
        if ($getRootPath == '0') {
          return 0;
          exit;
        }
        else {
          return $getRootPath . '/' . $Path;
        }
      }
    }
  }
  // public function getFileManagerSearchPathIds($fmRowIds)
  // {

  //   $expIds = explode(',', $fmRowIds);
  //   if (count($expIds) > 0) {
  //     $urlPath = '';
  //     for ($i = 0; $i < count($expIds); $i++) 
  //     {
  //       $fmRowId = $expIds[$i];
  //       $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
  //       if ($getUrlPathCnt > 0) {
  //         $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();

  //           if($i==0)
  //           {
  //            $urlPath.= $getUrlPath[0]->file_path.$getUrlPath[0]->file_name . '/';
  //           }
  //           else
  //           {
  //             $urlPath.=$getUrlPath[0]->file_name.'/';
  //           }
  //       }
        
  //     }
  //     return $urlPath;
  //     exit;
      
  //   }
  //   else {
  //     return 0;
  //     exit;
  //   }

  // }

  public function getFileManagerPathIds($fmRowIds)
  {
//return $fmRowIds;exit;
    $expIds = explode(',', $fmRowIds);
    if (count($expIds) > 0) {
      $urlPath = '';
      for ($i = 0; $i < count($expIds); $i++) 
      {
        $fmRowId = $expIds[$i];
        $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
        if ($getUrlPathCnt > 0) {
          $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();

            if($i==0)
            {
             $urlPath.= $getUrlPath[0]->file_path.$getUrlPath[0]->file_name . '/';
            }
            else
            {
              $urlPath.=$getUrlPath[0]->file_name.'/';
            }
        }
        
      }
      return $urlPath;
      exit;
      
    }
    else {
      return 0;
      exit;
    }


  }

  public function getFileManagerPathWithIds($fmRowIds)
  {
    $expIds = explode(',', $fmRowIds);
    if (count($expIds) > 0) {
      $urlPath = '';
      for ($i = 0; $i < count($expIds); $i++) {
        $fmRowId = $expIds[$i];
        $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
        if ($getUrlPathCnt > 0) {
          $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();
          $urlPath.= $getUrlPath[0]->file_name . '/';
        }
      }

      $getRootPath = $this->chkUserAndSubMapped(); //$this->getuserAccessPath();
      if ($getRootPath['status'] == '1') {
        return $getRootPath['staffNameFolder'] . '/' . $urlPath;
        exit;
      }
      else {
        return 0;
        exit;
      }
    }
    else {
      return 0;
      exit;
    }
  }

  function getFileManagerChkValidUser($fmRowId)
  {
    $getStffCode = auth()->guard('user')->user()->email;
    $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
    if ($chkStfMapCnt == 0) {
      return 0;
      exit;
    }
    else {
      $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
      $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
      $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
      if ($chkSubMasterCnt == 0) {
        return 0;
        exit;
      }
      else {
        $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
        if ($chkActiveCnt[0]->active == 0) {
          return 0;
          exit;
        }
        else {
          $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId, 'scl_stf_id' => $scl_stf_id, ])->get()->count();
          if ($getUrlPathCnt > 0) {
            return 1;
            exit;
          }
          else {
            return 0;
          }
        }

        // return $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;

      }
    }
  }

  /** College function start **/

  public function chkUserAndSubMapped()
  {
  
    $result['status']=0;
    $result['errMsg']='';
    $getStffCode = auth()->guard('user')->user()->email;
    $chkStfMapCnt = ClgStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
    if ($chkStfMapCnt == 0) {
      $result['Permission denied'];
    }
    else
    {
        
      $getStaffDetails = ClgStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
      $cl_stf_id = $getStaffDetails[0]->cl_stf_id;
      $staffNameFolder = $getStffCode . '_' . $getStaffDetails[0]->staff_name;
      $chkSubMasterCnt = ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_master.cl_stf_id' =>$cl_stf_id,'at_college_staff_subject_master.active'=>1,'at_college_staff_subject_mapping.active'=>1])->get()->count();
      if($chkSubMasterCnt>0)
      {
         $chkSubMaster= ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_master.cl_stf_id' => $cl_stf_id,'at_college_staff_subject_master.active'=>1,'at_college_staff_subject_mapping.active'=>1])->get();
         $result['status']=1;
         $result['cl_stf_id']=$cl_stf_id;
         $result['clg_stf_sub_id']=isset($chkSubMaster[0]->clg_stf_sub_id)?$chkSubMaster[0]->clg_stf_sub_id:'';
         $result['staffNameFolder']=strtolower($staffNameFolder);
         $chkStaffFileManagerCnt=ClgFileManagerModel::where(['cl_stf_id'=>$cl_stf_id,'clg_stf_sub_id'=>$chkSubMaster[0]->clg_stf_sub_id,'active'=>1])->get()->count();
         if($chkStaffFileManagerCnt>0)
         {

         }
         else{
          $result['status']=0;
          $result['errMsg']='Staff file manager not found';
         } 
      }
      else
      {
         $result['errMsg']="Subject could not be mapped/blocked";
      }
    }
    return $result;
  }

}