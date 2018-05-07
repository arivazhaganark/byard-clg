<?php

namespace App\Http\Controllers\user;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SclSubjectModel;
use App\Model\backend\SclstaffClassMasterModel;
use App\Model\backend\SclstaffClassMappModel;
use App\Model\backend\SchStaffModel;
use App\Model\backend\SclStfSubMasterModel;
use App\Model\backend\SclStfSubMapModel;
use App\Model\backend\SchFileManagerModel;
use App\Model\backend\SclStudentModel;
use App\Model\backend\Setting;


use Redirect;
use Session;
use DB;
use File;


  class FileManagerSclStudentController extends Controller
 {
   public function index()
   {
      $getSubject=array();
      $getStudentDetails=$this->getStudentDetails();
       if($getStudentDetails['status']==1)
       {


          $roll_no=$getStudentDetails['roll_no'];
          $sch_stu_id=$getStudentDetails['sch_stu_id']; 
          $sch_cls_id=$getStudentDetails['sch_cls_id'];
          $sec_id=$getStudentDetails['sec_id'];
          $academic_year=$getStudentDetails['academic_year'];
          $student_name=$getStudentDetails['student_name'];
          $getSubjectCnt=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get()->count();
          if($getSubjectCnt>0)
          {
             $getSubject=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get();

          }


       }

     $settingCnt=Setting::select('*')->where('id', 1)->count();
    $logoImgPath='';
    if($settingCnt>0)
    {
      $setting=Setting::select('*')->where('id', 1)->get(); 
      $logoImgPath=isset($setting[0]->img_path)?$setting[0]->img_path:'' ;
    } 
        
      return view('user.filemanagerSclStudent',compact('getSubject','logoImgPath'));
   }
   public function getPathWithRowId($fileId)
   {
    $idArr=[]; 
    $returnUrl='';
    $getPathCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fileId])->count();
    if($getPathCnt>0)
    {

      $getPaths=SchFileManagerModel::where(['scl_stf_file_id'=>$fileId])->get();
      $Ids=isset($getPaths[0]->path_folder_ids)?$getPaths[0]->path_folder_ids:'';
      $idArr=explode(',',$Ids);
      if(count($idArr)>0)
      {

        for($k=0;$k<count($idArr);$k++)
        {

          $rId=isset($idArr[$k])?$idArr[$k]:'';
          if($k==0)
          {
            $geturlPaths=SchFileManagerModel::where(['scl_stf_file_id'=>$rId])->get();
            $filePath=isset($geturlPaths[0]->file_path)?$geturlPaths[0]->file_path:''  ;
            $fileName=isset($geturlPaths[0]->file_name)?$geturlPaths[0]->file_name:''  ;
            $returnUrl.=$filePath.$fileName;
          
          }
          else
          {

            $geturlPaths=SchFileManagerModel::where(['scl_stf_file_id'=>$rId])->get();
            $fileName=isset($geturlPaths[0]->file_name)?$geturlPaths[0]->file_name:''  ;
            $returnUrl.='/'.$fileName;
          }

        }

      return $returnUrl;
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

   public function getUrlLangVal($staffId=null,$subId=null,$fldermode=null,$rowId=null)
   {
 
     $langUrl=''; 
     $outArr=[]; 
     if($staffId>0 && $subId>0 && $fldermode!="" && $rowId>0 )
     {

      $getSubjectCnt=SclSubjectModel::where(['sub_id'=>$subId])->count();
      if($getSubjectCnt>0)
      {

        $getSubject=SclSubjectModel::where(['sub_id'=>$subId])->get();
        $outArr[]=$getSubject[0]->sub_name.'_subject_'.$subId;

      }
      else
      {
         return 0;
         exit;
      }

      $getStfNameCnt=SchStaffModel::where(['scl_stf_id'=>$staffId])->count();
      if($getStfNameCnt>0)
      {
          $getStfName=SchStaffModel::where(['scl_stf_id'=>$staffId])->get();
          $outArr[]=$getStfName[0]->staff_name.'staff_'.$staffId;

       
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
     if($fldermode=='staff')
     {
 
       if(count($outArr)>0)
       {
 
         return implode(',',$outArr);
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

        $getlanCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$rowId,'file_type'=>'folder'])->count();
        if($getlanCnt>0)
        {

             $getlan=SchFileManagerModel::where(['scl_stf_file_id'=>$rowId,'file_type'=>'folder'])->get();
             $fPathIds=$getlan[0]->path_folder_ids;
             $exploPath=explode(',',$fPathIds);
             $currentRowVal=$getlan[0]->file_name.'_folder_'.$rowId; 
             if(count($exploPath)==3)
             {
                $outArr[]=$currentRowVal;
             }
             elseif(count($exploPath)>3)
             {
 

              for($kcnt=3;$kcnt<count($exploPath);$kcnt++)
              {

                $masterRId=isset($exploPath[$kcnt])?$exploPath[$kcnt]:0;
                $getlanValCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$masterRId,'file_type'=>'folder'])->count();
                if($getlanValCnt>0)
                {

                   $getlanVal=SchFileManagerModel::where(['scl_stf_file_id'=>$masterRId,'file_type'=>'folder'])->get();
                   $outArr[]=$getlanVal[0]->file_name.'_folder_'.$masterRId;

                }
                else
                {
                  return 0 ;
                  exit;
                }
                


              }

               $outArr[]=$currentRowVal;

             }


            if(count($outArr)>0)
            {

            return implode(',',$outArr);
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

   }
   public function ajaxsearchfm(Request $request)
   {
       $inputs = $request->all();
       $sTxt=trim($inputs['searchTxt']);
       $getStudentDetails=$this->getStudentDetails();
       $classStaffId=[];
       $classStaffIds='';
       $subjectId=[];
       $subjectIds='';
       $searchVal ="'".'%'.$sTxt.'%'."'";

       if($getStudentDetails['status']==1)
       {

          $roll_no=$getStudentDetails['roll_no'];
          $sch_stu_id=$getStudentDetails['sch_stu_id']; 
          $sch_cls_id=$getStudentDetails['sch_cls_id'];
          $sec_id=$getStudentDetails['sec_id'];
          $academic_year=$getStudentDetails['academic_year'];
          $student_name=$getStudentDetails['student_name'];

          $getSubjectCnt=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get()->count();
          if($getSubjectCnt>0)
          {
             $getSubject=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get();
             foreach ($getSubject as $key => $value) {
              $subjectId[]=$value->sub_id;
             }

            $subjectIds=implode(',',$subjectId);
 
          }
  
          $getStaffClassCnt=SclstaffClassMappModel::leftJoin('at_school_class_staff_master','at_school_class_staff_master.sch_cls_stf_id','=','at_school_class_staff_mapping.sch_cls_stf_id')->where(['at_school_class_staff_mapping.sch_cls_id'=>$sch_cls_id,"at_school_class_staff_mapping.sec_id"=>$sec_id,"at_school_class_staff_master.active"=>1])->count();
          if($getStaffClassCnt>0)
          {
            $getStaffClass=SclstaffClassMappModel::leftJoin('at_school_class_staff_master','at_school_class_staff_master.sch_cls_stf_id','=','at_school_class_staff_mapping.sch_cls_stf_id')->where(['at_school_class_staff_mapping.sch_cls_id'=>$sch_cls_id,"at_school_class_staff_mapping.sec_id"=>$sec_id,"at_school_class_staff_master.active"=>1])->get();
            foreach ($getStaffClass as $key => $value) {
              $classStaffId[]=$value->scl_stf_id;
            }
            
            $classStaffIds=implode(',',$classStaffId);

            if($subjectIds !="" && $classStaffIds !="" )
            {

               $getSearchValCnt=DB::select("SELECT count('scl_stf_file_id') as Cnt FROM  at_school_staff_file_manager WHERE   scl_stf_id  in($classStaffIds) AND sub_id in($subjectIds) AND  active=1 AND file_published=1 AND sec_id=$sec_id AND file_name  like $searchVal ");
               if($getSearchValCnt[0]->Cnt>0)
               {


                $getFolderFile=DB::select("SELECT  scl_stf_file_id, scl_stf_id, scl_stf_sub_id, scl_stf_sub_map_id, sch_cls_id, sec_id, sub_id, file_name, file_type, file_path, path_folder_ids, file_published, file_permission, folder_access, parent_id, academic_year, create_time, active FROM  at_school_staff_file_manager WHERE   scl_stf_id  in($classStaffIds) AND sub_id in($subjectIds) AND  active=1 AND file_published=1 AND sec_id=$sec_id AND file_name  like $searchVal ");

                $viewFileCnt=0;

                 foreach ($getFolderFile as $key => $Fldervalue) 
                 {
                    
                    $sch_cls_id=$Fldervalue->sch_cls_id;
                    $pathIds=$Fldervalue->path_folder_ids;
                    $sec_id=$Fldervalue->sec_id;
                    $subId=$Fldervalue->sub_id;
                    $staffId=$Fldervalue->scl_stf_id;
                    $rowId=$Fldervalue->scl_stf_file_id;
                    $getPaths=$this->getPathWithRowId($rowId);
                    $phyRes='';
                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                    $fldermode="'staffFileInner'";
                    $urlMode="staffFileInner";


                      if ($Fldervalue->file_type == 'folder') 
                        {  
                        $expPathIds=explode(',',$pathIds);
                        if(count($expPathIds)==2)
                        {
                        $fldermode="'staff'";
                        $urlMode="staff";

                        }

                        $getLangUrlVal=$this->getUrlLangVal($staffId,$subId,$urlMode,$rowId);

                        $langVal="'".$getLangUrlVal."'";

                        if (file_exists($chkPath) && $getLangUrlVal !='0' ) 
                        {

                        $viewFileCnt++;
                        echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                        <a href="javascript:" class="flderLinks" onclick="return fnTsSearch(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.','.$langVal.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                        <span id="getClassFlde_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                        <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="' . $phyRes . '">
                        <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                        </div>
                        </div>';
                        }
                  }
                  else {

                     
                        //   $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                            $imgName = "'" . $getPaths . "'";
                          $urlPath=url('uploads/file_manager/'.$getPaths.'/'.$Fldervalue->file_name)  ;
                          $imgName = "'" .$getPaths.'/'. $Fldervalue->file_name . "'";

                          if (file_exists($chkPath)) {
                           $viewFileCnt++;

                           $chkPathVal="''";

                          $ext = pathinfo($Fldervalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $rowId . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                          else
                          if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
                        else
                          if (strtolower($ext) == 'mp3')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';

                          else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;" lang=' . $imgName . ' id=' . $rowId . ' data="fileDel" >
                          ' . $ic . '
                          <span class="aliSearchCls" lang=' . $chkPathVal . '   id="file_name_area_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                          <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $Fldervalue->file_name . '" style="display: none;">
                          </div>
                          </div>';
                           }

                           }






                 }

                 if($viewFileCnt==0)
                 {

                    echo "Search result is empty";
                    exit;
                 }





               }
               else
               {

                 echo "Search result is empty";
                 exit;


               }

              
            }
            else
            {

              echo "No file/folder found";
              exit;

            }

            // $getSearchValCnt=DB::select("SELECT count(*) as Cnt FROM  at_college_school_file_manager WHERE   scl_stf_id  in($classStaffIds) AND sub_id in($subjctIds) AND  active=1 AND file_published=1 AND file_name  like $searchVal ");

            


            //echo "<pre>";print_r($classId);

          }
          else
          {


          }

           

          /* $classId = SclstaffClassMappModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_staff_mapping.sch_cls_id')->where('at_school_class_staff_mapping.sch_cls_stf_id',$id )->groupBy('at_school_class_staff_mapping.sch_cls_id')->orderBy('at_school_class_staff_mapping.sch_cls_id')->get(array('at_school_class_staff_mapping.sch_cls_id','at_school_class_master.sch_class',DB::raw('""  as sectionVal')));*/

          //echo "<pre>"; print_r($getStudentDetails);




       }
       else
       {
          echo $getStudentDetails['error'];
          exit;
       }

   }

   public function getSearchFilePath($ids){


    $chkFileManagerCnt = SchFileManagerModel::where(['scl_stf_file_id' => $ids])->count() ;
    if($chkFileManagerCnt>0)
    {
      $pathValues='';
      $getResult=[]; 
      $chkFileManager = SchFileManagerModel::where(['scl_stf_file_id' => $ids])->get() ;
      foreach ($chkFileManager as $key => $value) {

        $getPathIds=$value->path_folder_ids;
        $getFileName=$value->file_name;
        if($getPathIds=="")
        {
            $pathValues=$getFileName.'_'.$ids;
        }
        else
        {
          $explodeVal=explode(',',$getPathIds);
          if(count($explodeVal)>0)
          {

            for($i=0;$i<count($explodeVal);$i++)
            {
               $getId=$explodeVal[$i];
               $chkPathFileCnt = SchFileManagerModel::where(['scl_stf_file_id' => $getId])->count() ;
               if($chkPathFileCnt>0)
               {

                $chkPathFile = SchFileManagerModel::where(['scl_stf_file_id' => $getId])->get() ;
                $fname=$chkPathFile[0]->file_name;
                $getResult[]=$fname.'_'.$getId;

               }
            }
            if(count($getResult)>0)
            {
                $pathVal=implode(',',$getResult);
                $pathValues=$pathVal.','.$getFileName.'_'.$ids;
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

     
      }

      return $pathValues;
      exit;

    }
    else
    {
      return 0;
      exit;
    }




  }

   public function ajaxsearchfmOld(Request $request)
   {

       $inputs = $request->all();
       $fileRowId = $inputs['fileRowId'];
       $pathIds = $inputs['pathIds'];
       $folderMode=$inputs['folderMode'];
       $cls_id=$inputs['cls_id'];
       $subId=$inputs['subId'];
       $staffId=$inputs['staffId'];
       $sTxt=trim($inputs['searchTxt']);

        

       $getStudentDetails=$this->getStudentDetails();
       if($getStudentDetails['status']==1)
       {

          $roll_no=$getStudentDetails['roll_no'];
          $sch_stu_id=$getStudentDetails['sch_stu_id']; 
          $sch_cls_id=$getStudentDetails['sch_cls_id'];
          $sec_id=$getStudentDetails['sec_id'];
          $academic_year=$getStudentDetails['academic_year'];
          $student_name=$getStudentDetails['student_name'];

          if($fileRowId==0)
          {

              $getSubjectCnt=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get()->count();
              if($getSubjectCnt>0)
              {
                 $getSubject=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get();
                 foreach ($getSubject as $key => $value) {
                  $phyRes='';
                  $sub_id=$value->sub_id;
                  $fldermode="'staff'";
                  $staffId=0;
                  $rowId=$value->sub_id;

                    echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                    <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$sub_id.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                    <span id="getClassFlde_' . $sub_id . '">' . $value->sub_name . '</span>
                    <input type="hidden" name="hid_res_' . $sub_id . '" id="hid_res_' . $sub_id . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                    </div>
                    </div>';
                   
                 }
                




              }
              else
              {
              echo "Subject is empty";
              exit;
              }



          }
          elseif($folderMode=='staff')
          {

              $getStaff=$this->getStaffSubjectFolderSearch($sch_cls_id,$sec_id,$subId,'',$sTxt);
               
              if($getStaff['status']==1)
              {

                foreach ($getStaff['output'] as $key => $value) {

                   $staffName=$value->staff_name;
                   $fldermode="'staffFile'";
                   $staffId=$value->scl_stf_id;
                   $rowId=$value->scl_stf_id;
                   $phyRes='';

                     echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                    <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                    <span id="getClassFlde_' . $staffId . '">' . $staffName . '</span>
                    <input type="hidden" name="hid_res_' . $staffId . '" id="hid_res_' . $staffId . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                    </div>
                    </div>';



                 
                }

              }
              else
              {
                echo $getStaff['error'] ;
                exit;
              }


                //echo "<pre>";print_r($getStaff);


          }
          elseif($folderMode=='staffFile')
          {
                  $viewFileCnt=0;
                  $getPaths=$this->getFileManagerPathWithIds($pathIds,$sch_cls_id,$sec_id,$subId,$staffId);
                   $getStaff=$this->getStaffSubjectFolder($sch_cls_id,$sec_id,$subId,$staffId);
                   
                    if($getStaff['status']==1)
                    {

                      $scl_stf_sub_id=isset($getStaff['output'][0]->scl_stf_sub_id)?$getStaff['output'][0]->scl_stf_sub_id:'';

                      $getSubMasterFlderIdCnt=SchFileManagerModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>0,'file_type'=>'folder','active'=>1])->get()->count();
                        if($getSubMasterFlderIdCnt>0)
                        {
                        $phyRes='';
                          $getSubMasterFlderId=SchFileManagerModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>0,'file_type'=>'folder','active'=>1])->get() ;
                         $getSubMasterParentId=isset($getSubMasterFlderId[0]->scl_stf_file_id)?$getSubMasterFlderId[0]->scl_stf_file_id:0;

                         $getStaffFileManagerCnt=SchFileManagerModel::where(['scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$subId,'file_type'=>'folder','active'=>1,'parent_id'=>$getSubMasterParentId])->get()->count();

                           if($getStaffFileManagerCnt>0)
                           {

                             $getStaffFileManager=SchFileManagerModel::where(['scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$subId,'file_type'=>'folder','active'=>1,'parent_id'=>$getSubMasterParentId])->get();

                             $folderSubRowid=isset($getStaffFileManager[0]->scl_stf_file_id)?$getStaffFileManager[0]->scl_stf_file_id:0;
                             $getFolderFileCnt=SchFileManagerModel::where(['parent_id'=>$folderSubRowid,'active'=>1])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
                             if($getFolderFileCnt>0)
                             {
                              $getFolderFile=SchFileManagerModel::where(['parent_id'=>$folderSubRowid,'active'=>1])->where('file_name', 'like', '%' . $sTxt . '%')->get();

                                foreach ($getFolderFile as $key => $Fldervalue) {

                                  $rowId=$Fldervalue->scl_stf_file_id;
                                  $phyRes='';
                                  $fldermode="'staffFileInner'";


                                  if ($Fldervalue->file_type == 'folder') {

                                   


                                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;

                                      if (file_exists($chkPath)) {

                                     $viewFileCnt++;
                                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                <span id="getClassFlde_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                                <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="' . $phyRes . '">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';
                              }
                  }
                  else {

                     
                          $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                          $imgName = "'" . $getPaths . "'";
                          $urlPath=url('uploads/file_manager/'.$getPaths.'/'.$Fldervalue->file_name)  ;
                          $imgName = "'" .$getPaths.'/'. $Fldervalue->file_name . "'";

                          if (file_exists($chkPath)) {
                            $viewFileCnt++;

                          $chkPathVal='';

                          $ext = pathinfo($Fldervalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $rowId . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                          else
                          if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
                        else
                          if (strtolower($ext) == 'mp3')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';

                          else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;" lang=' . $imgName . ' id=' . $rowId . ' data="fileDel" >
                          ' . $ic . '
                          <span lang=' . $chkPathVal . '   id="file_name_area_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                          <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $Fldervalue->file_name . '" style="display: none;">
                          </div>
                          </div>';

                          }

                             }

                             if( $viewFileCnt==0)
                             {

                              echo "No file/folder found";
                              exit;
                             }

                                  
                                }


                             }
                             else
                             {
                                echo "No file/folder found";
                                exit;

                             }




                           }
                           else
                           {

                             echo "No file/folder found";
                             exit;

                           }


                        }
                        else
                        {

                          echo "No file/folder found";
                          exit;

                        }

                    }
                    else
                    {

                    echo $getStaff['error'] ;
                    exit;


                    }

          }
          elseif($folderMode=='staffFileInner')
          {
             $viewFileCnt=0;
             $getPaths=$this->getFileManagerPathWithIds($pathIds,$sch_cls_id,$sec_id,$subId,$staffId);

             
                  $getFolderFileCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fileRowId,'active'=>1,'file_type'=>'folder'])->get()->count();
                 if($getFolderFileCnt>0)
                 {
                  $getFolderInnerFileCnt=SchFileManagerModel::where(['parent_id'=>$fileRowId,'active'=>1])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
                  if($getFolderInnerFileCnt>0)
                  {
                      
                    $getFolderInnerFile=SchFileManagerModel::where(['parent_id'=>$fileRowId,'active'=>1])->where('file_name', 'like', '%' . $sTxt . '%')->get();



                        foreach ($getFolderInnerFile as $key => $Fldervalue) {

                                  $rowId=$Fldervalue->scl_stf_file_id;
                                  $phyRes='';
                                  $fldermode="'staffFileInner'";


                                  if ($Fldervalue->file_type == 'folder') {


                                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;

                                    if (file_exists($chkPath)) {
                                      $viewFileCnt++;

                                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                <span id="getClassFlde_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                                <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="' . $phyRes . '">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';

                              }


                  }
                  else {
                     
                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                          $imgName = "'" . $getPaths . "'";
                          $urlPath=url('uploads/file_manager/'.$getPaths.'/'.$Fldervalue->file_name)  ;
                          $imgName = "'" .$getPaths.'/'. $Fldervalue->file_name . "'";

                          if (file_exists($chkPath)) {

                           $viewFileCnt++;

                          $chkPathVal='';

                          $ext = pathinfo($Fldervalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $rowId . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                          else
                          if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
                        else
                          if (strtolower($ext) == 'mp3')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';

                          else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;" lang=' . $imgName . ' id=' . $rowId . ' data="fileDel" >
                          ' . $ic . '
                          <span lang=' . $chkPathVal . '   id="file_name_area_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                          <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $Fldervalue->file_name . '" style="display: none;">
                          </div>
                          </div>';
                  }
                       }

                        if( $viewFileCnt==0)
                             {

                              echo "No file/folder found";
                              exit;
                             }


                                  
                                }





                  }
                  else
                  {

                     echo "No file/folder found";
                  }



                }
                else{

                   echo "No file/folder found";

                }


          }

          


       }
       else
       {
 
        echo $getStudentDetails['error'];

       }

       

   }
   public function ajaxfm(Request $request)
    {

       $inputs = $request->all();
       $fileRowId = $inputs['fileRowId'];
       $pathIds = $inputs['pathIds'];
       $folderMode=$inputs['folderMode'];
       $cls_id=$inputs['cls_id'];
       $subId=$inputs['subId'];
       $staffId=$inputs['staffId'];

        

       $getStudentDetails=$this->getStudentDetails();
       if($getStudentDetails['status']==1)
       {

          $roll_no=$getStudentDetails['roll_no'];
          $sch_stu_id=$getStudentDetails['sch_stu_id']; 
          $sch_cls_id=$getStudentDetails['sch_cls_id'];
          $sec_id=$getStudentDetails['sec_id'];
          $academic_year=$getStudentDetails['academic_year'];
          $student_name=$getStudentDetails['student_name'];

          if($fileRowId==0)
          {

              $getSubjectCnt=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get()->count();
              if($getSubjectCnt>0)
              {
                 $getSubject=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1])->get();
                 foreach ($getSubject as $key => $value) {
                  $phyRes='';
                  $sub_id=$value->sub_id;
                  $fldermode="'staff'";
                  $staffId=0;
                  $rowId=$value->sub_id;

                    echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                    <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$sub_id.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                    <span id="getClassFlde_' . $sub_id . '">' . $value->sub_name . '</span>
                    <input type="hidden" name="hid_res_' . $sub_id . '" id="hid_res_' . $sub_id . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                    </div>
                    </div>';
                   
                 }
                




              }
              else
              {
              echo "Subject is empty";
              exit;
              }



          }
          elseif($folderMode=='staff')
          {

              $getStaff=$this->getStaffSubjectFolder($sch_cls_id,$sec_id,$subId);
              if($getStaff['status']==1)
              {

                foreach ($getStaff['output'] as $key => $value) {

                   $staffName=$value->staff_name;
                   $fldermode="'staffFile'";
                   $staffId=$value->scl_stf_id;
                   $rowId=$value->scl_stf_id;
                   $phyRes='';

                     echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                    <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                    <span id="getClassFlde_' . $staffId . '">' . $staffName . '</span>
                    <input type="hidden" name="hid_res_' . $staffId . '" id="hid_res_' . $staffId . '" value="' . $phyRes . '">
                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                    </div>
                    </div>';



                 
                }

              }
              else
              {
                echo $getStaff['error'] ;
                exit;
              }


                //echo "<pre>";print_r($getStaff);


          }
          elseif($folderMode=='staffFile')
          {
                  $viewFileCnt=0;
                  $viewFolderCnt=0;
                  $getPaths=$this->getFileManagerPathWithIds($pathIds,$sch_cls_id,$sec_id,$subId,$staffId);
                  $getStaff=$this->getStaffSubjectFolder($sch_cls_id,$sec_id,$subId,$staffId);
                    if($getStaff['status']==1)
                    {

                      $scl_stf_sub_id=isset($getStaff['output'][0]->scl_stf_sub_id)?$getStaff['output'][0]->scl_stf_sub_id:'';

                      $getSubMasterFlderIdCnt=SchFileManagerModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>0,'file_type'=>'folder','active'=>1])->get()->count();
                        if($getSubMasterFlderIdCnt>0)
                        {
                        $phyRes='';
                          $getSubMasterFlderId=SchFileManagerModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>0,'file_type'=>'folder','active'=>1])->get() ;
                         $getSubMasterParentId=isset($getSubMasterFlderId[0]->scl_stf_file_id)?$getSubMasterFlderId[0]->scl_stf_file_id:0;

                         $getStaffFileManagerCnt=SchFileManagerModel::where(['scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$subId,'file_type'=>'folder','active'=>1,'parent_id'=>$getSubMasterParentId])->get()->count();

                           if($getStaffFileManagerCnt>0)
                           {

                             $getStaffFileManager=SchFileManagerModel::where(['scl_stf_id'=>$staffId,'sch_cls_id'=>$sch_cls_id,'sec_id'=>$sec_id,'sub_id'=>$subId,'file_type'=>'folder','active'=>1,'parent_id'=>$getSubMasterParentId])->get();

                             $folderSubRowid=isset($getStaffFileManager[0]->scl_stf_file_id)?$getStaffFileManager[0]->scl_stf_file_id:0;
                             $getFolderFileCnt=SchFileManagerModel::where(['parent_id'=>$folderSubRowid,'active'=>1,'file_published'=>1])->get()->count();
                             if($getFolderFileCnt>0)
                             {
                              $getFolderFile=SchFileManagerModel::where(['parent_id'=>$folderSubRowid,'active'=>1,'file_published'=>1])->get();

                                foreach ($getFolderFile as $key => $Fldervalue) {

                                  $rowId=$Fldervalue->scl_stf_file_id;
                                  $phyRes='';
                                  $fldermode="'staffFileInner'";


                                  if ($Fldervalue->file_type == 'folder') {


                                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;

                                      if (file_exists($chkPath) ) { 

                                        //if($Fldervalue->file_published==1){

                                        $viewFileCnt++;
 
                                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                <span class="aliSearchCls" id="getClassFlde_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                                <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="' . $phyRes . '">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';

                              //}
                              }
                  }
                  else {

                     
                          $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                          $imgName = "'" . $getPaths . "'";
                          $urlPath=url('uploads/file_manager/'.$getPaths.'/'.$Fldervalue->file_name)  ;
                          $imgName = "'" .$getPaths.'/'. $Fldervalue->file_name . "'";

                          if (file_exists($chkPath)) {   
                           //if($Fldervalue->file_published==1){ 
                              $viewFileCnt++;

                          $chkPathVal='';

                          $ext = pathinfo($Fldervalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $rowId . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                          else
                          if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
                        else
                          if (strtolower($ext) == 'mp3')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';

                          else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;" lang=' . $imgName . ' id=' . $rowId . ' data="fileDel" >
                          ' . $ic . '
                          <span class="aliSearchCls" lang=' . $chkPathVal . '   id="file_name_area_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                          <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $Fldervalue->file_name . '" style="display: none;">
                          </div>
                          </div>';

                          //}
                         }
                             }

                               

                                  
                                }

                                if( $viewFileCnt==0 )
                             {

                               echo "No file/folder found";
                                exit;
                             }


                             }
                             else
                             {
                                echo "No file/folder found";
                                exit;

                             }




                           }
                           else
                           {

                             echo "No file/folder found";
                             exit;

                           }


                        }
                        else
                        {

                          echo "No file/folder found";
                          exit;

                        }

                    }
                    else
                    {

                    echo $getStaff['error'] ;
                    exit;


                    }

          }
          elseif($folderMode=='staffFileInner')
          { 
            $viewFileCnt=0;
             $getPaths=$this->getFileManagerPathWithIds($pathIds,$sch_cls_id,$sec_id,$subId,$staffId);

             
                  $getFolderFileCnt=SchFileManagerModel::where(['scl_stf_file_id'=>$fileRowId,'active'=>1,'file_type'=>'folder'])->get()->count();
                 if($getFolderFileCnt>0)
                 {
                  $getFolderInnerFileCnt=SchFileManagerModel::where(['parent_id'=>$fileRowId,'active'=>1,'file_published'=>1])->get()->count();
                  if($getFolderInnerFileCnt>0)
                  {
                      
                    $getFolderInnerFile=SchFileManagerModel::where(['parent_id'=>$fileRowId,'active'=>1,'file_published'=>1])->get();



                        foreach ($getFolderInnerFile as $key => $Fldervalue) {

                                  $rowId=$Fldervalue->scl_stf_file_id;
                                  $phyRes='';
                                  $fldermode="'staffFileInner'";


                                  if ($Fldervalue->file_type == 'folder') {


                                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;

                                    if (file_exists($chkPath)) {
                                     $viewFileCnt++;
                                echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $sch_cls_id . ','.$sec_id.','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                <span id="getClassFlde_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                                <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="' . $phyRes . '">
                                <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                </div>
                                </div>';

                              }


                  }
                  else {
                     
                    $chkPath = base_path() . "/public/uploads/file_manager/" . $getPaths.'/'.$Fldervalue->file_name;
                          $imgName = "'" . $getPaths . "'";
                          $urlPath=url('uploads/file_manager/'.$getPaths.'/'.$Fldervalue->file_name)  ;
                          $imgName = "'" .$getPaths.'/'. $Fldervalue->file_name . "'";

                          if (file_exists($chkPath)) {
                             $viewFileCnt++;


                          $chkPathVal='';

                          $ext = pathinfo($Fldervalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $rowId . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
                          else
                          if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
                          else
                          if (strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';
                        else
                          if (strtolower($ext) == 'mp3')  $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-file-audio-o fa-3x" aria-hidden="true"></i></a><br/>';
                        else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;" lang=' . $imgName . ' id=' . $rowId . ' data="fileDel" >
                          ' . $ic . '
                          <span class="aliSearchCls" lang=' . $chkPathVal . '   id="file_name_area_' . $rowId . '">' . $Fldervalue->file_name . '</span>
                          <input type="hidden" name="hid_res_' . $rowId . '" id="hid_res_' . $rowId . '" value="">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $Fldervalue->file_name . '" style="display: none;">
                          </div>
                          </div>';
                  }
                       }
                          if( $viewFileCnt==0)
                             {

                              echo "No file and folder found,";
                              exit;
                             }

                                  
                                }





                  }
                  else
                  {

                     echo "No file/folder found";
                  }



                }
                else{

                   echo "No file/folder found";

                }


          }

          


       }
       else
       {
 
        echo $getStudentDetails['error'];

       }

       

    }
    public function staffSelect(Request $request)
    {
        $inputs = $request->all();
        $subSelectId = $inputs['subSelectId'];
        $getStudentDetails=$this->getStudentDetails();
        $result['error']=0;
       if($getStudentDetails['status']==1)
       {
          $roll_no=$getStudentDetails['roll_no'];
          $sch_stu_id=$getStudentDetails['sch_stu_id']; 
          $sch_cls_id=$getStudentDetails['sch_cls_id'];
          $sec_id=$getStudentDetails['sec_id'];
          $academic_year=$getStudentDetails['academic_year'];
          $student_name=$getStudentDetails['student_name'];
          $getSubjectCnt=SclSubjectModel::where(['sch_cls_id'=>$sch_cls_id,'active'=>1,'sub_id'=>$subSelectId])->get()->count();
          if($getSubjectCnt>0)
          {

            $getStaff=$this->getStaffSubjectFolder($sch_cls_id,$sec_id,$subSelectId);
            if($getStaff['status']==1)
            {
               $result['error']=1;
               $result['output']=$getStaff['output'];
            }
         }
       }
      echo json_encode($result);
    }

    public function getStaffSubjectFolderSearch($sch_cls_id=null,$sec_id=null,$subId=null,$scl_stf_id=null,$srTxt=null)
    {

      $stfDetailsArr['status']=0;
      $stfDetailsArr['error']='';

       

      if($scl_stf_id>0)
      {
 
        $chkSubMasterCnt = SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId,'at_school_staff_subject_master.scl_stf_id'=>$scl_stf_id])->where('at_school_staff_master.staff_name', 'like', '%' . $srTxt . '%')->get()->count();

          if($chkSubMasterCnt>0)
          {

            $chkSubMaster= SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId,'at_school_staff_subject_master.scl_stf_id'=>$scl_stf_id])->where('at_school_staff_master.staff_name', 'like', '%' . $srTxt . '%')->get() ;
            $stfDetailsArr['status']=1;
            $stfDetailsArr['output']=$chkSubMaster;
          
          }
          else
          {

             $stfDetailsArr['error']='No staff found';
          }

      }
      else{

        $chkSubMasterCnt = SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId])->where('at_school_staff_master.staff_name', 'like', '%' . $srTxt . '%')->get()->count();

          if($chkSubMasterCnt>0)
          {

            $chkSubMaster= SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId])->where('at_school_staff_master.staff_name', 'like', '%' . $srTxt . '%')->get();
            $stfDetailsArr['status']=1;
            $stfDetailsArr['output']=$chkSubMaster;
          }
          else{

            $stfDetailsArr['error']='No staff found';
          }

        
    }
    return $stfDetailsArr;


    }

    public function getStaffSubjectFolder($sch_cls_id=null,$sec_id=null,$subId=null,$scl_stf_id=null)
    {

      $stfDetailsArr['status']=0;
      $stfDetailsArr['error']='';

      if($scl_stf_id>0)
      {
 
        $chkSubMasterCnt = SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId,'at_school_staff_subject_master.scl_stf_id'=>$scl_stf_id])->get()->count();

          if($chkSubMasterCnt>0)
          {

            $chkSubMaster= SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId,'at_school_staff_subject_master.scl_stf_id'=>$scl_stf_id])->get() ;
            $stfDetailsArr['status']=1;
            $stfDetailsArr['output']=$chkSubMaster;
          
          }
          else
          {

             $stfDetailsArr['error']='No staff found';
          }

      }
      else{

        $chkSubMasterCnt = SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId])->get()->count();

          if($chkSubMasterCnt>0)
          {

            $chkSubMaster= SclStfSubMasterModel::leftJoin('at_school_staff_subject_mapping','at_school_staff_subject_mapping.scl_stf_sub_id','=','at_school_staff_subject_master.scl_stf_sub_id')->leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where(['at_school_staff_subject_mapping.active'=>1,'at_school_staff_master.active'=>1,'at_school_staff_subject_master.active'=>1,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id,'at_school_staff_subject_mapping.sub_id'=>$subId])->get();
            $stfDetailsArr['status']=1;
            $stfDetailsArr['output']=$chkSubMaster;
          }
          else{

            $stfDetailsArr['error']='No staff found';
          }

        
    }
    return $stfDetailsArr; 
  }

