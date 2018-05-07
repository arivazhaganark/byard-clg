<?php

namespace App\Http\Controllers\backend;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SclSubjectModel;
use App\Model\backend\SclstaffClassMasterModel;
use App\Model\backend\SchStaffModel;
use App\Model\backend\SclStfSubMasterModel;
use App\Model\backend\SclStfSubMapModel;
use App\Model\backend\SchFileManagerModel;

use Redirect;
use Session;
use DB;
use File;


class FileManagerDBController extends Controller
{
     
      public function index(){

         return view('backend.filemanagerDB.add');
    }
  public function ajaxsearchfm(Request $request)
  {

     $getStffCode=auth()->guard('admin')->user()->email;

          $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
          if($chkStfMapCnt==0)
          {
            echo "Permission denied";
            exit;
          }
          else
          {
            $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
            $scl_stf_id=$getStaffDetails[0]->scl_stf_id;
            $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

            if($chkSubMasterCnt==0)
            {

            echo "Staff yet not mapped subject";
            exit;

            }
            else{


                $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

                if($chkActiveCnt[0]->active==0)
                {

                echo "Staff mode is inactive";
                exit;

                }

                 $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;
                 $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
                 $chkMappingFileTbl=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId])->get()->count();

                 if($chkMappingFileTbl>0)
                 {
                        
                      $inputs=$request->all();
                      $fid=$inputs['file_id'];
                      $sTxt=trim($inputs['search_txt']);
                      $pathIds=$inputs['pathIds'];

                      if($fid=='')
                      { 
                          $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>0])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();

                          if($chkParentchildCnt>0)
                          {

                            $chkParentchild= SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>0])->where('file_name', 'like', '%' . $sTxt . '%')->get();
                            $i=0;
                            foreach ($chkParentchild as $key => $chkParentchildvalue) {

                                  $pathIds=$chkParentchildvalue->scl_stf_file_id;
 
                                  $i++;
                                  $urlPath='';
                                  $imgName='';
                                  $scl_stf_file_id=$chkParentchildvalue->scl_stf_file_id;
                                  $getFileManagerPath=$this->getFileManagerPathWithIds($pathIds);
                                  $phyRes='';
                                  $chkPathVal='';
                                  if($getFileManagerPath !='0')
                                  {
                                      $chkPath = base_path()."/public/uploads/file_manager/".$getFileManagerPath ;
                                       
                                     $imgName="'".$getFileManagerPath."'";
 
                                     $chkPathVal="'".$chkPath."'";

                                     $urlPath=url('uploads/file_manager/'.$getFileManagerPath);

                                      if(file_exists($chkPath)){

                                          $phyRes='phy@yes';
                                      }
                                      else
                                      {
                                          $phyRes='phy@no';

                                      }


                                  }

                                if($chkParentchildvalue->file_type=='folder')
                                {

                                /**chk physically folder exists or not **/

                                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTs('.$scl_stf_file_id.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a>
                                <span id="getClassFlde_'.$scl_stf_file_id.'">'.$chkParentchildvalue->file_name.'</span>
                                <input type="hidden" name="hid_res_'.$scl_stf_file_id.'" id="hid_res_'.$scl_stf_file_id.'" value="'.$phyRes.'">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';
                                }
                                else
                                {


                                }

                            }

                          }
                          else
                          {
                            echo "No files or folder found";
                          }

                      }
                      else //Not in root directory
                      {


                         if($pathIds !="")
                         {


                           $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$fid])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();

                             if($chkParentchildCnt>0)
                             {

                                 $chkParentchild = SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$fid])->where('file_name', 'like', '%' . $sTxt . '%')->get();

                  
                                  $getAccessFlderCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$fid])->get()->count();
                                  $acessCls='';
                                  if($getAccessFlderCnt>0) //function for folder rename and delete
                                  {

                                      $getAccessFlder=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$fid])->get();

                                      if($getAccessFlder[0]->folder_access==1)
                                      {
                                      $acessCls="context-menu-one"  ;
                                      }

                                  }




                                 $i=0;
                                 foreach ($chkParentchild as $key => $chkParentchildvalue) 
                                 {

                                       

                                      $i++;
                                      $urlPath='';
                                      $imgName='';
                                      $scl_stf_file_id=$chkParentchildvalue->scl_stf_file_id;
                                      $getFileManagerPath=$this->getFileManagerPathWithIds($pathIds);
                                      
                                      $phyRes='';
                                      $chkPathVal='';
                                      if($getFileManagerPath !='0')
                                      {
                                          $chkPath = base_path()."/public/uploads/file_manager/".$getFileManagerPath.$chkParentchildvalue->file_name;
                                          $imgName="'".$getFileManagerPath.$chkParentchildvalue->file_name."'";

                                          $chkPathVal="'".$chkPath."'";

                                          $urlPath=url('uploads/file_manager/'.$getFileManagerPath.$chkParentchildvalue->file_name);

                                          if(file_exists($chkPath)){
                                              $phyRes='phy@yes';
                                          }
                                          else
                                          {
                                              $phyRes='phy@no';

                                          }


                                      }

                                      if($chkParentchildvalue->file_type=='folder')
                                      {

                                      echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="'.$acessCls.'"  lang='.$imgName.' id='.$scl_stf_file_id.'>

                                      <a href="javascript:" class="flderLinks" onclick="return fnTs('.$scl_stf_file_id.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a> 
                                      <span lang='.$imgName.'   id="getClassFlde_'.$scl_stf_file_id.'">'.$chkParentchildvalue->file_name.'</span>
                                      <input type="hidden" name="hid_res_'.$scl_stf_file_id.'" id="hid_res_'.$scl_stf_file_id.'" value="'.$phyRes.'">
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                      </div> 
                                      </div>';




                                      }
                                      else
                                      {

                                              if($phyRes=='phy@no')
                                              {
                                                $imgName='';
                                                $urlPath='';
                                                  
                                              }
                         
                                           $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                                           if(strtolower($ext)=='jpg'||strtolower($ext)=='jpeg'||strtolower($ext)=='png'||strtolower($ext)=='gif'||strtolower($ext)=='bmp') 
                                            $ic = '<a href="javascript:" onclick="return fnShowImg('.$imgName.','.$scl_stf_file_id.');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                                          else if(strtolower($ext)=='pdf')
                                          $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                                          else if(strtolower($ext)=='doc')
                                          $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>'; 
                                          else if(strtolower($ext)=='ods' || strtolower($ext)=='xlsx')
                                          $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>'; 
                                          else if(strtolower($ext)=='ppt' || strtolower($ext)=='pptx' || strtolower($ext)=='odp')
                                          $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>'; 
                                          else if(strtolower($ext)=='mp3' || strtolower($ext)=='mp4' || strtolower($ext)=='mpeg')
                                          $ic = '<a  href="'.$urlPath.'" target="_blank"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>'; 
                                          else 
                                          $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';

                                          echo '<div class="col-sm-2 mb-3p" id="2">
                                          <div style="text-align: center;" lang='.$imgName.' id='.$scl_stf_file_id.' data="fileDel" class="context-menu-one">
                                          '.$ic.'
                                          <span lang='.$chkPathVal.'   id="file_name_area_'.$scl_stf_file_id.'">'.$chkParentchildvalue->file_name.'</span>
                                          <input type="hidden" name="hid_res_'.$scl_stf_file_id.'" id="hid_res_'.$scl_stf_file_id.'" value="'.$phyRes.'">
                                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$chkParentchildvalue->file_name.'" style="display: none;">
                                          </div>
                                          </div>';  

                                      }

                                }

                             }
                             else
                             {
                              echo "No search result found";
                              exit;
                             }

                         }
                         else
                         {
                          echo "Invalid Search";
                          exit;
                         }

                     }

                 }
                 else
                 {

                    echo "Staff not mapping in file manager part";
                    exit;
                 }

            }

          }

 
    
  }
  public function ajaxfm(Request $request) {
 
          $getStffCode=auth()->guard('admin')->user()->email;

          $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
          if($chkStfMapCnt==0)
          {
            echo "Permission denied";
            exit;
          }
          else
          {

            $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
            $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

            $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

          if($chkSubMasterCnt==0)
          {

            echo "Staff yet not mapped subject";
            exit;

          }
          else{

            $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

          if($chkActiveCnt[0]->active==0)
          {

            echo "Staff mode is inactive";
            exit;

          }


          $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;
           
          $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;

          $chkMappingFileTbl=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId])->get()->count();

          if($chkMappingFileTbl>0)
          {


             $inputs=$request->all();
             $flId=$inputs['file_id']; //file manager table primary id
             $pathIds=$inputs['pathIds'];

              if($flId==0)
              {

                 

                /* get Parent folder like School Standard start*/

                $chkParentCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>0])->get()->count();
 
                if($chkParentCnt>0)
                {
 
                  $chkParent=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>0])->orderBy('sch_cls_id')->get();


                  foreach ($chkParent as $key => $chkParentValue) 
                  {
                    $sch_cls_id=$chkParentValue->sch_cls_id;
                    $scl_stf_file_id=$chkParentValue->scl_stf_file_id;

                    $getSclClsMaster=SchClassModel::where('sch_cls_id',$sch_cls_id)->get();
 
                      if(isset($getSclClsMaster[0]->sch_class) && $getSclClsMaster[0]->sch_class !="")
                      {                        
                        
  
                       echo '<div class="col-sm-2 mb-3p" id="25"><a class="brdcum" href=""> </a><div style="text-align: center;">
                      <a href="javascript:" class="flderLinks"  onclick="return fnTs('.$scl_stf_file_id.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a>
                      <span id="getClassFlde_'.$scl_stf_file_id.'">'.$getSclClsMaster[0]->sch_class.'</span>
                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                      </div>
                      </div>';


                      }
                     
                   
                  } 


                }

                /* get Parent folder like Standard end */



              }
              else
              { 

                  $chkParentchildCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$flId])->get()->count();
                 if($chkParentchildCnt>0)
                 {


                  $chkParentchild=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$flId])->get();
                  $i=0;
                  foreach ($chkParentchild as $key => $chkParentchildvalue) {

                    $i++;
                    $urlPath='';
                    $imgName='';
                    $scl_stf_file_id=$chkParentchildvalue->scl_stf_file_id;

                     //$getFileManagerPath=$this->getFileManagerPath($flId);
                     $getFileManagerPath=$this->getFileManagerPathWithIds($pathIds);

                      $phyRes='';
                      $chkPathVal='';
                      if($getFileManagerPath !='0')
                      {
                         $chkPath = base_path()."/public/uploads/file_manager/".$getFileManagerPath.$chkParentchildvalue->file_name;
                         $imgName="'".$getFileManagerPath.$chkParentchildvalue->file_name."'";

                         $chkPathVal="'".$chkPath."'";

                          $urlPath=url('uploads/file_manager/'.$getFileManagerPath.$chkParentchildvalue->file_name);
 
                         if(file_exists($chkPath)){

                          $phyRes='phy@yes';


                         }
                         else
                         {
                            $phyRes='phy@no';

                         }

                     
                      }

                    if($chkParentchildvalue->file_type=='folder')
                    {

     
                      $getAccessFlderCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$flId])->get()->count();
                       $acessCls='';
                      if($getAccessFlderCnt>0) //function for folder rename and delete
                      {

                         $getAccessFlder=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$flId])->get();

                         if($getAccessFlder[0]->folder_access==1)
                         {
                             $acessCls="context-menu-one"  ;
                         }
                          



                      }

                    /**chk physically folder exists or not **/
                     
                    echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="'.$acessCls.'" lang='.$imgName.'   id='.$scl_stf_file_id.'>

                      <a href="javascript:" class="flderLinks" onclick="return fnTs('.$scl_stf_file_id.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a> 
                      <span lang='.$imgName.'   id="getClassFlde_'.$scl_stf_file_id.'">'.$chkParentchildvalue->file_name.'</span>
                      <input type="hidden" name="hid_res_'.$scl_stf_file_id.'" id="hid_res_'.$scl_stf_file_id.'" value="'.$phyRes.'">
                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                      </div> 
                      </div>';
                    }
                    else
                    {

                    if($phyRes=='phy@no')
                    {
                      $imgName='';
                      $urlPath='';
                        
                    }
                     
                     $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                     if(strtolower($ext)=='jpg'||strtolower($ext)=='jpeg'||strtolower($ext)=='png'||strtolower($ext)=='gif'||strtolower($ext)=='bmp') 
                      $ic = '<a href="javascript:" onclick="return fnShowImg('.$imgName.','.$scl_stf_file_id.');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                    else if(strtolower($ext)=='pdf')
                    $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                    else if(strtolower($ext)=='doc')
                    $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>'; 
                    else if(strtolower($ext)=='ods' || strtolower($ext)=='xlsx')
                    $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>'; 
                    else if(strtolower($ext)=='ppt' || strtolower($ext)=='pptx' || strtolower($ext)=='odp')
                    $ic = '<a href="'.$urlPath.'" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>'; 
                    else if(strtolower($ext)=='mp3' || strtolower($ext)=='mp4' || strtolower($ext)=='mpeg')
                    $ic = '<a  href="'.$urlPath.'" target="_blank"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>'; 
                    else 
                    $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';

                    echo '<div class="col-sm-2 mb-3p" id="2">
                    <div style="text-align: center;"   lang='.$imgName.' id='.$scl_stf_file_id.' class="context-menu-one">
                    '.$ic.'
                    <span lang='.$chkPathVal.'  id="file_name_area_'.$scl_stf_file_id.'">'.$chkParentchildvalue->file_name.'</span>
                    <input type="hidden" name="hid_res_'.$scl_stf_file_id.'" id="hid_res_'.$scl_stf_file_id.'" value="'.$phyRes.'">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$chkParentchildvalue->file_name.'" style="display: none;">
                    </div>
                    </div>';  


                    }



                   
                  }



                 }
                 else
                 {

                  echo "Empty";

                 }
       

              }

          }
          else
          {

             echo "Staff not mapping in file manager part";
             exit;

          }

          }

          }

  }
