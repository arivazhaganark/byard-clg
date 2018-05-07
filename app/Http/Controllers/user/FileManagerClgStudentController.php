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
use App\Model\backend\Setting;
use App\Model\backend\User;
use Redirect;
use Session;
use DB;
use File;
class FileManagerClgStudentController extends Controller
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

    $totYear=0;
    $getStudentDetails=$this->getStudentDetails();
               if($getStudentDetails['status']==1)
               {
                  $course_id=$getStudentDetails['courseId'];
                  $totYear=$getStudentDetails['totYear']*2;
               }
                
     return view('user.filemanagerClgStudent',compact('totYear','logoImgPath'));
  }
  public function getSearchHomeAjxDir(Request $request)
  {

       $inputs=$request->all();
       $SemId=$inputs['file_id'];
       $pathIds=$inputs['pathIds'];
       $folderMode=$inputs['folderMode'];
       $staffId=0;
       $rowId=0;
       $sTxt=trim($inputs['searchTxt']);
       $searchVal="'".'%'.$sTxt.'%'."'";

       $getStudentDetails=$this->getStudentDetails();
       if($getStudentDetails['status']==1)
       {

        $courseId=$getStudentDetails['courseId'];
        $totYear=$getStudentDetails['totYear']*2;
        $semArr=[];
        $subjctIds=[];
        $staffIds=[];
        $subjctIds[]=0;

          for($semI=1;$semI<=$totYear;$semI++)
          {
             $semArr[]=$semI;
             $getSubjectCntIds=ClgSubjectModel::where(["course_id"=>"$courseId","semester_id"=>"$semI"])->count();
             if($getSubjectCntIds>0)
             {
              $getSubjectIds=ClgSubjectModel::where(["course_id"=>"$courseId","semester_id"=>"$semI","active"=>"1"])->get();
              foreach ($getSubjectIds as $key => $value) {
                $subId=$value->sub_id;
                $subjctIds[]=$value->sub_id;

                $getStaffIdCnt=ClgStfSubMapModel::where(["course_id"=>"$courseId","sub_id"=>"$subId","semester_id"=>"$semI","active"=>"1"])->count();
                if($getStaffIdCnt>0)
                {
                   $getStaffIdCnt=ClgStfSubMapModel::leftJoin('at_college_staff_subject_master','at_college_staff_subject_master.clg_stf_sub_id','=','at_college_staff_subject_mapping.clg_stf_sub_id')->where(["at_college_staff_subject_mapping.course_id"=>"$courseId","at_college_staff_subject_mapping.sub_id"=>"$subId","at_college_staff_subject_mapping.semester_id"=>"$semI","at_college_staff_subject_mapping.active"=>"1","at_college_staff_subject_master.active"=>"1"])->count();

                  if($getStaffIdCnt>0)
                  {
                      $getStaffId=ClgStfSubMapModel::leftJoin('at_college_staff_subject_master','at_college_staff_subject_master.clg_stf_sub_id','=','at_college_staff_subject_mapping.clg_stf_sub_id')->where(["at_college_staff_subject_mapping.course_id"=>"$courseId","at_college_staff_subject_mapping.sub_id"=>"$subId","at_college_staff_subject_mapping.semester_id"=>"$semI","at_college_staff_subject_mapping.active"=>"1","at_college_staff_subject_master.active"=>"1"])->get();
                   
                     foreach ($getStaffId as $key => $value) {
                        $staffIds[]=$value->cl_stf_id  ;
                       }
                  }
                }
              }
            }
          }  
              $semesterIds=implode(',',$semArr);
              $totCnt=0;$fileCnt=0;
              $subjctIds=implode(',',$subjctIds);
              if(count($staffIds)>0 && count($semArr)>0)
              {

                 $stfIds=implode(',',array_unique($staffIds));
                 $getSearchValCnt=DB::select("SELECT count(*) as Cnt FROM  at_college_staff_file_manager WHERE   cl_stf_id  in($stfIds) AND sub_id in($subjctIds) AND  active=1 AND file_published=1 AND file_name  like $searchVal ");

                 if($getSearchValCnt[0]->Cnt>0)
                 {
 
                  $chkParentFiles=DB::select("SELECT  *   FROM  at_college_staff_file_manager WHERE   cl_stf_id  in($stfIds) AND sub_id in($subjctIds)  AND active=1 AND file_published=1 AND file_name  like $searchVal ");

                    foreach ($chkParentFiles as $key => $chkParentchildvalue) {
                             $totCnt++;
                             $SemId=$chkParentchildvalue->semester_id;
                             $subId=$chkParentchildvalue->sub_id;
                             $cl_stf_id=$chkParentchildvalue->cl_stf_id;
                             $fldermode="'staffFile'";
                             $chkThumbPath = '';
                             $chkThumbUrlPath = '';
                           if ($chkParentchildvalue->file_type == 'folder') {

                               $pathIds=$chkParentchildvalue->path_folder_ids ;
                               $basicPath =$this->getFileManagerPathIds($pathIds);
                               $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath.$chkParentchildvalue->file_name);

                                $getStaffName=$this->getStaffName($cl_stf_id);

                                if($getStaffName['status']==1)
                                 {
                       
                                   $sName=$getStaffName['staffName'];

                                  $langIdArr=explode('/',$basicPath.'/'.$chkParentchildvalue->file_name);
                                  $langVal="Semester-".$SemId."_semester_".$SemId;
                                  if(count($langIdArr)>=3)
                                  {
                                    for($k=3;$k<count($langIdArr);$k++)
                                    {

                                      if($k==3)
                                      {
                                      $langVal.=",".$langIdArr[$k]."_subject_$subId".",".$sName."_Staff_".$cl_stf_id;
                                      }
                                       
                                    }
                                    $getSubFolderIds=$this->SubFolderIds($chkParentchildvalue->clg_stf_file_id);
                                    if($getSubFolderIds !='0')
                                    {
                                        $langVal.=$getSubFolderIds;
                                    }
                                     


                                  }
                                     $imgName = "'" . $langVal . "'";

                               
                                if (file_exists($chkPath) && $getSubFolderIds !='0') {
                                  $fileCnt++;
                            echo '<div class="col-sm-2 mb-3p" id="2"><a class="brdcum" href=""> </a><div style="text-align: center;">
                                       <a href="javascript:" class="flderLinks" onclick="return fnTsSearch(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.','.$chkParentchildvalue->clg_stf_file_id.','.$imgName.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                      <span  id="getClassFlde_' . $chkParentchildvalue->clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
                                      <input type="hidden" name="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" id="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" >
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                       </div>
                                       </div>';
                                     }
                                   }


                           }
                           else
                           {



                            if (1) {
                              $fileCnt++;
                           $imgName='';
                           $urlPath='';
                           $basicPathVal='';
                           $chkPathVal='';
                           $phyRes='';


                            $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;

                            $pathIds=$chkParentchildvalue->path_folder_ids ;
                            $basicPath =$this->getFileManagerPathIds($pathIds);
                            $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath.$chkParentchildvalue->file_name);
                            $imgName = "'" . strtolower($basicPath.$chkParentchildvalue->file_name) . "'";
                            $basicPathVal = "'" . $chkPath . "'";
                         
                           $urlPath=url('uploads/file_manager/'.strtolower($basicPath.$chkParentchildvalue->file_name))  ;
                           $desc=$chkParentchildvalue->description;
                           
                           $chkThumbPath = base_path() . "/public/uploads/file_manager/" .strtolower($basicPath . $chkParentchildvalue->thumb_img);
                           $chkThumbUrlPath = url('uploads/file_manager/' .strtolower($basicPath . $chkParentchildvalue->thumb_img));


                           if (file_exists($chkPath)) {
                            $fileCnt++;
                  
                          $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') 
                           {

                            $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block custom_span_fa" aria-hidden="true"></i></a>';
                            if (file_exists($chkThumbPath)) {

                              $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  ></a>';

                             }
                          }
                          else
                          if (strtolower($ext) == 'pdf')
                          {
                              $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                               
                          }
                          else
                          if (strtolower($ext) == 'doc') 
                            {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                          
                            }
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') {
                           $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                            
                         }
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') 
                            {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
                          
                           }
                          else
                          if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg'){
                           $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';
                           if (file_exists($chkThumbPath)) {

                            $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';

                             }
                         }
                          else {$ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>';}

                          
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;"   lang=' . $basicPathVal . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
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

                         }
                         if($totCnt==0)
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
                echo "No files/folder found";
                exit;
              }
          }
       else
       {
        echo $getStudentDetails['errMsg'];
        exit;

       }
  }
  public function SubFolderIds($RowIds){

    $getFileIdsVal=ClgFileManagerModel::where(['clg_stf_file_id' => $RowIds])->get();
    $getPathIds=isset($getFileIdsVal[0]->path_folder_ids)?$getFileIdsVal[0]->path_folder_ids:'';
    $fileName=isset($getFileIdsVal[0]->file_name)?$getFileIdsVal[0]->file_name:'';
    $returnVal='';
    if($getPathIds !="")
    {

      $explodeVal=explode(',',$getPathIds);
      if(count($explodeVal)>0)
      {
        if(count($explodeVal)>=2)
       {

        for ($i = 0; $i  <  count($explodeVal); $i++) 
        {
           if($i !=0){

           $fmRowId = $explodeVal[$i];
           $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
           $flderName='';
           if($getUrlPathCnt>0)
           {
             
             $getUrlPath= ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get();
             $flderName=isset($getUrlPath[0]->file_name)?$getUrlPath[0]->file_name:'';
             $returnVal.=",".$flderName."_folder_".$fmRowId ;
           } 

           }
        }

      return $returnVal.','.$fileName."_folder_".$RowIds;
      exit;

       }
       else
         {
           $returnVal.=",".$fileName."_folder_".$RowIds;
       
        return $returnVal;
        exit;

        }
       

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

  // public function getSearchHomeAjxDirold(Request $request)
  // {


  //      $inputs=$request->all();
  //      $SemId=$inputs['file_id'];
  //      $pathIds=$inputs['pathIds'];
  //      $folderMode=$inputs['folderMode'];
  //      $staffId=0;
  //      $rowId=0;
  //      $sTxt=trim($inputs['searchTxt']);

  //      if($SemId==0) //First time loading
  //      {  
  //              $getStudentDetails=$this->getStudentDetails();
  //              if($getStudentDetails['status']==1)
  //              {
  //                $course_id=$getStudentDetails['courseId'];
  //              $totYear=$getStudentDetails['totYear']*2;
  //              $staffId=0;
  //              $subId=0;
  //              $semArr=[];

  //               for($semI=1;$semI<=$totYear;$semI++)
  //               { //function fnTs(semId=null,subId=null,staffId=null,folderMode=null,rowId=null)
  //                     $semsterId=$semI;
  //                     $fldermode="'subject'";
  //                     $semArr[]=$semI;
  //                      //print_r($semArr);
  //                     // echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
  //                     //                 <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $semsterId . ','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
  //                     //                 <span id="getClassFlde_' . $semsterId . '">Semester-' . $semsterId . '</span>
  //                     //                <input type="hidden" name="hid_res_' . $semsterId . '" id="hid_res_' . $semsterId . '" >
  //                     //                 <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
  //                     //                 </div>
  //                     //                 </div>';
  //               }
  //               //$semIdImplode=implode(',',  $semArr);

  //              //  $course_id=$getStudentDetails['courseId'];
  //                //$getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'active'=>1])->whereIn('semester_id',[$semArr])->get()->count();

  //                //$getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'active'=>1])->whereIn('semester_id',[$semArr])->where('subject_name', 'like', '%' . $sTxt . '%')->get()->count();

  //                // $chkSubjectMappedStaffCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where(['at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.sub_id'=>$subjectId,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_master.active'=>1])->where('staff_name', 'like', '%' . $sTxt . '%')->get()->count();

  //               //print_r($semArr);
  //              }
  //              else
  //              {
  //               echo $getStudentDetails['errMsg'];
  //              }

  //      }
  //      elseif($folderMode=='subject') 
  //      {

  //        $SemId = $inputs['file_id'];
  //        $rowId=0;
  //        if($SemId>0)
  //        {
              
  //           $getStudentDetails=$this->getStudentDetails();
  //           if($getStudentDetails['status']==1)
  //           {
  //              $course_id=$getStudentDetails['courseId'];
  //              $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->where('subject_name', 'like', '%' . $sTxt . '%')->get()->count();
  //              if($getSubjectCnt>0)
  //              {
  //                   $getSubject=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->where('subject_name', 'like', '%' . $sTxt . '%')->get();
  //                   foreach ($getSubject as $key => $subValue) {

  //                     $subName=$subValue->subject_name;
  //                     $subId=$subValue->sub_id;
                     
  //                     $semsterId=$SemId;
  //                     $staffId=0;
  //                     $fldermode="'staff'";
  //                     echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
  //                                     <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $semsterId . ','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
  //                                     <span class="aliSearchCls" id="getClassFlde_' . $subId . '">' . $subName . '</span>
  //                                    <input type="hidden" name="hid_res_' . $subId . '" id="hid_res_' . $subId . '" >
  //                                     <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
  //                                     </div>
  //                                     </div>';


                     
  //                   }

  //              }
  //              else
  //              {
  //               echo "Search result is empty";
  //              }
  //           }
  //           else
  //           {
  //           echo $getStudentDetails['errMsg'];
  //           exit;
  //           }

  //        }
  //        else
  //        {
  //         echo "Invalid request";
  //        }

  //      }
  //      elseif($folderMode=='staff') 
  //      {

  //        $SemId=$inputs['file_id'];
  //        $subId=$inputs['subId'];
  //        if($SemId>0 && $subId>0)
  //        {

  //          $getStudentDetails=$this->getStudentDetails();
  //           if($getStudentDetails['status']==1)
  //           {
  //              $course_id=$getStudentDetails['courseId'];
  //              $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get()->count();
  //              if($getSubjectCnt>0)
  //              {

  //                $getStffMappedSubject=$this->getStaffMappedSubjctSearchDetails($course_id,$SemId,$subId,$sTxt);
                
  //                if($getStffMappedSubject['status']==1)
  //                {
  //                 $fldermode="'staffFile'";
  //                 $subName='';
  //                 $stffCnt=0;
  //                  foreach ($getStffMappedSubject['output'] as $key => $stfValue) {

  //                    $cl_stf_id=$stfValue['cl_stf_id'] ;
  //                    //$clg_stf_file_id=stfValue

  //                    $getStaffName=$this->getStaffName($cl_stf_id);

                       
                     
  //                     if($getStaffName['status']==1)
  //                     {
  //                        $stffCnt++;
  //                         $sName=$getStaffName['staffName'];
  //                         $sCode=$getStaffName['staffcode'];

  //                       echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
  //                                      <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
  //                                     <span class="aliSearchCls" id="getClassFlde_' . $cl_stf_id . '">' . $sName . '</span>
  //                                     <input type="hidden" name="hid_res_' . $cl_stf_id . '" id="hid_res_' . $cl_stf_id . '" >
  //                                     <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
  //                                      </div>
  //                                      </div>';


  //                     }
                     
  //                  }
  //                  if($stffCnt==0)
  //                  {
  //                      echo "Search result is empty";
  //                      exit;
  //                 }

  //                }
  //                else
  //                {
  //                 echo $getStffMappedSubject['errMsg'];
  //                 exit;
  //                }

  //                 //$getStaffInFileManager=ClgFileManagerModel::  

  //              }
  //              else
  //              {
  //                 echo "No subject found";
  //                 exit;
                   
  //              }

  //            }
  //            else
  //            {
  //             echo $getStudentDetails['errMsg'];
  //             exit;
  //            }


  //        }
  //        else
  //        {
  //         echo "Invalid request";
  //         exit;
  //        }
  //      }
  //      elseif($folderMode=='staffFile')
  //      {
  
  //          $cl_stf_id=$inputs['staffId'];
  //          $SemId=$inputs['file_id']; //semesterid
  //          $subId=$inputs['subId'];
  //          $fldermode="'staffFile'";
  //          $fileRowId=$inputs['fileRowId'];
  //          $basicPath='';

  //           $getStudentDetails=$this->getStudentDetails();


            
  //           if($getStudentDetails['status']==1)
  //           {
  //               $course_id=$getStudentDetails['courseId'];
  //               $subCnt=ClgSubjectModel::where('sub_id',$subId)->get()->count();
  //               if($subCnt>0)
  //               {
  //                 $subName=ClgSubjectModel::where('sub_id',$subId)->get() ;
  //                 $subjectName=isset($subName[0]->subject_name)?$subName[0]->subject_name:'';
  //               }
  //               else
  //               {
  //                 echo "Folder is empty";
  //                 exit;
  //               } 

  //              $courseName=$getStudentDetails['courseName'];
  //              $getStaffDetails=$this->getStaffName($cl_stf_id);
  //               if($getStaffDetails['status']==0)
  //               {
  //               echo $resultStfArr['errMsg'];
  //               exit;
  //               }
  //               else
  //               {
  //               $basicPath=$getStaffDetails['staffcode'] .'_'.$getStaffDetails['staffName'].'/'.$courseName.'/semester-'.$SemId.'/'.$subjectName.'/'; 

  //               }

                

               
  //               if($fileRowId==0){  
 

  //               $chkParentchildCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get()->count();
                 
  //               if($chkParentchildCnt>0)
  //               {
  //                    $chkParentchild=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get();
  //                   $getId=$chkParentchild[0]->clg_stf_file_id;
  //                   $chkParentFilesCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$getId])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
  //                   if($chkParentFilesCnt>0)
  //                   {


  //                       $chkParentFiles=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$getId])->where('file_name', 'like', '%' . $sTxt . '%')->get();
  //                       $totCnt=0;
  //                       $flderCnt=0;
  //                       $fileCnt=0;
  //                       foreach ($chkParentFiles as $key => $chkParentchildvalue) {
  //                            $totCnt++;
  //                          if ($chkParentchildvalue->file_type == 'folder') {

  //                            $flderCnt++;
  //                           echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
  //                                      <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.','.$chkParentchildvalue->clg_stf_file_id.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
  //                                     <span class="aliSearchCls" id="getClassFlde_' . $chkParentchildvalue->clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
  //                                     <input type="hidden" name="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" id="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" >
  //                                     <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
  //                                      </div>
  //                                      </div>';



  //                          }
  //                          else
  //                          {


  //                             $phyRes='';
  //                             $chkPathVal='';
   
  //                         $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath.$chkParentchildvalue->file_name);
  //                         $imgName = "'" . strtolower($basicPath.$chkParentchildvalue->file_name) . "'";
  //                          $basicPathVal = "'" . $chkPath . "'";
                          
  //                         $urlPath=url('uploads/file_manager/'.strtolower($basicPath.$chkParentchildvalue->file_name))  ;

  //                           if (file_exists($chkPath)) {
  //                             $fileCnt++;
  //                           $phyRes = 'phy@yes';


  //                           $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
  //                         $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
  //                         if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block custom_span_fa" aria-hidden="true"></i></a>';
  //                         else
  //                         if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else $ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>';

  //                         // echo $chkParentchildvalue->file_name;
  //                         echo '<div class="col-sm-2 mb-3p" id="2">
  //                         <div style="text-align: center;"   lang=' . $basicPathVal . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
  //                         ' . $ic . '
  //                         <span class="aliSearchCls" lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
  //                         <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
  //                         <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
  //                         </div>
  //                         </div>';




  //                           }
  //                           else {
  //                           $phyRes = 'phy@no' ;
  //                           }

                          

  //                        }
                         
  //                       }

                         
  //                       if($flderCnt==0 && $fileCnt==0)
  //                       {
  //                         echo "Folder is empty";
  //                       }
                        

  //                   }
  //                   else
  //                   {

  //                      echo "Search result is empty";
  //                      exit;

  //                   }

  //               }
  //               else
  //               {
  //                  echo "No files/folder found";
  //                  exit;
  //               }

  //             }
  //             else //staff folder view 2nd time
  //             {
                  
  //                 $clg_stf_file_id=$fileRowId;
  //                 $chkParentchildCnt=ClgFileManagerModel::where(['clg_stf_file_id' => $fileRowId, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get()->count();
  //                 if($chkParentchildCnt>0)
  //                 {

  //                   $chkParentchildFlderCnt=ClgFileManagerModel::where(['course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$clg_stf_file_id])->where('file_name', 'like', '%' . $sTxt . '%')->get()->count();
  //                     if($chkParentchildFlderCnt>0)
  //                     {

  //                        $chkParentchildFlder=ClgFileManagerModel::where(['course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$clg_stf_file_id])->where('file_name', 'like', '%' . $sTxt . '%')->get() ;

  //                           $totCnt=0;
  //                           $flderCnt=0;
  //                           $fileCnt=0;

  //                             foreach ($chkParentchildFlder as $key => $chkParentchildvalue) 
  //                             {

  //                                   if ($chkParentchildvalue->file_type == 'folder') {
  //                                     $flderCnt++;

  //                                   echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
  //                                   <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.','.$chkParentchildvalue->clg_stf_file_id.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
  //                                   <span class="aliSearchCls" id="getClassFlde_' . $chkParentchildvalue->clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
  //                                   <input type="hidden" name="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" id="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" >
  //                                   <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
  //                                   </div>
  //                                  </div>';
  //                          }
  //                          else
  //                          {
                              
                                
  //                               $phyRes='';
  //                               $chkPathVal='';

  //                         $getPath=$this->getFileManagerPathWithIds($pathIds,$staffId,$SemId,$subId);


                                 

  //                         $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath. $getPath.$chkParentchildvalue->file_name);
  //                         $imgName = "'" . strtolower($basicPath.$getPath.$chkParentchildvalue->file_name) . "'";
  //                          $basicPathVal = "'" . $chkPath . "'";
                          
  //                         $urlPath=url('uploads/file_manager/'.strtolower($basicPath.$getPath.$chkParentchildvalue->file_name))  ;

  //                           if (file_exists($chkPath)) {
  //                           $phyRes = 'phy@yes';
  //                           $fileCnt++;

  //                            $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
  //                         $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
  //                         if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block custom_span_fa" aria-hidden="true"></i></a>';
  //                         else
  //                         if (strtolower($ext) == 'pdf') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'doc') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') $ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else
  //                         if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';
  //                         else $ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>';

  //                         // echo $chkParentchildvalue->file_name;
  //                         echo '<div class="col-sm-2 mb-3p" id="2">
  //                         <div style="text-align: center;"   lang=' . $basicPathVal . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
  //                         ' . $ic . '
  //                         <span  lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
  //                         <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
  //                         <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
  //                         </div>
  //                         </div>';
  //                           }
  //                           else {
  //                           $phyRes = 'phy@no';
  //                           }

                         



  //                          }

  //                             }
  //                       if($flderCnt==0 && $fileCnt==0)
  //                       {
  //                         echo "Folder is empty";
  //                         exit;
  //                       }

  //                     }
  //                     else
  //                     {

  //                       echo "Search result is empty";
  //                       exit;
  //                     }


  //                 }
  //                 else
  //                 {

  //                    echo "No files/folder founds";
  //                    exit;

  //                 }




  //             }
  //           }
  //           else
  //           {

  //              echo $getStudentDetails['errMsg'];
  //               exit;


  //           }
          
  //      }




  // }

  public function getHomeAjxDir(Request $request)
  {


       $inputs=$request->all();
       $SemId=$inputs['file_id'];
       $pathIds=$inputs['pathIds'];
       $folderMode=$inputs['folderMode'];
       $staffId=0;
       $rowId=0;

       if($SemId==0) //First time loading
       {
               $getStudentDetails=$this->getStudentDetails();
               if($getStudentDetails['status']==1)
               {
                 $course_id=$getStudentDetails['courseId'];
               $totYear=$getStudentDetails['totYear']*2;
               $staffId=0;
               $subId=0;

                for($semI=1;$semI<=$totYear;$semI++)
                { //function fnTs(semId=null,subId=null,staffId=null,folderMode=null,rowId=null)
                      $semsterId=$semI;
                      $fldermode="'subject'";
                      echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                      <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $semsterId . ','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                      <span id="getClassFlde_' . $semsterId . '">Semester-' . $semsterId . '</span>
                                     <input type="hidden" name="hid_res_' . $semsterId . '" id="hid_res_' . $semsterId . '" >
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                      </div>
                                      </div>';
                }
               }
               else
               {
                echo $getStudentDetails['errMsg'];
               }

       }
       elseif($folderMode=='subject') 
       {

         $SemId = $inputs['file_id'];
         $rowId=0;
         if($SemId>0)
         {
              
            $getStudentDetails=$this->getStudentDetails();
            if($getStudentDetails['status']==1)
            {
               $course_id=$getStudentDetails['courseId'];
               $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get()->count();
               if($getSubjectCnt>0)
               {
                    $getSubject=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get();
                    
                    foreach ($getSubject as $key => $subValue) {
                      $chkThumbPath = '';
                      $chkThumbUrlPath = '';

                      $subName=$subValue->subject_name;
                      $subId=$subValue->sub_id;

                     
                      $semsterId=$SemId;
                      $staffId=0;
                      $fldermode="'staff'";
                      echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                      <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $semsterId . ','.$subId.','.$staffId.','.$fldermode.','.$rowId.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                      <span id="getClassFlde_' . $subId . '">' . $subName . '</span>
                                     <input type="hidden" name="hid_res_' . $subId . '" id="hid_res_' . $subId . '" >
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                      </div>
                                      </div>';


                     
                    }

               }
               else
               {
                echo "No subject found";
               }
            }
            else
            {
            echo $getStudentDetails['errMsg'];
            exit;
            }

         }
         else
         {
          echo "Invalid request";
         }

       }
       elseif($folderMode=='staff') 
       {

         $SemId=$inputs['file_id'];
         $subId=$inputs['subId'];
         if($SemId>0 && $subId>0)
         {

           $getStudentDetails=$this->getStudentDetails();
            if($getStudentDetails['status']==1)
            {
               $course_id=$getStudentDetails['courseId'];
               $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get()->count();
               if($getSubjectCnt>0)
               {

                 $getStffMappedSubject=$this->getStaffMappedSubjctDetails($course_id,$SemId,$subId);
                
                 if($getStffMappedSubject['status']==1)
                 {
                  $fldermode="'staffFile'";
                  $subName='';
                  $stffCnt=0;
                   foreach ($getStffMappedSubject['output'] as $key => $stfValue) {

                    $chkThumbPath = '';
                    $chkThumbUrlPath = '';

                     $cl_stf_id=$stfValue['cl_stf_id'] ;
                     //$clg_stf_file_id=stfValue

                     $getStaffName=$this->getStaffName($cl_stf_id);

                       
                     
                      if($getStaffName['status']==1)
                      {
                         $stffCnt++;
                          $sName=$getStaffName['staffName'];
                          $sCode=$getStaffName['staffcode'];

                        echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                      <span id="getClassFlde_' . $cl_stf_id . '">' . $sName . '</span>
                                      <input type="hidden" name="hid_res_' . $cl_stf_id . '" id="hid_res_' . $cl_stf_id . '" >
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                       </div>
                                       </div>';


                      }
                     
                   }
                   if($stffCnt==0)
                   {
                       echo "No staff found";
                       exit;
                  }

                 }
                 else
                 {
                  echo $getStffMappedSubject['errMsg'];
                  exit;
                 }

                  //$getStaffInFileManager=ClgFileManagerModel::  

               }
               else
               {
                  echo "No subject found";
                  exit;
                   
               }

             }
             else
             {
              echo $getStudentDetails['errMsg'];
              exit;
             }


         }
         else
         {
          echo "Invalid request";
          exit;
         }
       }
       elseif($folderMode=='staffFile')
       {
  
           $cl_stf_id=$inputs['staffId'];
           $SemId=$inputs['file_id']; //semesterid
           $subId=$inputs['subId'];
           $fldermode="'staffFile'";
           $fileRowId=$inputs['fileRowId'];
           $basicPath='';

            $getStudentDetails=$this->getStudentDetails();


            
            if($getStudentDetails['status']==1)
            {
                $course_id=$getStudentDetails['courseId'];
                $subCnt=ClgSubjectModel::where('sub_id',$subId)->get()->count();
                if($subCnt>0)
                {
                  $subName=ClgSubjectModel::where('sub_id',$subId)->get() ;
                  $subjectName=isset($subName[0]->subject_name)?$subName[0]->subject_name:'';
                }
                else
                {
                  echo "Folder is empty";
                  exit;
                } 

               $courseName=$getStudentDetails['courseName'];
               $getStaffDetails=$this->getStaffName($cl_stf_id);
                if($getStaffDetails['status']==0)
                {
                echo $resultStfArr['errMsg'];
                exit;
                }
                else
                {
                $basicPath=$getStaffDetails['staffcode'] .'_'.$getStaffDetails['staffName'].'/'.$courseName.'/semester-'.$SemId.'/'.$subjectName.'/'; 

                }

                

               
                if($fileRowId==0){  


                $chkParentchildCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get()->count();
                if($chkParentchildCnt>0)
                {
                     $chkParentchild=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get();
                    $getId=$chkParentchild[0]->clg_stf_file_id;
                    $chkParentFilesCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$getId,'file_published'=>1])->get()->count();
                    if($chkParentFilesCnt>0)
                    {


                        $chkParentFiles=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$getId,'file_published'=>1])->get();
                        $totCnt=0;
                        $flderCnt=0;
                        $fileCnt=0;
                        $chkThumbPath = '';
                        $chkThumbUrlPath = '';
                        $desc='';
                        foreach ($chkParentFiles as $key => $chkParentchildvalue) {
                          $chkThumbPath = '';
                          $chkThumbUrlPath = '';
                             $totCnt++;
                          $desc=$chkParentchildvalue->description;
                           if ($chkParentchildvalue->file_type == 'folder') {

                             $flderCnt++;
                            echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.','.$chkParentchildvalue->clg_stf_file_id.');"><i class="fa fa-folder custom_span_fa" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                      <span class="aliSearchCls" id="getClassFlde_' . $chkParentchildvalue->clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
                                      <input type="hidden" name="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" id="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" >
                                      <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                       </div>
                                       </div>';



                           }
                           else
                           {


                              $phyRes='';
                              $chkPathVal='';

                           $chkThumbPath = base_path() . "/public/uploads/file_manager/" .strtolower($basicPath . $chkParentchildvalue->thumb_img);
                           $chkThumbUrlPath = url('uploads/file_manager/' .strtolower($basicPath . $chkParentchildvalue->thumb_img));
   
                          $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath.$chkParentchildvalue->file_name);
                          $imgName = "'" . strtolower($basicPath.$chkParentchildvalue->file_name) . "'";
                           $basicPathVal = "'" . $chkPath . "'";

                            $chkThumbPath = base_path() . "/public/uploads/file_manager/".strtolower($basicPath . $chkParentchildvalue->thumb_img) ;
                          
                           $chkThumbUrlPath = url('uploads/file_manager/' .strtolower($basicPath . $chkParentchildvalue->thumb_img));
                          
                          $urlPath=url('uploads/file_manager/'.strtolower($basicPath.$chkParentchildvalue->file_name))  ;

                            if (file_exists($chkPath)) {
                              $fileCnt++;
                            $phyRes = 'phy@yes';


                            $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                          $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') {
                            $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block custom_span_fa" aria-hidden="true"></i></a>';
 
                            if (file_exists($chkThumbPath)) {
                              
                                 $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  ></a>';

                             }

                          }
                          else
                          if (strtolower($ext) == 'pdf') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';} 
                          else
                          if (strtolower($ext) == 'doc') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';} 
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xls') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';} 
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';} 
                          else
                          if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg'){ 


                            $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';

                             if (file_exists($chkThumbPath)) {

                                $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';

                            }

                              }
                          else {$ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>';

                         

                          }

                          // echo $chkParentchildvalue->file_name;
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;"   lang=' . $basicPathVal . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
                          ' . $ic . '
                          <span class="aliSearchCls" lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
                           <span class="des_content">' . $desc. '</span>
                          <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
                          </div>
                          </div>';




                            }
                            else {
                            $phyRes = 'phy@no' ;
                            }

                          

                         }
                         
                        }

                         
                        if($flderCnt==0 && $fileCnt==0)
                        {
                          echo "Folder is empty";
                        }
                        

                    }
                    else
                    {

                       echo "No files/folder found";
                       exit;

                    }

                }
                else
                {
                   echo "No files/folder found";
                   exit;
                }

              }
              else //staff folder view 2nd time
              {
                  
                  $clg_stf_file_id=$fileRowId;
                  $chkParentchildCnt=ClgFileManagerModel::where(['clg_stf_file_id' => $fileRowId, 'course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'file_type'=>'folder'])->get()->count();
                  if($chkParentchildCnt>0)
                  {

                    $chkParentchildFlderCnt=ClgFileManagerModel::where(['course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$clg_stf_file_id])->get()->count();
                      if($chkParentchildFlderCnt>0)
                      {

                         $chkParentchildFlder=ClgFileManagerModel::where(['course_id' => $course_id, 'semester_id' => $SemId,'sub_id'=>$subId,'active'=>1,'parent_id'=>$clg_stf_file_id,'file_published'=>1])->get() ;

                            $totCnt=0;
                            $flderCnt=0;
                            $fileCnt=0;
                            $desc='';

                              foreach ($chkParentchildFlder as $key => $chkParentchildvalue) 
                              {  
                                  $chkThumbPath = '';
                                  $chkThumbUrlPath = '';
                                   $desc=$chkParentchildvalue->description;
                                    if ($chkParentchildvalue->file_type == 'folder') {
                                      $flderCnt++;

                                    echo '<div class="col-sm-2 mb-3p" id=""><a class="brdcum" href=""> </a><div style="text-align: center;">
                                    <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $SemId . ','.$subId.','.$cl_stf_id.','.$fldermode.','.$chkParentchildvalue->clg_stf_file_id.');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
                                    <span  class="aliSearchCls" id="getClassFlde_' . $chkParentchildvalue->clg_stf_file_id . '">' . $chkParentchildvalue->file_name. '</span>
                                    <input type="hidden" name="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" id="hid_res_' . $chkParentchildvalue->clg_stf_file_id . '" >
                                    <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
                                    </div>
                                   </div>';
                           }
                           else
                           {
                              
                                
                                $phyRes='';
                                $chkPathVal='';




                          $getPath=$this->getFileManagerPathWithIds($pathIds,$staffId,$SemId,$subId);


                                 

                          $chkPath =strtolower(base_path() . "/public/uploads/file_manager/" .$basicPath. $getPath.$chkParentchildvalue->file_name);
                          $imgName = "'" . strtolower($basicPath.$getPath.$chkParentchildvalue->file_name) . "'";
                           $basicPathVal = "'" . $chkPath . "'";
                          
                          $urlPath=url('uploads/file_manager/'.strtolower($basicPath.$getPath.$chkParentchildvalue->file_name))  ;

                          $chkThumbPath = base_path() . "/public/uploads/file_manager/" .strtolower($basicPath . $chkParentchildvalue->thumb_img);
                           $chkThumbUrlPath = url('uploads/file_manager/' .strtolower($basicPath . $chkParentchildvalue->thumb_img));

                            if (file_exists($chkPath)) {
                            $phyRes = 'phy@yes';
                            $fileCnt++;

                             $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;
                          $ext = pathinfo($chkParentchildvalue->file_name, PATHINFO_EXTENSION);
                          if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'png' || strtolower($ext) == 'gif' || strtolower($ext) == 'bmp') {

                            $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><i class="fa fa-picture-o fa-3x d-block custom_span_fa" aria-hidden="true"></i></a>';

                            if (file_exists($chkThumbPath)) {

                                $ic = '<a href="javascript:" onclick="return fnShowImg(' . $imgName . ',' . $clg_stf_file_id . ');"><img src="'.$chkThumbUrlPath.'"  ></a>';

                            }

                          }
                          else
                          if (strtolower($ext) == 'pdf') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-pdf-o fa-3x font-blue custom_span_fa"></i></a><br/>';}
                          else
                          if (strtolower($ext) == 'doc') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-word-o fa-3x font-blue custom_span_fa"></i></a><br/>';}
                          else
                          if (strtolower($ext) == 'ods' || strtolower($ext) == 'xlsx' || strtolower($ext) == 'xlsx') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-excel-o fa-3x font-blue custom_span_fa"></i></a><br/>';}
                          else
                          if (strtolower($ext) == 'ppt' || strtolower($ext) == 'pptx' || strtolower($ext) == 'odp') {$ic = '<a href="' . $urlPath . '" target="_blank"><i class="fa fa-file-powerpoint-o fa-3x font-blue custom_span_fa"></i></a><br/>';} 
                          else
                          if (strtolower($ext) == 'mp3' || strtolower($ext) == 'mp4' || strtolower($ext) == 'mpeg') {
                            $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><i class="fa fa-video-camera fa-3x font-blue custom_span_fa"></i></a><br/>';
                          
                           if (file_exists($chkThumbPath)) {

                              $ic = '<a href="javascript:" onclick="return show_video(' . $imgName . ');"><img src="'.$chkThumbUrlPath.'"  ></a><br/>';

                            }

                          }
                          else {$ic = '<i class="fa fa-file-text-o fa-3x font-blue custom_span_fa"></i><br/>';

                           } 

                          // echo $chkParentchildvalue->file_name;
                          echo '<div class="col-sm-2 mb-3p" id="2">
                          <div style="text-align: center;"   lang=' . $basicPathVal . ' id=' . $clg_stf_file_id . ' class="context-menu-one" >
                          ' . $ic . '
                          <span class="aliSearchCls" lang=' . $chkPathVal . '  id="file_name_area_' . $clg_stf_file_id . '">' . $chkParentchildvalue->file_name . '</span>
                           <span class="des_content">' . $desc. '</span>
                          <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
                          <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="' . $chkParentchildvalue->file_name . '" style="display: none;">
                          </div>
                          </div>';
                            }
                            else {
                            $phyRes = 'phy@no';
                            }

                         



                           }

                              }
                        if($flderCnt==0 && $fileCnt==0)
                        {
                          echo "Folder is empty";
                          exit;
                        }

                      }
                      else
                      {

                        echo "Foler is empty";
                        exit;
                      }


                  }
                  else
                  {

                     echo "No files/folder founds";
                     exit;

                  }




              }
            }
            else
            {

               echo $getStudentDetails['errMsg'];
                exit;


            }
          
       }


       
 

  }

  public function getStudentDetails()
  {

    $resultArr['status']=0;
    $resultArr['errMsg']="";
    $getStudentCode = auth()->guard('user')->user()->email;
    $ChkStudentCnt=ClgStudentModel::leftJoin('users','users.email','=','at_college_student_master.roll_no')->leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_student_master.course_id')->where(['at_college_student_master.active'=>1,'at_college_student_master.roll_no'=>$getStudentCode,'at_college_course_master.active'=>1])->get()->count();
     if($ChkStudentCnt>0)
     {
        $ChkStudent=ClgStudentModel::leftJoin('users','users.email','=','at_college_student_master.roll_no')->leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_student_master.course_id')->where(['at_college_student_master.active'=>1,'at_college_student_master.roll_no'=>$getStudentCode,'at_college_course_master.active'=>1])->get() ;

        $courseId=isset($ChkStudent[0]->course_id)?$ChkStudent[0]->course_id:'';
        $courseName=isset($ChkStudent[0]->course_name)?$ChkStudent[0]->course_name:'';
        $totYear=isset($ChkStudent[0]->year_id)?$ChkStudent[0]->year_id:'';
        
        if($courseId !="" &&  $courseName!="" && $totYear>0  )
        {
           $resultArr['status']=1;
           $resultArr['courseId']=$courseId;
           $resultArr['courseName']=$courseName;
           $resultArr['totYear']=$totYear;

        }
        else
        {
          $resultArr['errMsg']="Invalid course";
        }
     }
     else
     {
         $resultArr['errMsg']="Course are inactive";

     }

   return $resultArr;
  }
  public function getStaffMappedSubjctDetails($courId=null,$semId=null,$subjectId=null)
  {
    $resultSubMapArr['status']=0;
    $resultSubMapArr['errMsg']="";
    $resultSubMapArr['output']="";

    $chkSubjectMappedStaffCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_mapping.sub_id'=>$subjectId,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_master.active'=>1])->get()->count();
    if($chkSubjectMappedStaffCnt>0)
    {
      $chkSubjectMappedStaff=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_mapping.sub_id'=>$subjectId,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_master.active'=>1])->get(array('at_college_staff_subject_master.cl_stf_id'));
      $resultSubMapArr['status']=1;
      $resultSubMapArr['output']=$chkSubjectMappedStaff;

    }
    else
    {
      $resultSubMapArr['errMsg']="No staff found";
    }    
 

    return $resultSubMapArr;



  }
  public function getStaffMappedSubjctSearchDetails($courId=null,$semId=null,$subjectId=null,$sTxt=null)
  {
    $resultSubMapArr['status']=0;
    $resultSubMapArr['errMsg']="";
    $resultSubMapArr['output']="";

    $chkSubjectMappedStaffCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_mapping.sub_id'=>$subjectId,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_master.active'=>1])->where('staff_name', 'like', '%' . $sTxt . '%')->get()->count();
    if($chkSubjectMappedStaffCnt>0)
    {
      $chkSubjectMappedStaff=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_mapping.sub_id'=>$subjectId,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_master.active'=>1])->where('staff_name', 'like', '%' . $sTxt . '%')->get() ;
      $resultSubMapArr['status']=1;
      $resultSubMapArr['output']=$chkSubjectMappedStaff;

    }
    else
    {
      $resultSubMapArr['errMsg']="No staff found";
    }    
 

    return $resultSubMapArr;



  }

  //->where('subject_name', 'like', '%' . $sTxt . '%')->
  public function getStaffMappedDetails($courId=null)
  {

     $resultMapArr['status']=0;
     $resultMapArr['errMsg']="";

     $chkSubjectMappedStaffCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_master.active'=>1])->groupBy('at_college_staff_subject_mapping.semester_id')->get()->count();
       
       if($chkSubjectMappedStaffCnt>0)
       {
           $chkSubjectMappedStaff=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_mapping.course_id'=>$courId,'at_college_staff_subject_master.active'=>1])->groupBy('at_college_staff_subject_mapping.semester_id')->get() ;
           $resultMapArr['status']=1;
           $resultMapArr['output']=$chkSubjectMappedStaff;

       }
       else
       {
           $resultMapArr['errMsg']="No semester mapped";
       }

    return $resultMapArr;
  }
  public function getStaffName($cl_stf_id=null){

    $resultStfArr['status']=0;
    $resultStfArr['errMsg']="";

    $getStaffNameCnt=ClgStaffModel::where(['cl_stf_id'=>$cl_stf_id,'active'=>1])->get()->count();
    if($getStaffNameCnt>0)
    { 
       $getStaffNameVal=ClgStaffModel::where(['cl_stf_id'=>$cl_stf_id,'active'=>1])->get() ;
      $resultStfArr['status']=1;
      $resultStfArr['staffName']=$getStaffNameVal[0]->staff_name;
      $resultStfArr['staffcode']=$getStaffNameVal[0]->staff_code;

    }
    else
    {
      $resultStfArr['errMsg']="Staff are not found/inactive ";
    }

  return  $resultStfArr;
  }