//   public function ajaxsearchfm(Request $request)
//   {
//     $getStffCode = auth()->guard('user')->user()->email;
//     $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//     if ($chkStfMapCnt == 0) {
//       echo "Permission denied";
//       exit;
//     }
//     else {
//       $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//       $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
//       $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
//       if ($chkSubMasterCnt == 0) {
//         echo "Staff yet not mapped subject";
//         exit;
//       }
//       else {
//         $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
//         if ($chkActiveCnt[0]->active == 0) {
//           echo "Staff mode is inactive";
//           exit;
//         }

//         $sclStfSubMasterId = $chkActiveCnt[0]->scl_stf_sub_id;
//         $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
//         $chkMappingFileTbl = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId])->get()->count();
//         if ($chkMappingFileTbl > 0) {
//           $inputs = $request->all();
//           $fid = $inputs['file_id'];
//           $sTxt = trim($inputs['search_txt']);
//           $pathIds = $inputs['pathIds'];
//           if ($fid == '') {
//             $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => 0])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
//             if ($chkParentchildCnt > 0) {
//               $chkParentchild = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => 0])->where('file_name', 'like', '%' . $sTxt . '%')->get();
//               $i = 0;
//               foreach($chkParentchild as $key => $chkParentchildvalue) {
//                 $pathIds = $chkParentchildvalue->scl_stf_file_id;
//                 $i++;
//                 $urlPath = '';
//                 $imgName = '';
//                 $scl_stf_file_id = $chkParentchildvalue->scl_stf_file_id;
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
//                                 <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $scl_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
//                                 <span id="getClassFlde_' . $scl_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                                 <input type="hidden" name="hid_res_' . $scl_stf_file_id . '" id="hid_res_' . $scl_stf_file_id . '" value="' . $phyRes . '">
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