public function renameflder(Request $request)
{


         $inputs = $request->all();
         $file_id = $inputs['file_id'];
         $new_file_name = trim(strtolower($inputs['new_name']));
         $old_file_name = trim(strtolower($inputs['old_name']));
         $pathIds=$inputs['pathIds'];

         if($new_file_name =="" && $old_file_name =="" && $file_id ==""  && $pathIds=="" )
         {
        
           echo "Please try again";
           exit;

         }

         $getUserOrginPath=$this->getFileManagerChkValidUser($file_id);
         if($getUserOrginPath=='0')
         {
          echo "Invalide user";
          exit;
         }
         $chkPhysicallyFlderExit=$this->getFileManagerPathWithIds($pathIds);
         if($chkPhysicallyFlderExit !='0'){

          $old_path=base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit.$old_file_name;
          $new_path=base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit.$new_file_name;

         if (file_exists($old_path)) {

            if (!file_exists($new_path))  
              {

                rename($old_path, $new_path);
                $updateFileName=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->update(['file_name'=>$new_file_name]);
                echo "success";
                exit;

              }
              else
              {
                   echo "new folder name already exists";
                   exit;
              } 

          }
          else
          {

             echo "old folder name not exists";
             exit;
          }

         }
         
}

 public function rename(Request $request) { //Rename for file 


         $inputs = $request->all();
         $file_id = $inputs['file_id'];
         $new_file_name = trim(strtolower($inputs['new_name']));
         $old_file_name = trim(strtolower($inputs['old_name']));
         $pathIds=$inputs['pathIds'];
         $data = array('file_name' => $new_file_name);

         $getUserOrginPath=$this->getFileManagerChkValidUser($file_id);

         if($getUserOrginPath=='0')
         {
          echo "Invalide user";
          exit;
         }

         $chkPhysicallyFlderExit=$this->getFileManagerPathWithIds($pathIds);
         if($chkPhysicallyFlderExit !='0'){

          $old_path=base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit.$old_file_name;
          $new_path=base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit.$new_file_name;

          if (file_exists($old_path)) {

            if (!file_exists($new_path))  
              {


                rename($old_path, $new_path);

                $updateFileName=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->update(['file_name'=>$new_file_name]);

                  echo "success";
                   exit;

              }
              else
              {

                   echo "new file name already exists";
                   exit;

              } 



          }
          else
          {

             echo "old file name not exists";
             exit;
          }

         }
         else
         {

          echo "Invalid file path";
          exit;
         }

        
    }