public function getFileManagerPathWithIds($fmRowIds=null,$stfId=null,$SemId=null,$subId=null)
{

    $urlPath='';
    $expIds = explode(',', $fmRowIds);
    if (count($expIds) > 3) {

    for ($i = 3; $i < count($expIds); $i++) {
          $fmRowId = $expIds[$i];
          $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
    if ($getUrlPathCnt > 0) {
        $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();
        $urlPath.= $getUrlPath[0]->file_name . '/';
        }
    }
    return $urlPath;

    }
    else {
       return 0;
       exit;
    }
}

public function subjectSelect(Request $request)
{
      $inputs = $request->all();
      $SemId = $inputs['semSelectId'];
      $getStudentDetails=$this->getStudentDetails();
      $outputRes['status']=0;
      $outputRes['error']='';
      $outputRes['result']='';
            if($getStudentDetails['status']==1)
            {
               $course_id=$getStudentDetails['courseId'];
               $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get()->count();
               if($getSubjectCnt>0)
               {

                    $outputRes['status']=1;
                    $getSubject=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>$SemId ,'active'=>1])->get();
                     $outputRes['result']=$getSubject;
                   
       
               }
               else
               {
                $outputRes['error']="No subject found";
               }
            }
            else
            {
             $outputRes['error']=$getStudentDetails['errMsg'];
            
            }

    echo json_encode($outputRes);
}
public function staffSelect(Request $request)
{
      $inputs = $request->all();
      $SemId = $inputs['semSelectId'];
      $subId = $inputs['subSelectId'];
     
      $outputRes['status']=0;
      $outputRes['error']='';
      $outputRes['result']='';


           $getStudentDetails=$this->getStudentDetails();
            if($getStudentDetails['status']==1)
            {
               $course_id=$getStudentDetails['courseId'];
               $getSubjectCnt=ClgSubjectModel::where(['course_id'=>$course_id,'semester_id'=>"$SemId" ,'active'=>1])->get()->count();
               if($getSubjectCnt>0)
               {

                $chkSubjectMappedStaffCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where(['at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.sub_id'=>$subId,'at_college_staff_subject_mapping.semester_id'=>$SemId,
                    'at_college_staff_subject_master.active'=>1,'at_college_staff_master.active'=>1])->get()->count();
                if($chkSubjectMappedStaffCnt>0)
                {

                  $chkSubjectMappedStaff=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where(['at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.sub_id'=>$subId,'at_college_staff_subject_mapping.semester_id'=>$SemId,
                    'at_college_staff_subject_master.active'=>1,'at_college_staff_master.active'=>1])->get() ;
                     $outputRes['result']=$chkSubjectMappedStaff;
                     $outputRes['status']=1;

                }
                else
                {
                    $outputRes['error']='No staff found';
                }
                  
               }
               else
               {
                  $outputRes['error']="No subject found";
               }

             }
             else
             {
              $outputRes['error']=$getStudentDetails['errMsg'];
             }

        echo json_encode($outputRes);
}   