//           // Not in root directory

//           {
//             if ($pathIds != "") {
//               $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $fid])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
//               if ($chkParentchildCnt > 0) {
//                 $chkParentchild = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $fid])->where('file_name', 'like', '%' . $sTxt . '%')->get();
//                 $getAccessFlderCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $fid])->get()->count();
//                 $acessCls = '';
//                 if ($getAccessFlderCnt > 0) //function for folder rename and delete
//                 {
//                   $getAccessFlder = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $fid])->get();
//                   if ($getAccessFlder[0]->folder_access == 1) {
//                     $acessCls = "context-menu-one";
//                   }
//                 }

//                 $i = 0;
//                 foreach($chkParentchild as $key => $chkParentchildvalue) {
//                   $i++;
//                   $urlPath = '';
//                   $imgName = '';
//                   $scl_stf_file_id = $chkParentchildvalue->scl_stf_file_id;
//                   $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                   $phyRes = '';
//                   $chkPathVal = '';
//                   if ($getFileManagerPath != '0') {
//                     $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
//                     $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
//                     $chkPathVal = "'" . $chkPath . "'";
//                     $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
//                     if (file_exists($chkPath)) {
//                       $phyRes = 'phy@yes';
//                     }
//                     else {
//                       $phyRes = 'phy@no';
//                     }
//                   }