public function getBrdcrum(Request $request){

   $inputs=$request->all();
   $fid=$inputs['fid'];
   if($fid>0)
   {
       echo $this->getFileManagerPath($fid);
   }
   else
   {
    echo "";
   }
}

    public function ajaxfm1(Request $request) {

      $getStffCode=auth()->guard('admin')->user()->email;

      $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
      if($chkStfMapCnt==0)
      {
        echo "Permission denied";
        exit;
      }
      else
      {
 
        $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
          $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

        $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

        if($chkSubMasterCnt==0)
        {
          
          echo "Staff yet not mapped subject";
          exit;

        }
        else{

         $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

         if($chkActiveCnt[0]->active==0)
         {

            echo "Staff mode is inactive";
            exit;

         }
          
           
           $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;
           
          $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
        }

      }

       $inputs=$request->all();
             
       $flname=$inputs['flname'];

       if($flname=="")
       {
           $path = base_path()."/public/uploads/file_manager/$staffNameFolder";
       }
       else
       { 

             $path = base_path()."/public/uploads/file_manager/$staffNameFolder".$flname;
         
       }
            
              if(1) {

              $files = array();


              if(file_exists($path)){

              foreach(scandir($path) as $f) {

              if(!$f || $f[0] == '.') {
                continue; // Ignore hidden files
              }

              if(is_dir($path . '/' . $f)) {

                $fileName="'".$path.$f."'";
                $filepathSplit=str_replace(base_path()."/public/uploads/file_manager/$staffNameFolder",'',$path.'/'.$f);
                $fileName="'".$filepathSplit."'";
               echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
              <a href="javascript:" class="flderLinks"  onclick="return fnTs('.$fileName.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br></a>
              <span>'.$f.'</span>
              <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$f.'" style="display: none;">
              </div>
              </div>';

              }

              else {


              $dir='';
              $file = $path . $f;
              if($flname=="")
              {
                $filename = url('uploads/file_manager/'.$staffNameFolder.'/'.$f);
                $imgName="'".$staffNameFolder.$f."'";
              }
              else
              { 

                $filename = url('uploads/file_manager/'.$staffNameFolder.$flname.'/'.$f);
                $imgName="'".$staffNameFolder.$flname.'/'.$f."'";

              }


              $ext = pathinfo($file, PATHINFO_EXTENSION);
              if(strtolower($ext)=='jpg'||strtolower($ext)=='jpeg'||strtolower($ext)=='png'||strtolower($ext)=='gif'||strtolower($ext)=='bmp') 
              $ic = '<a href="javascript:" onclick="return fnShowImg('.$imgName.');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
              else if(strtolower($ext)=='pdf')
              $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
              else if(strtolower($ext)=='doc')
              $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>'; 
              else if(strtolower($ext)=='ods' || strtolower($ext)=='xlsx')
              $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>'; 
              else if(strtolower($ext)=='ppt' || strtolower($ext)=='pptx' || strtolower($ext)=='odp')
              $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>'; 
              else if(strtolower($ext)=='mp3' || strtolower($ext)=='mp4' || strtolower($ext)=='mpeg')
              $ic = '<a href="'.$filename.'" target="_blank"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>'; 
              else 
              $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';

              echo '<div class="col-sm-2 mb-3p" id="">
              <div style="text-align: center;" class="context-menu-one">
              '.$ic.'
              <span>'.$f.'</span>
              <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="'.$f.'" style="display: none;">
              </div>
              </div>';


              }
              }

              }

              }

}


     public function create_folder(Request $request) {

      $rowId=$request->fldrId; 
      $linkPathIds=$request->allId;
      $folderNameParam=strtolower(trim($request->folder_name));

      if($rowId>0 && $folderNameParam !="")
      {


          $getStffCode=auth()->guard('admin')->user()->email;
          $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
          if($chkStfMapCnt==0)
          {
            echo "Permission denied";
            exit;
          }
          else
          {

            $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
            $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

            $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

            if($chkSubMasterCnt==0)
            {

            echo "Staff yet not mapped subject";
            exit;

            }
            else{


              $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

          if($chkActiveCnt[0]->active==0)
          {

            echo "Staff mode is inactive";
            exit;

          }
          

          $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;
           
          $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;

              $chkParentchildCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$rowId,'file_type'=>'folder','folder_access'=>1])->get()->count();
 
              if($chkParentchildCnt>0)
              {

                $getParentIdCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$rowId,'file_type'=>'folder'])->get()->count();

                  //$getParentIdval=$getParentId[0]->parent_id;
 
                if($getParentIdCnt>0) 
                {


                  $getParentId=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$rowId,'file_type'=>'folder'])->get();

                  $getParentIdval=$getParentId[0]->parent_id;

                 
                   $chkFolderNameExists=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'parent_id'=>$getParentIdval,'file_type'=>'folder'])->get();

                  
                   foreach ($chkFolderNameExists as $key => $Ckvalue) {

                    $foldrName=strtolower($Ckvalue->file_name); 

                     
                    if($folderNameParam==$foldrName)
                    {
                      echo "Folder name already exists";
                      exit;
                    }
 

                   }
              // echo "ok";

                }


                /***Physically folder name exits or not ***/

                  //$chkPhysicallyFlderExit=$this->getFileManagerPath($rowId);
                  $chkPhysicallyFlderExit=$this->getFileManagerPathWithIds($linkPathIds);
                 
                  
 
                 if($chkPhysicallyFlderExit !='0')
                 {

                   

                    $destinationPath = base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit.$folderNameParam;

                    

                      if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);

                      $chkParentchildval=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$rowId,'file_type'=>'folder'])->get();

                           $scl_stf_file_id=$chkParentchildval[0]->scl_stf_file_id;
                           $scl_stf_id=$chkParentchildval[0]->scl_stf_id;
                           $scl_stf_sub_id=$chkParentchildval[0]->scl_stf_sub_id;
                           
                           $sch_cls_id=$chkParentchildval[0]->sch_cls_id;
                           $sec_id=$chkParentchildval[0]->sec_id;
                           $sub_id=$chkParentchildval[0]->sub_id;
                           $parent_id=$chkParentchildval[0]->parent_id ;
                           $academic_year=$chkParentchildval[0]->academic_year;

                           $InserFlder=SchFileManagerModel::insert(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$sub_id,'parent_id'=>$scl_stf_file_id,'academic_year'=>$academic_year,'file_type'=>'folder','file_name'=>$folderNameParam,'folder_access'=>1]);

 

                      echo   "success";

                      } else {
                      echo  'fail';
                      }
                 }
                 else
                 {

                  echo "Folder creation failed";
                  exit;
                 }


              
 

              }
              else
              {
                echo "Folder creation failed or denied";
                exit;
              }



               
            }



          }
           
         
 

      }
      else
      {

         echo "Folder creation denied";

      }
           
    
    }

   public function delete(Request $request) {
        $inputs = $request->all();
        $c_path = $inputs['c_path'];
        $file_id= $inputs['file_id'];  
        $FiledelPath=$inputs['FiledelPath'];
        $file_name = trim($inputs['file_name']);
        $getUserOrginPath=$this->getFileManagerChkValidUser($file_id);
        if($getUserOrginPath==1)
        {

         $dir = base_path().'/public/uploads/file_manager/'.$FiledelPath;
          $chkFileOrFolderCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->get()->count();
          $fileorFlder='';

          if($chkFileOrFolderCnt==1)
          { 
              
              $chkFileOrFolder=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->get();
 
              if($chkFileOrFolder[0]->file_type=='folder')
              {
 
  
                 if(is_dir($dir)) 
                 {
                 $this->rrmdir($dir);

                 $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
                 $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
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
              else
              {


                  $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
                  $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
                  echo "success";
                  exit;

              }
              
                }
                else
                {



                  $dir = base_path().'/public/uploads/file_manager/'.$FiledelPath;
                  if(is_dir($dir))
                  {
                      unlink($dir);
                  }

                
                  $DelFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
                  echo "success";
             
                }

          }
          else
          {
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
        else
        {
          echo "Invalid user";
          exit;
        }
       
    }
 function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir."/".$object) == "dir") {
                 $this->rrmdir($dir."/".$object); 
                 // $file = FileManagerModel::where('file_name', $object)->select('file_id')->first();        
                 // $file_id = @$file->file_id; 
                 // FileManagerModel::where('file_id', $file_id)->delete();        
              } else {                
                // $file = FileManagerModel::where('file_name', $object)->select('file_id')->first();        
                // $file_id = @$file->file_id; 
                // FileManagerModel::where('file_id', $file_id)->delete();
                unlink($dir."/".$object);
              }
            }
          }
          reset($objects);
          rmdir($dir);

          
           


        }
    }

      public function create_file(Request $request) {

     
           $inputs=$request->all();
           $curUploadPath=$inputs['current_url']; //file manager Id
           $path_ids=$inputs['path_ids']; //Path ids
           if($curUploadPath>0 && $path_ids !="")
           {



                  $getStffCode=auth()->guard('admin')->user()->email; //Chk Valied user
                  $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
                  if($chkStfMapCnt==0)
                  {
                   echo "Permission denied";
                  exit;
                  }
                   else
                 {

                   $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
                    $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

                   $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

                   if($chkSubMasterCnt==0)
                   {

            

                   }
                   else{


                   $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

                   if($chkActiveCnt[0]->active==0)
                    {

                    echo "Staff mode is inactive";
                    exit;

                   }

                   $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;

                   }
              }

           }
           else
           {

            echo "Invalid request / file upload is  denied";
            exit;
           }

          

            //$chkPhysicallyFlderExit=$this->getFileManagerPath($curUploadPath);
              $chkPhysicallyFlderExit=$this->getFileManagerPathWithIds($path_ids);
           
 //echo $chkPhysicallyFlderExit; exit;
                 if($chkPhysicallyFlderExit !='0')
                  {

                     $destinationPath = base_path().'/public/uploads/file_manager/'.$chkPhysicallyFlderExit;

                   

                    if (file_exists($destinationPath)) {

                      $chkParentchildvalCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$curUploadPath,'file_type'=>'folder'])->get()->count();
                      if($chkParentchildvalCnt==0)
                       {

                         echo "Invalid file path";
                         exit;

                       }

                       $chkParentchildvalAccessCnt=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$curUploadPath,'file_type'=>'folder','folder_access'=>1])->get()->count();

                       if($chkParentchildvalAccessCnt==0) //chk the folder path uploaded is posible
                       {

                        echo "File uploaded denied";
                        exit;
                       }


                      $chkParentchildval=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId,'scl_stf_file_id'=>$curUploadPath,'file_type'=>'folder'])->get();

                           $scl_stf_file_id=$chkParentchildval[0]->scl_stf_file_id;
                           $scl_stf_id=$chkParentchildval[0]->scl_stf_id;
                           $scl_stf_sub_id=$chkParentchildval[0]->scl_stf_sub_id;
                           $sch_cls_id=$chkParentchildval[0]->sch_cls_id;
                           $sec_id=$chkParentchildval[0]->sec_id;
                           $sub_id=$chkParentchildval[0]->sub_id;
                           $parent_id=$chkParentchildval[0]->parent_id ;
                           $academic_year=$chkParentchildval[0]->academic_year;
                           if (!empty($_FILES)) {
                            $tmpFile = $_FILES['file']['tmp_name'];
                            $filename = $destinationPath.$_FILES['file']['name'];       
                            if (!file_exists($filename)) {
                            $actual_filename = time().'-'.strtolower($_FILES['file']['name']);    
                            $filename = $destinationPath.$actual_filename;

                            move_uploaded_file($tmpFile,$filename);
                            } else {
                            $actual_filename = time().'-'.strtolower($_FILES['file']['name']);            
                            $filename = $destinationPath.$actual_filename;

                            move_uploaded_file($tmpFile,$filename);

                            }//chk below physically file upload or not
                            if (file_exists($destinationPath.$actual_filename)) {


                              $InserFlder=SchFileManagerModel::insert(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$sub_id,'parent_id'=>$scl_stf_file_id,'academic_year'=>$academic_year,'file_type'=>'file','file_name'=>$actual_filename]);

                              echo "success";
                              exit;




                            }
                            else
                            {
                              echo "File not uploaded successfully";
                              exit;
                            }





                          }
                          else
                          {
                          
                            echo "Upload file is empty";
                            exit;

                          }





                    }
                    else {


                    echo "Invaid folder path";
                    exit;      


                    }

                      
                 }
                 else
                 {

                  echo "Folder creation failed";
                  exit;
                 }
          
      }
         
    

    public function getuserAccessPath() {

 
        $getStffCode=auth()->guard('admin')->user()->email;

        $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
        if($chkStfMapCnt==0)
        {
            
            return 0;
             exit;
        }
    else
    {

        $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
        $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

        $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

    if($chkSubMasterCnt==0)
    {

     return 0;
     exit;

    }
    else{

     $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

    if($chkActiveCnt[0]->active==0)
    {

      return 0;
      exit;

    }
    return $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
    }


    }

    }
    public function getFileManagerPath($fmRowId)
    {  
 
       $getUrlPathCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fmRowId])->get()->count();
        
       if($getUrlPathCnt>0)
        {
 
          $getUrlPath=SchFileManagerModel::where(['scl_stf_file_id'=>$fmRowId])->get();

         $sch_cls_id=$getUrlPath[0]->sch_cls_id;
         $sec_id=$getUrlPath[0]->sec_id;
         $sub_id=$getUrlPath[0]->sub_id;
         $parentId=$getUrlPath[0]->parent_id;

         if($sch_cls_id==0 && $sec_id==0 && $sub_id==0)
         {

            return 0;
            exit;

         }
         else
         {

           $Path='';
           if($sch_cls_id>0)
           {


              $getSclClsMaster=SchClassModel::where('sch_cls_id',$sch_cls_id)->get();
              if(isset($getSclClsMaster[0]->sch_class) && $getSclClsMaster[0]->sch_class !="" )
              {

                 $Path=$getSclClsMaster[0]->sch_class;
              }

           }

           if($sec_id>0)
            {

               $getSection=SchSectionModel::where(['sec_id'=>$sec_id])->get();

               if(isset($getSection[0]->section_name) && $getSection[0]->section_name !="" )
              {

                 $Path.='/'.$getSection[0]->section_name;
              }


            }

           if($sub_id>0)
           {

            $getSubject=SclSubjectModel::where(['sub_id'=>$sub_id])->get();

               if(isset($getSubject[0]->sub_name) && $getSubject[0]->sub_name !="" )
              {

                   $Path.='/'.$getSubject[0]->sub_name ;
              }


             

            }

             $getRootPath=$this->getuserAccessPath();
             if($getRootPath =='0')
             {
                return 0;
                exit;
             }
             else
             {
                 return  $getRootPath.'/'.$Path ;
             }

 



         } 

       
         
        }
       
    }


    public function getFileManagerPathWithIds($fmRowIds)
    {  


    
       $expIds=explode(',',$fmRowIds);

       if(count($expIds)>0)
       {
           $urlPath='';

           for($i=0;$i<count($expIds);$i++)
           {
  
              $fmRowId=$expIds[$i];


              $getUrlPathCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fmRowId,'file_type'=>'folder'])->get()->count();
              if($getUrlPathCnt>0)
              {

                 $getUrlPath=SchFileManagerModel::where(['scl_stf_file_id'=>$fmRowId])->get() ;

                 $urlPath.=$getUrlPath[0]->file_name.'/';



              }







           }

           $getRootPath=$this->getuserAccessPath();

           if($getRootPath !='0')
           {
                 return $getRootPath.'/'.$urlPath;
                 exit;
           }
           else
           {
            return 0;
              exit;
           }
 
       }
       else
       {

        return 0;
        exit;
       }



   
       
    }
    function getFileManagerChkValidUser($fmRowId)
    {




        $getStffCode=auth()->guard('admin')->user()->email;

        $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
        if($chkStfMapCnt==0)
        {
            
            return 0;
             exit;
        }
    else
    {

        $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
        $scl_stf_id=$getStaffDetails[0]->scl_stf_id;

        $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();

    if($chkSubMasterCnt==0)
    {

     return 0;
     exit;

    }
    else{

     $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  

    if($chkActiveCnt[0]->active==0)
    {

      return 0;
      exit;

    }
    else
    {

          $getUrlPathCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fmRowId,'scl_stf_id'=>$scl_stf_id,])->get()->count();

          if($getUrlPathCnt>0)
          {
            return 1;
            exit;

          }
          else
          {
            return 0;
          }


    }
    //return $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
    }


    }




    }
}