//at_college_staff_subject_master
//at_college_staff_subject_mapping
//   public function ajaxsearchfm(Request $request)
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
  

// public function ajaxfm(Request $request)
// {
//         $chkUserAndSubMapped=$this->chkUserAndSubMapped();
//         if($chkUserAndSubMapped['status']==1)
//         {

//           $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
//           $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
//           $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];

//           $inputs = $request->all();
//           $flId = $inputs['file_id']; //file manager table primary id
//           $pathIds = $inputs['pathIds'];
//           if($flId==0)
//           {

//             $chkParentCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0,'active'=>1])->get()->count();
//             if($chkParentCnt>0)
//             {

//               $chkParent = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => 0,'active'=>1,'file_type'=>'folder'])->get();
//                foreach($chkParent as $key => $chkParentValue) { 
//                  $coureId=$chkParentValue->course_id;
//                  $clg_stf_file_id = $chkParentValue->clg_stf_file_id;
//                  $getCourseModelCnt=ClgCourseModel::where('course_id',$coureId)->get()->count();
//                 if ($getCourseModelCnt>0) {  
//                     $getCourseModel=ClgCourseModel::where('course_id',$coureId)->get();
//                    $courseName=strtolower($getCourseModel[0]->course_name);

//                    echo '<div class="col-sm-2 mb-3p" id="25"><a class="brdcum" href=""> </a><div style="text-align: center;">
//                       <a href="javascript:" class="flderLinks"  onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a>
//                       <span id="getClassFlde_' . $clg_stf_file_id . '">' .$courseName . '</span>
//                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                       </div>
//                       </div>';
                     