//                   if ($chkParentchildvalue->file_type == 'folder') {
//                     echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '"  lang=' . $imgName . ' id=' . $scl_stf_file_id . '>

//                                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $scl_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
//                                       <span lang=' . $imgName . '   id="getClassFlde_' . $scl_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                                       <input type="hidden" name="hid_res_' . $scl_stf_file_id . '" id="hid_res_' . $scl_stf_file_id . '" value="' . $phyRes . '">
//                                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                                       </div> 
//                                       </div>';
//                   }
//                   else {
//                     if ($phyRes == 'phy@no') {
//                       $imgName = '';
//                       $urlPath = '';
//                     }

//                     $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
//                     if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $scl_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
//                     else
//                     if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue"></i></a><br/>';
//                     else
//                     if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue"></i></a><br/>';
//                     else
//                     if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue"></i></a><br/>';
//                     else
//                     if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue"></i></a><br/>';
//                     else
//                     if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg')  $ic = '<a href="javascript:" onclick="return show_video(' . $urlPath . ');"><i class="fa fa-video-camera fa-3x font-blue"></i></a><br/>';

//                     else $ic = '<i class="fa fa-file-text-o fa-3x font-blue"></i><br/>';
//                     echo '<div class="col-sm-2 mb-3p" id="2">
//                                           <div style="text-align: center;" lang=' . $imgName . ' id=' . $scl_stf_file_id . ' data="fileDel" class="context-menu-one">
//                                           ' . $ic . '
//                                           <span lang=' . $chkPathVal . '   id="file_name_area_' . $scl_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                                           <input type="hidden" name="hid_res_' . $scl_stf_file_id . '" id="hid_res_' . $scl_stf_file_id . '" value="' . $phyRes . '">
//                                           <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
//                                           </div>
//                                           </div>';
//                   }
//                 }
//               }
//               else {
//                 echo "No search result found";
//                 exit;
//               }
//             }
//             else {
//               echo "Invalid Search";
//               exit;
//             }
//           }
//         }
//         else {
//           echo "Staff not mapping in file manager part";
//           exit;
//         }
//       }
//     }
//   }

  