//                 }
//               }
               

//             }
//             else
//             {

//               echo "No folder found";
//               exit;
//             }


//           }
//           else
//           {
         
//              $chkParentchildCnt=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $flId,'active'=>1])->get()->count();
//              if($chkParentchildCnt>0)
//              {

//               $chkParentchild=ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'parent_id' => $flId,'active'=>1])->orderBy('semester_id')->get();
//                $i=0;
//               foreach($chkParentchild as $key => $chkParentchildvalue) {
//                 $i++;
//                 $urlPath = '';
//                 $imgName = '';
//                  $phyRes='';

//                 if ($chkParentchildvalue->file_type == 'folder') {


//                   $getAccessFlderCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $flId])->get()->count();
//                   $acessCls = '';
//                   if ($getAccessFlderCnt > 0) //function for folder rename and delete
//                   {
//                     $getAccessFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $flId])->get();
//                     if ($getAccessFlder[0]->folder_access == 1) {
//                       $acessCls = "context-menu-one";
//                     }
//                   }

//                   $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                   $imgName="";
//                   $phyRes='';

//                   if ($getFileManagerPath != '0') {
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

//                   $clg_stf_file_id = $chkParentchildvalue->clg_stf_file_id;

//                        echo '<div class="col-sm-2 mb-3p"    ><div style="text-align: center;" class="' . $acessCls . '" lang=' . $imgName . '   id=' . $clg_stf_file_id . '>

//                       <a href="javascript:" class="flderLinks" onclick="return fnTs(' . $clg_stf_file_id . ');"><i class="fa fa-folder" aria-hidden="true" style="font-size: 40px;"></i><br /></a> 
//                       <span lang=' . $imgName . '   id="getClassFlde_' . $clg_stf_file_id . '">' . strtolower($chkParentchildvalue->file_name) . '</span>
//                       <input type="hidden" name="hid_res_' . $clg_stf_file_id . '" id="hid_res_' . $clg_stf_file_id . '" value="' . $phyRes . '">
//                       <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="" style="display: none;">
//                       </div> 
//                       </div>';

                 

//                 }
//                 else
//                 {


//                  $getFileManagerPath = $this->getFileManagerPathWithIds($pathIds);
//                   $imgName="";
//                   $phyRes='';

//                   if ($getFileManagerPath != '0') {
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


//                   if ($phyRes == 'phy@no') {
//                     $imgName = '';
//                     $urlPath = '';
//                   }
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



//                 }


//               }




//              }
//              else
//              {

//               echo "Folder is empty";
//              }


             
//           }



//         }
//         else
//         {

//          echo $chkUserAndSubMapped['errMsg'];
//          exit;

//         }

//        //  print_r($chkUserAndSubMapped);
//   }