//   public function ajaxfmOLD(Request $request)
//   {
//     $getStffCode = auth()->guard('user')->user()->email;
//     $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//     if ($chkStfMapCnt == 0) {
//       echo "Permission denied";
//       exit;
//     }
//     else {
//       $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//       $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
//       $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
//       if ($chkSubMasterCnt == 0) {
//         echo "Staff yet not mapped subject";
//         exit;
//       }
//       else {
//         $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
//         if ($chkActiveCnt[0]->active == 0) {
//           echo "Staff mode is inactive";
//           exit;
//         }

//         $sclStfSubMasterId = $chkActiveCnt[0]->scl_stf_sub_id;
//         $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
//         $chkMappingFileTbl = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId])->get()->count();
//         if ($chkMappingFileTbl > 0) {
//           $inputs = $request->all();
//           $flId = $inputs['file_id']; //file manager table primary id
//           $pathIds = $inputs['pathIds'];
//           if ($flId == 0) {
//             /* get Parent folder like School Standard start*/
//             $chkParentCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => 0])->get()->count();
//             if ($chkParentCnt > 0) {
//               $chkParent = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => 0])->orderBy('sch_cls_id')->get();
//               foreach($chkParent as $key => $chkParentValue) {
//                 $sch_cls_id = $chkParentValue->sch_cls_id;
//                 $scl_stf_file_id = $chkParentValue->scl_stf_file_id;
//                 $getSclClsMaster = SchClassModel::where('sch_cls_id', $sch_cls_id)->get();
//                 if (isset($getSclClsMaster[0]->sch_class) && $getSclClsMaster[0]->sch_class != "") {
//                   echo '<div class="col-sm-2 mb-3p" id="25"><a class="brdcum" href=""> </a><div style="text-align: center;">
//                       <a href="javascript:" class="flderLinks"  onclick="return fnTs(' . $scl_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
//                       <span id="getClassFlde_' . $scl_stf_file_id . '">' . $getSclClsMaster[0]->sch_class . '</span>
//                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                       </div>
//                       </div>';
//                 }
//               }
//             }