//   public function renameflder(Request $request)
//     {
//      $inputs = $request->all();
//       $file_id = $inputs['file_id'];
//       $new_file_name = trim(strtolower($inputs['new_name']));
//       $old_file_name = trim(strtolower($inputs['old_name']));
//       $pathIds = $inputs['pathIds'];
//     if($new_file_name == "" && $old_file_name == "" && $file_id == "" && $pathIds == "") {
//        echo "Please try again";
//        exit;
//       }

//      $getUserOrginPath = $this->chkUserAndSubMapped();
//      if($getUserOrginPath['status']==0)
//      {
//       echo "Invalide user";
//       exit;

//      }
    
//       $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($pathIds);
      
//       if ($chkPhysicallyFlderExit != '0') {
//         $old_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $old_file_name;
//         $new_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $new_file_name;
//         if (file_exists($old_path)) {
//            if (!file_exists($new_path)) {
//              rename($old_path, $new_path);
//              $updateFileName = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->update(['file_name' => $new_file_name]);
//             echo "success";
//              exit;
//           }
//           else {
//            echo "new folder name already exists";
//               exit;
//             }
//          }
//          else {
//            echo "old folder name not exists";
//             exit;
      
//         }
//   }
// }

//   public function rename(Request $request) //Rename for file
//   { 
//     $inputs = $request->all();
//     $file_id = $inputs['file_id'];
//     $new_file_name = trim(strtolower($inputs['new_name']));
//     $old_file_name = trim(strtolower($inputs['old_name']));
//     $pathIds = $inputs['pathIds'];
//     $data = array(
//       'file_name' => $new_file_name
//     );
//     $getUserOrginPath = $this->chkUserAndSubMapped();
//      if($getUserOrginPath['status']==0)
//      {
//       echo "Invalide user";
//       exit;

//      }

//     $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($pathIds);
//     if ($chkPhysicallyFlderExit != '0') {
//       $old_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $old_file_name;
//       $new_path = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $new_file_name;
//       if (file_exists($old_path)) {
//         if (!file_exists($new_path)) {
//           rename($old_path, $new_path);
//           $updateFileName = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->update(['file_name' => $new_file_name]);
//           echo "success";
//           exit;
//         }
//         else {
//           echo "new file name already exists";
//           exit;
//         }
//       }
//       else {
//         echo "old file name not exists";
//         exit;
//       }
//     }
//     else {
//       echo "Invalid file path";
//       exit;
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

//  public function create_folder(Request $request)
//   {
//     $rowId = $request->fldrId;
//     $linkPathIds = $request->allId;
//     $folderNameParam = strtolower(trim($request->folder_name));
//     if ($rowId > 0 && $folderNameParam != "") {

//       $chkUserAndSubMapped=$this->chkUserAndSubMapped();

//         if($chkUserAndSubMapped['status']==1)
//         {

//           $cl_stf_id=$chkUserAndSubMapped['cl_stf_id'] ;
//           $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'] ;
//           $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
//           $chkParentchildCnt = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' =>$clg_stf_sub_id, 'clg_stf_file_id' => $rowId, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
//           if($chkParentchildCnt>0)
//           {
//             $getParentchkFlder = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' =>$clg_stf_sub_id, 'parent_id' => $rowId, 'file_type' => 'folder'])->get();

//             foreach ($getParentchkFlder as $key => $fldValue) {

//               $fValue=strtolower($fldValue->file_name);
//               if($folderNameParam==$fValue)
//               {
//                   echo "Folder name already exists";
//                   exit;
//               }
             
//             }

          
//             $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($linkPathIds);

//             if ($chkPhysicallyFlderExit != '0') {

//               $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit . $folderNameParam;
//               if (!file_exists($destinationPath)) {
//                 mkdir($destinationPath, 0777, true);

//                 $chkParentchildval = ClgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $rowId, 'file_type' => 'folder'])->get();

//                   $course_id=$chkParentchildval[0]->course_id;
//                   $semester_id =$chkParentchildval[0]->semester_id;
//                   $sub_id=$chkParentchildval[0]->sub_id;

//                 $parent_id = $chkParentchildval[0]->parent_id;
//                 $academic_year = $chkParentchildval[0]->academic_year;

//                 $InserFlder = ClgFileManagerModel::insert(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'course_id' => $course_id, 'semester_id' => $semester_id, 'sub_id' => $sub_id, 'parent_id' => $rowId, 'academic_year' => $academic_year, 'file_type' => 'folder', 'file_name' => $folderNameParam, 'folder_access' => 1]);
//                 echo "success";

//                }


//             }
//             else
//             {
//                echo "Invalid request";
//                exit;
//             }



//           }
//           else
//           {
//             echo "Folder creation denied";
//             exit;
//           }


//         }
//         else
//         {

//           echo "Invalid subject mapping";
//           exit;

//         }

//     }
//     else
//     {
//       echo "Invalid request";
//       exit;
//     }

//   }


//   public function delete(Request $request)
//   {
//     $inputs = $request->all();
//     $c_path = $inputs['c_path'];
//     $file_id = $inputs['file_id'];
//     $FiledelPath = $inputs['FiledelPath'];
//     $file_name = trim($inputs['file_name']);
//     $chkUserAndSubMapped=$this->chkUserAndSubMapped();
//     if($chkUserAndSubMapped['status']==1)
//     {

//         $cl_stf_id = $chkUserAndSubMapped['cl_stf_id'];
//         $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'];
//         $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
//         $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
//         $chkFileOrFolderCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->get()->count();
//         if($chkFileOrFolderCnt>0)
//         {

//            $chkFileOrFolder = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->get();
//            if ($chkFileOrFolder[0]->file_type == 'folder') {

//             $this->rrmdir($dir);
//             $DelChildFile = ClgFileManagerModel::where(['parent_id' => $file_id])->delete();
//             $DelParentFile = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->delete();
//             echo "success";
//             exit;


//            }
//            else
//            {
//              $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
//             if (is_dir($dir)) {
//                 unlink($dir);
//                 }

//                  $DelFile = ClgFileManagerModel::where(['clg_stf_file_id' => $file_id])->delete();
//                 echo "success";
//           }


//         }
//         else
//         {
//           echo "Invalid request";
//           exit;
//         }


//     }
//     else
//     {
//       echo "Invalid folder access";
//       exit;
//     }

//   }

 

//   public function deleteold(Request $request)
//   {
//     $inputs = $request->all();
//     $c_path = $inputs['c_path'];
//     $file_id = $inputs['file_id'];
//     $FiledelPath = $inputs['FiledelPath'];
//     $file_name = trim($inputs['file_name']);
//     $getUserOrginPath = $this->getFileManagerChkValidUser($file_id);
//     if ($getUserOrginPath == 1) {
//       $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
//       $chkFileOrFolderCnt = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->get()->count();
//       $fileorFlder = '';
//       if ($chkFileOrFolderCnt == 1) {
//         $chkFileOrFolder = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->get();
//         if ($chkFileOrFolder[0]->file_type == 'folder') {
//           if (is_dir($dir)) {
//             $this->rrmdir($dir);
//             $DelChildFile = SchFileManagerModel::where(['parent_id' => $file_id])->delete();
//             $DelParentFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
//             echo "success";
//             exit;

//             //    if (!is_dir($dir)) { //chk physically folder available or not
//             //       $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
//             //       $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
//             //       echo "success";
//             //       exit;
//             // }
//             // else
//             // {
//             //   //Here table only maintaining  the files we need file both physical and table
//             //   $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
//             //   $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
//             //   echo "success";
//             //   exit;
//             // }

//           }
//           else {
//             $DelChildFile = SchFileManagerModel::where(['parent_id' => $file_id])->delete();
//             $DelParentFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
//             echo "success";
//             exit;
//           }
//         }
//         else {
//           $dir = base_path() . '/public/uploads/file_manager/' . $FiledelPath;
//           if (is_dir($dir)) {
//             unlink($dir);
//           }

//           $DelFile = SchFileManagerModel::where(['scl_stf_file_id' => $file_id])->delete();
//           echo "success";
//         }
//       }
//       else {
//         echo "Invalid user access";
//         exit;
//       }

//       //   $dir = base_path().'/public/uploads/file_manager/'.$FiledelPath;
//       //    if(is_dir($dir)) {
//       //       $this->rrmdir($dir);
//       //       if (!is_dir($dir)) {
//       //          $DelChildFile=SchFileManagerModel::where(['parent_id'=>$file_id])->delete();
//       //          $DelParentFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
//       //          echo "success";
//       //          exit;
//       //       }
//       // } else {
//       //    // unlink($dir);
//       //    // $DelFile=SchFileManagerModel::where(['scl_stf_file_id'=>$file_id])->delete();
//       //    // echo "success";
//       //  }

//     }
//     else {
//       echo "Invalid user";
//       exit;
//     }
//   }

//   function rrmdir($dir)
//   {
//     if (is_dir($dir)) {
//       $objects = scandir($dir);
//       foreach($objects as $object) {
//         if ($object != "." && $object != "..") {
//           if (filetype($dir . "/" . $object) == "dir") {
//             $this->rrmdir($dir . "/" . $object);

//             // $file = FileManagerModel::where('file_name', $object)->select('file_id')->first();
//             // $file_id = @$file->file_id;
//             // FileManagerModel::where('file_id', $file_id)->delete();

//           }
//           else {

//             // $file = FileManagerModel::where('file_name', $object)->select('file_id')->first();
//             // $file_id = @$file->file_id;
//             // FileManagerModel::where('file_id', $file_id)->delete();

//             unlink($dir . "/" . $object);
//           }
//         }
//       }

//       reset($objects);
//       rmdir($dir);
//     }
//   }

//   public function create_file(Request $request)
//   {

//     $inputs = $request->all();
//     $curUploadPath = $inputs['current_url']; //file manager Id
//     $path_ids = $inputs['path_ids']; //Path ids
//     if ($curUploadPath > 0 && $path_ids != "") {

//       $chkUserAndSubMapped=$this->chkUserAndSubMapped();

//         if($chkUserAndSubMapped['status']==1)
//         {

//           $cl_stf_id=$chkUserAndSubMapped['cl_stf_id'] ;
//           $clg_stf_sub_id=$chkUserAndSubMapped['clg_stf_sub_id'] ;
//           $staffNameFolder=$chkUserAndSubMapped['staffNameFolder'];
//           $chkPhysicallyFlderExit = $this->getFileManagerPathWithIds($path_ids);
//            if ($chkPhysicallyFlderExit != '0') {