//             /* get Parent folder like Standard end */
//           }
//           else {
//             $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $flId])->get()->count();
//             if ($chkParentchildCnt > 0) {
//               $chkParentchild = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $flId])->get();
//               $i = 0;
//               foreach($chkParentchild as $key => $chkParentchildvalue) {
//                 $i++;
//                 $urlPath = '';
//                 $imgName = '';
//                 $scl_stf_file_id = $chkParentchildvalue->scl_stf_file_id;

//                 // $getFileManagerPath=$this->getFileManagerPath($flId);

//                 $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                 $phyRes = '';
//                 $chkPathVal = '';
//                 if ($getFileManagerPath != '0') {
//                   $chkPath = base_path() . "/public/uploads/file_manager/" . $getFileManagerPath . $chkParentchildvalue->file_name;
//                   $imgName = "'" . $getFileManagerPath . $chkParentchildvalue->file_name . "'";
//                   $chkPathVal = "'" . $chkPath . "'";
//                   $urlPath = url('uploads/file_manager/' . $getFileManagerPath . $chkParentchildvalue->file_name);
//                   if (file_exists($chkPath)) {
//                     $phyRes = 'phy@yes';
//                   }
//                   else {
//                     $phyRes = 'phy@no';
//                   }
//                 }

//                 if ($chkParentchildvalue->file_type == 'folder') {
//                   $getAccessFlderCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $flId])->get()->count();
//                   $acessCls = '';
//                   if ($getAccessFlderCnt > 0) //function for folder rename and delete
//                   {
//                     $getAccessFlder = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $flId])->get();
//                     if ($getAccessFlder[0]->folder_access == 1) {
//                       $acessCls = "context-menu-one";
//                     }
//                   }

//                   /**chk physically folder exists or not **/
//                   echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '" lang=' . $imgName . '   id=' . $scl_stf_file_id . '>

//                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $scl_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
//                       <span lang=' . $imgName . '   id="getClassFlde_' . $scl_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                       <input type="hidden" name="hid_res_' . $scl_stf_file_id . '" id="hid_res_' . $scl_stf_file_id . '" value="' . $phyRes . '">
//                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                       </div> 
//                       </div>';
//                 }
//                 else {
//                   if ($phyRes == 'phy@no') {
//                     $imgName = '';
//                     $urlPath = '';
//                   }

//                   $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
//                   if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $scl_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block" aria-hidden="true"></i></a>';
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
//                   echo '<div class="col-sm-2 mb-3p" id="2">
//                     <div style="text-align: center;"   lang=' . $imgName . ' id=' . $scl_stf_file_id . ' class="context-menu-one" >
//                     ' . $ic . '
//                     <span lang=' . $chkPathVal . '  id="file_name_area_' . $scl_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
//                     <input type="hidden" name="hid_res_' . $scl_stf_file_id . '" id="hid_res_' . $scl_stf_file_id . '" value="' . $phyRes . '">
//                     <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
//                     </div>
//                     </div>';
//                 }
//               }
//             }
//             else {
//               echo "Folder is Empty";
//             }
//           }
//         }
//         else {
//           echo "Staff not mapping in file manager part";
//           exit;
//         }
//       }
//     }
//   }

  

 

//   public function getBrdcrum(Request $request)
//   {
//     $inputs = $request->all();
//     $fid = $inputs['fid'];
//     if ($fid > 0) {
//       echo $this->getFileManagerPath($fid);
//     }
//     else {
//       echo "";
//     }
//   }

 
//   public function create_folder(Request $request)
//   {
//     $rowId = $request->fldrId;
//     $linkPathIds = $request->allId;
//     $folderNameParam = strtolower(trim($request->folder_name));
//     if ($rowId > 0 && $folderNameParam != "") {
//       $getStffCode = auth()->guard('user')->user()->email;
//       $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//       if ($chkStfMapCnt == 0) {
//         echo "Permission denied";
//         exit;
//       }
//       else {
//         $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//         $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
//         $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
//         if ($chkSubMasterCnt == 0) {
//           echo "Staff yet not mapped subject";
//           exit;
//         }
//         else {
//           $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
//           if ($chkActiveCnt[0]->active == 0) {
//             echo "Staff mode is inactive";
//             exit;
//           }

//           $sclStfSubMasterId = $chkActiveCnt[0]->scl_stf_sub_id;
//           $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
//           $chkParentchildCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $rowId, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
//           if ($chkParentchildCnt > 0) {
//             $getParentIdCnt = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $rowId, 'file_type' => 'folder'])->get()->count();

//             // $getParentIdval=$getParentId[0]->parent_id;

//             if ($getParentIdCnt > 0) {
//               $getParentId = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $rowId, 'file_type' => 'folder'])->get();
//               $getParentIdval = $getParentId[0]->parent_id;
//               $chkFolderNameExists = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'parent_id' => $getParentIdval, 'file_type' => 'folder'])->get();
//               foreach($chkFolderNameExists as $key => $Ckvalue) {
//                 $foldrName = strtolower($Ckvalue->file_name);
//                 if ($folderNameParam == $foldrName) {
//                   echo "Folder name already exists";
//                   exit;
//                 }
//               }