//             $destinationPath = base_path() . '/public/uploads/file_manager/' . $chkPhysicallyFlderExit;

//           $chkParentchildvalCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder'])->get()->count();
//               if ($chkParentchildvalCnt == 0) {
//                 echo "Invalid file path";
//                 exit;
//               }
//               else
//               {

//                 $chkParentchildval =clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder'])->get();

//                 $chkParentchildvalAccessCnt = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get()->count();
//                 if ($chkParentchildvalAccessCnt == 0) //chk the folder path uploaded is posible
//                 {
//                   echo "File upload denied";
//                   exit;
//                 }

//                 $chkParentchildvalAccessVal = clgFileManagerModel::where(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'clg_stf_file_id' => $curUploadPath, 'file_type' => 'folder', 'folder_access' => 1])->get();

//                   $course_id=$chkParentchildvalAccessVal[0]->course_id;
//                   $semester_id=$chkParentchildvalAccessVal[0]->semester_id;
//                   $sub_id=$chkParentchildvalAccessVal[0]->sub_id;
//                   $parent_id= $chkParentchildvalAccessVal[0]->parent_id;
//                   $academic_year=$chkParentchildvalAccessVal[0]->academic_year;

//                   if (!empty($_FILES)) {
//                   $tmpFile = $_FILES['file']['tmp_name'];
//                   $filename = $destinationPath . $_FILES['file']['name'];
//                   if (!file_exists($filename)) {
//                   $actual_filename = time() . '-' . strtolower($_FILES['file']['name']);
//                   $filename = $destinationPath . $actual_filename;
//                   move_uploaded_file($tmpFile, $filename);
//                   }
//                   else {
//                   $actual_filename = time() . '-' . strtolower($_FILES['file']['name']);
//                     $filename = $destinationPath . $actual_filename;
//                     move_uploaded_file($tmpFile, $filename);
//                   } //chk below physically file upload or not
//                   if (file_exists($destinationPath . $actual_filename)) {
                  
//                   $InserFlder=ClgFileManagerModel::insert(['cl_stf_id' => $cl_stf_id, 'clg_stf_sub_id' => $clg_stf_sub_id, 'course_id' => $course_id, 'semester_id' => $semester_id, 'sub_id' => $sub_id, 'parent_id' => $curUploadPath, 'academic_year' => $academic_year, 'file_type' => 'file', 'file_name' => $actual_filename]);
//                   echo "success";
//                   exit;
//                   }
//                   else {
//                     echo "File not uploaded successfully";
//                     exit;
//                   }
//                   }
//                   else {
//                     echo "Upload file is empty";
//                     exit;
//                   }

 
 
//               }
//            }
//            else
//            {
//                echo "Invalid request";
//                exit;

//            }


//         }
//         else
//         {


//          echo "Invalid subject mapping";
//           exit;

//         }
//     }
//     else
//     {
//       echo "Invalid request / file upload is  denied";
//       exit;
//     }
//   }

//   public function getuserAccessPath()
//   {
//     $getStffCode = auth()->guard('admin')->user()->email;
//     $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//     if ($chkStfMapCnt == 0) {
//       return 0;
//       exit;
//     }
//     else {
//       $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//       $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
//       $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
//       if ($chkSubMasterCnt == 0) {
//         return 0;
//         exit;
//       }
//       else {
//         $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
//         if ($chkActiveCnt[0]->active == 0) {
//           return 0;
//           exit;
//         }

//         return $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
//       }
//     }
//   }

//   public function getFileManagerPath($fmRowId)
//   {
//     $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get()->count();
//     if ($getUrlPathCnt > 0) {
//       $getUrlPath = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get();
//       $sch_cls_id = $getUrlPath[0]->sch_cls_id;
//       $sec_id = $getUrlPath[0]->sec_id;
//       $sub_id = $getUrlPath[0]->sub_id;
//       $parentId = $getUrlPath[0]->parent_id;
//       if ($sch_cls_id == 0 && $sec_id == 0 && $sub_id == 0) {
//         return 0;
//         exit;
//       }
//       else {
//         $Path = '';
//         if ($sch_cls_id > 0) {
//           $getSclClsMaster = SchClassModel::where('sch_cls_id', $sch_cls_id)->get();
//           if (isset($getSclClsMaster[0]->sch_class) && $getSclClsMaster[0]->sch_class != "") {
//             $Path = $getSclClsMaster[0]->sch_class;
//           }
//         }

//         if ($sec_id > 0) {
//           $getSection = SchSectionModel::where(['sec_id' => $sec_id])->get();
//           if (isset($getSection[0]->section_name) && $getSection[0]->section_name != "") {
//             $Path.= '/' . $getSection[0]->section_name;
//           }
//         }

//         if ($sub_id > 0) {
//           $getSubject = SclSubjectModel::where(['sub_id' => $sub_id])->get();
//           if (isset($getSubject[0]->sub_name) && $getSubject[0]->sub_name != "") {
//             $Path.= '/' . $getSubject[0]->sub_name;
//           }
//         }

//         $getRootPath = $this->getuserAccessPath();
//         if ($getRootPath == '0') {
//           return 0;
//           exit;
//         }
//         else {
//           return $getRootPath . '/' . $Path;
//         }
//       }
//     }
//   }

//   public function getFileManagerPathWithIds($fmRowIds)
//   {
//     $expIds = explode(',', $fmRowIds);
//     if (count($expIds) > 0) {
//       $urlPath = '';
//       for ($i = 0; $i < count($expIds); $i++) {
//         $fmRowId = $expIds[$i];
//         $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
//         if ($getUrlPathCnt > 0) {
//           $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();
//           $urlPath.= $getUrlPath[0]->file_name . '/';
//         }
//       }

//       $getRootPath = $this->chkUserAndSubMapped(); //$this->getuserAccessPath();
//       if ($getRootPath['status'] == '1') {
//         return $getRootPath['staffNameFolder'] . '/' . $urlPath;
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
//   }

//   function getFileManagerChkValidUser($fmRowId)
//   {
//     $getStffCode = auth()->guard('admin')->user()->email;
//     $chkStfMapCnt = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//     if ($chkStfMapCnt == 0) {
//       return 0;
//       exit;
//     }
//     else {
//       $getStaffDetails = SchStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//       $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
//       $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
//       if ($chkSubMasterCnt == 0) {
//         return 0;
//         exit;
//       }
//       else {
//         $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
//         if ($chkActiveCnt[0]->active == 0) {
//           return 0;
//           exit;
//         }
//         else {
//           $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId, 'scl_stf_id' => $scl_stf_id, ])->get()->count();
//           if ($getUrlPathCnt > 0) {
//             return 1;
//             exit;
//           }
//           else {
//             return 0;
//           }
//         }

//         // return $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;

//       }
//     }
//   }

//   /** College function start **/

//   public function chkUserAndSubMapped()
//   {
  
//     $result['status']=0;
//     $result['errMsg']='';
//     $getStffCode = auth()->guard('admin')->user()->email;
//     $chkStfMapCnt = ClgStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get()->count();
//     if ($chkStfMapCnt == 0) {
//       $result['Permission denied'];
//     }
//     else
//     {
        
//       $getStaffDetails = ClgStaffModel::where(['staff_code' => $getStffCode, 'active' => 1])->get();
//       $cl_stf_id = $getStaffDetails[0]->cl_stf_id;
//       $staffNameFolder = $getStffCode . '_' . $getStaffDetails[0]->staff_name;
//       $chkSubMasterCnt = ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_master.cl_stf_id' =>$cl_stf_id,'at_college_staff_subject_master.active'=>1,'at_college_staff_subject_mapping.active'=>1])->get()->count();
//       if($chkSubMasterCnt>0)
//       {
//          $chkSubMaster= ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_master.cl_stf_id' => $cl_stf_id,'at_college_staff_subject_master.active'=>1,'at_college_staff_subject_mapping.active'=>1])->get();
//          $result['status']=1;
//          $result['cl_stf_id']=$cl_stf_id;
//          $result['clg_stf_sub_id']=isset($chkSubMaster[0]->clg_stf_sub_id)?$chkSubMaster[0]->clg_stf_sub_id:'';
//          $result['staffNameFolder']=strtolower($staffNameFolder);
//          $chkStaffFileManagerCnt=ClgFileManagerModel::where(['cl_stf_id'=>$cl_stf_id,'clg_stf_sub_id'=>$chkSubMaster[0]->clg_stf_sub_id,'active'=>1])->get()->count();
//          if($chkStaffFileManagerCnt>0)
//          {

//          }
//          else{
//           $result['status']=0;
//           $result['errMsg']='Staff file manager not found';
//          } 
//       }
//       else
//       {
//          $result['errMsg']="Subject could not be mapped/blocked";
//       }
//     }
//     return $result;
//   }

}