//               // echo "ok";

//             }

//             /***Physically folder name exits or not ***/

//             // $chkPhysicallyFlderExit=$this->getFileManagerPath($rowId);

//             $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($linkPathIds);
//             if ($chkPhysicallyFlderExit != '0') {
//               $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $folderNameParam;
//               if (!file_exists($destinationPath)) {
//                 mkdir($destinationPath, 0777, true);
//                 $chkParentchildval = SchFileManagerModel::where(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $sclStfSubMasterId, 'scl_stf_file_id' => $rowId, 'file_type' => 'folder'])->get();
//                 $scl_stf_file_id = $chkParentchildval[0]->scl_stf_file_id;
//                 $scl_stf_id = $chkParentchildval[0]->scl_stf_id;
//                 $scl_stf_sub_id = $chkParentchildval[0]->scl_stf_sub_id;
//                 $sch_cls_id = $chkParentchildval[0]->sch_cls_id;
//                 $sec_id = $chkParentchildval[0]->sec_id;
//                 $sub_id = $chkParentchildval[0]->sub_id;
//                 $parent_id = $chkParentchildval[0]->parent_id;
//                 $academic_year = $chkParentchildval[0]->academic_year;
//                 $InserFlder = SchFileManagerModel::insert(['scl_stf_id' => $scl_stf_id, 'scl_stf_sub_id' => $scl_stf_sub_id, 'sch_cls_id' => $sch_cls_id, 'sec_id' => $sec_id, 'sub_id' => $sub_id, 'parent_id' => $scl_stf_file_id, 'academic_year' => $academic_year, 'file_type' => 'folder', 'file_name' => $folderNameParam, 'folder_access' => 1]);
//                 echo "success";
//               }
//               else {
//                 echo 'fail';
//               }
//             }
//             else {
//               echo "Folder creation failed";
//               exit;
//             }
//           }
//           else {
//             echo "Folder creation failed or denied";
//             exit;
//           }
//         }
//       }
//     }
//     else {
//       echo "Folder creation denied";
//     }
//   }

  

public function  getStudentDetails()
{
     $stuDetailsArr['status']=0;
     $stuDetailsArr['error']='';
     $getRollNo = auth()->guard('user')->user()->email;

     $getStudentCnt=SclStudentModel::where(['active'=>'1','roll_no'=>$getRollNo])->get()->count();
     if($getStudentCnt>0)
     {
       $getStudent=SclStudentModel::where(['active'=>'1','roll_no'=>$getRollNo])->get();
       $stuDetailsArr['status']=1;
       $stuDetailsArr['roll_no']=$getStudent[0]['roll_no'];
       $stuDetailsArr['sch_stu_id']=$getStudent[0]['sch_stu_id']; 
       $stuDetailsArr['sch_cls_id']=$getStudent[0]['sch_cls_id'];
       $stuDetailsArr['sec_id']=$getStudent[0]['sec_id'];
       $stuDetailsArr['academic_year']=$getStudent[0]['academic_year'];
       $stuDetailsArr['student_name']=$getStudent[0]['student_name'];
     }
     else
     {
       $stuDetailsArr['error']='Inactive student';
     }
     return $stuDetailsArr;

  }

  
  public function getuserAccessPath($staffId)
  {
    //$getStffCode = auth()->guard('user')->user()->email;
    $chkStfMapCnt = SchStaffModel::where(['scl_stf_id' => $staffId, 'active' => 1])->get()->count();
    if ($chkStfMapCnt == 0) {
      return 0;
      exit;
    }
    else {
      $getStaffDetails = SchStaffModel::where(['scl_stf_id' => $staffId, 'active' => 1])->get();

      return $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
      
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

  public function getFileManagerPathWithIds($fmRowIds,$sch_cls_id,$sec_id,$sub_id,$staffId)
  {

      $urlPath='';
      $expIds = explode(',', $fmRowIds); 
      $className='';
      $sectionName='';
      $subjectName='';
      $clsSubSecName='';



        $classNameCnt=SchClassModel::where(['sch_cls_id'=>$sch_cls_id])->get()->count();
        if($classNameCnt>0)
        {
          $getClassName=SchClassModel::where(['sch_cls_id'=>$sch_cls_id])->get() ;
          $className=$getClassName[0]->sch_class;
        }
        $sectionCnt=SchSectionModel::where('sec_id',$sec_id)->get()->count();
        if($sectionCnt>0)
        {
          $getsection=SchSectionModel::where('sec_id',$sec_id)->get();
          $sectionName=$getsection[0]->section_name;
        
        }

        $getSujectCnt=SclSubjectModel::where('sub_id',$sub_id)->get()->count();
        if($getSujectCnt>0)
        {
           $getSuject=SclSubjectModel::where('sub_id',$sub_id)->get();
           $subjectName=$getSuject[0]->sub_name;
        }

        if($className !='' && $sectionName !='' && $subjectName !="")
        {
              $clsSubSecName=$className.'/'.$sectionName.'/'.$subjectName;
        }
        else
        {
          return 0;
          exit;

        }

      
       
 if (count($expIds) >=3) {

    for ($i = 2; $i < count($expIds); $i++) {
          $fmRowId = $expIds[$i];
          $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
    if ($getUrlPathCnt > 0) {
        $getUrlPath = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get();
        $urlPath.= $getUrlPath[0]->file_name . '/';
        }
    } 

    $getRootPath = $this->getuserAccessPath($staffId);
      if ($getRootPath != '0') {
        return $getRootPath . '/' . $clsSubSecName.'/'.$urlPath;
        exit;
      }
      else {
        return 0;
        exit;
      }

    }
    else
    {


       $getRootPath = $this->getuserAccessPath($staffId);
      if ($getRootPath != '0') {
        return $getRootPath . '/' . $clsSubSecName;
        exit;
      }
      else {
        return 0;
        exit;
      }


    }
 //    // return $urlPath;

 //    $getRootPath = $this->getuserAccessPath($staffId);
    
 //      if ($getRootPath != '0') {
 //        return $getRootPath . '/'.$clsSubSecName . $urlPath;
 //        exit;
 //      }
 //      else {
 //        return 0;
 //        exit;
 //      }

 //    }
 //    else {
 //       return 0;
 //       exit;
 //    }




//     if (count($expIds) > 0) {
//       $urlPath = '';
//       for ($i = 1; $i < count($expIds); $i++) {
//         $fmRowId = $expIds[$i];
//         $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
//         if ($getUrlPathCnt > 0) {
//           $getUrlPath = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get();
//           $urlPath.= $getUrlPath[0]->file_name . '/';
//         }
//       }
// return $urlPath;
// exit;
//       $getRootPath = $this->getuserAccessPath();
//       if ($getRootPath != '0') {
//         return $getRootPath . '/' . $urlPath;
//         exit;
//       }
//       else {
//         return 0;
//         exit;
//       }
//     }
//     else {
//       return 0;
//       exit;
//     }
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

}
