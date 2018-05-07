<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
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
use Redirect;
use Session;
use DB;
use Excel;
use File;


class ClgStfSubjectMapController extends Controller
{

   public function index(){
        $uPermission=getUserPermission('clgstaffsubmapp','college');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }
        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
        $clgSubjectAll = ClgStfSubMasterModel::leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where('at_college_staff_subject_master.active',0)->get();
        } else {
        $clgSubjectAll = ClgStfSubMasterModel::leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')->where('at_college_staff_subject_master.active',1)->get();
        }
        $active_count = ClgStfSubMasterModel::where('active', 1)->count();
        $inactive_count = ClgStfSubMasterModel::where('active', 0)->count();
        return view('backend.clgstfsubjectmapping.index', compact('clgSubjectAll', 'active_count', 'inactive_count','uPermission'));
   }
   public function create() {
          $uPermission=getUserPermission('clgstaffsubmapp','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
          $staffAll=ClgStaffModel::where('active',1)->get();
          $getCourse=ClgCourseModel::leftJoin('at_college_subject_master','at_college_subject_master.course_id','=','at_college_course_master.course_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_depart_master.gr_id')
          ->where('at_college_course_master.active',1)->groupBy('at_college_course_master.course_id')->get();
         return view('backend.clgstfsubjectmapping.add',compact('staffAll','getCourse'));
    }


    public function depdropboxSection(Request $request) //get class list for staff
    {
 
        $inputs=$request->all();
        if($request['mode']=='single')
        {
            $selectCourId=$request['selectCour'];
        }
        else
        {
            $selectCourId=$request['selectCour'];
        }
      $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where(['at_college_subject_master.course_id'=>$selectCourId,'at_college_course_master.active'=>1,'at_college_subject_master.active'=>1])->orderBy('at_college_subject_master.semester_id')->groupBy('at_college_subject_master.semester_id')->get(array(DB::raw('group_concat(at_college_subject_master.subject_name) as subNames'),'at_college_course_master.course_name','at_college_depart_master.depart_name','at_college_graduate_master.grad_name','at_college_subject_master.semester_id',DB::raw('group_concat(concat(at_college_subject_master.subject_name  ,"@@@" , at_college_subject_master.sub_id) ) as subNames')));
        echo json_encode($clgCourseSubAll);
       
    }
     public function store(Request $request) {
            $uPermission=getUserPermission('clgstaffsubmapp','college');
            if($uPermission[0]->file_add==0)
            {return view('backend.pageDined'); }

            $inputs=$request->all();
            $StaffId=$inputs['selectstaffCls'];
            $courId=$inputs['selectCour'];
            $explSemSub=explode(',', $inputs['hidcls']);
            $destinationPath = public_path('uploads/file_manager/');
            $GetAcademicYear=ClgAcademicYearModel::get();
            $AcademicYear=$GetAcademicYear[0]->academic_year;
            if($StaffId>0 && $courId>0 && count($explSemSub)>0  )
            {
               $chkStfId=ClgStfSubMasterModel::where('cl_stf_id',$StaffId)->get()->count();
               if($chkStfId==0)
               {
                 $inserMaster=ClgStfSubMasterModel::create(['cl_stf_id'=>$StaffId]);
                 $lstInsertId=$inserMaster->clg_stf_sub_id ;
               }
               else
               {
                 $chkStfId=ClgStfSubMasterModel::where('cl_stf_id',$StaffId)->get();
                 $lstInsertId=$chkStfId[0]->clg_stf_sub_id ;
               }

               if($lstInsertId>0)
               { 

                    for($i=0;$i<count($explSemSub);$i++)
                    { 
                      $splSemSub=explode('@#@', $explSemSub[$i]);
                      $semId=isset($splSemSub[0])?$splSemSub[0]:'';
                      $SubId=isset($splSemSub[1])?$splSemSub[1]:'';

                          if($semId>0 &&  $SubId>0 )
                          {
 
                          $getPathFolder=$this->PathFolderFunction($StaffId,$courId,$semId);
                          $getMapExistSub=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$lstInsertId,'semester_id'=>$semId,'course_id'=>$courId,'sub_id'=>$SubId])->get()->count();
                          if($getPathFolder=='0')
                          {
                              Session::flash('message', 'Invalid request.');
                              Session::flash('alert-class', 'alert-warning');
                              return redirect('admin/clgstaffsubmapp/add')->withInput($request->all());
                              exit;

                          }
                          else
                          {

                            $fileManagerPathArr=explode('/',$getPathFolder);
                            $fileManagerPathCourse=isset($fileManagerPathArr[0])?$fileManagerPathArr[0]:'';

                            //$fileManagerPathSem=


                            $getSubjectName=ClgSubjectModel::where('sub_id',$SubId)->get();
                            $subjectName=isset($getSubjectName[0]->subject_name)?$getSubjectName[0]->subject_name:'';
                             $directory=$getPathFolder.'/semester-'.$semId.'/'.$subjectName; 
                            if($subjectName=='')
                            {
                               $directory=''; 
                            }

                           
                          }

                         if (!File::exists(strtolower($destinationPath.'/'.$directory) )) {  
                            File::makeDirectory(strtolower($destinationPath . '/' . $directory), 0777, true);
                          }
                          if($getMapExistSub==0)
                          {
                          $getMapInsert=ClgStfSubMapModel::insert(['clg_stf_sub_id'=>$lstInsertId,'semester_id'=>$semId,'course_id'=>$courId,'sub_id'=>$SubId]);
                          }

                      }

                    }
                    /*File Manager start */
                    $insertCourse=$this->insertCourseFileManager($lstInsertId,$StaffId,strtolower($fileManagerPathCourse.'/'));
                    $insertSemester=$this->insertSemesterFileManager($lstInsertId,$StaffId,strtolower($getPathFolder));
                    $insertSubject=$this->insertSubjectFileManager($lstInsertId,$StaffId,strtolower($getPathFolder));

                   
                    Session::flash('message', 'Subject has been successfully saved');
                    Session::flash('alert-class', 'alert-success');
                    return redirect('admin/clgstaffsubmapp/add');
                }
               else
               {
                  Session::flash('message', 'Invalid request.');
                  Session::flash('alert-class', 'alert-warning');
                  return redirect('admin/clgstaffsubmapp/add')->withInput($request->all());
               }
            
            }
            else
            {
              Session::flash('message', 'Invalid request.');
              Session::flash('alert-class', 'alert-warning');
              return redirect('admin/clgstaffsubmapp/add')->withInput($request->all());
            }
           
}

public function show($id) { 

       $uPermission=getUserPermission('clgstaffsubmapp','college');
            if($uPermission[0]->file_view==0)
            {return view('backend.pageDined'); } 

        $clgStaffCnt = ClgStfSubMasterModel::leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')
         ->where('at_college_staff_subject_master.clg_stf_sub_id',$id)->get()->count();

         if($clgStaffCnt>0)
         {
            $clgStaff = ClgStfSubMasterModel::leftJoin('at_college_staff_master','at_college_staff_master.cl_stf_id','=','at_college_staff_subject_master.cl_stf_id')
         ->where('at_college_staff_subject_master.clg_stf_sub_id',$id)->get();

            $subMapId=$clgStaff[0]->clg_stf_sub_id;
            $staffId=$clgStaff[0]->cl_stf_id;
            $stfName=$clgStaff[0]->staff_name;
            $stfCode=$clgStaff[0]->staff_code;
            // $chkStafSubMapCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->where(['at_college_staff_subject_master.clg_stf_sub_id'=>$id])->orderBy('at_college_staff_subject_mapping.course_id','ASC')->orderBy('at_college_staff_subject_mapping.semester_id','ASC')->groupBy('at_college_staff_subject_mapping.course_id')->get(array(DB::raw('group_concat(concat(at_college_staff_subject_mapping.course_id,"@@@",at_college_staff_subject_mapping.semester_id  ,"@@@" , at_college_staff_subject_mapping.sub_id) ) as subNames')));

            $chkStafSubMap=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->where(['at_college_staff_subject_master.clg_stf_sub_id'=>$id])->orderBy('at_college_staff_subject_mapping.course_id','ASC')->orderBy('at_college_staff_subject_mapping.semester_id','ASC')->get();

          // $chkStafSubMapCnt=ClgStfSubMasterModel::leftJoin('at_college_staff_subject_mapping','at_college_staff_subject_mapping.clg_stf_sub_id','=','at_college_staff_subject_master.clg_stf_sub_id')->leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->where(['at_college_staff_subject_master.clg_stf_sub_id'=>$id])->orderBy('at_college_staff_subject_mapping.course_id','ASC')->orderBy('at_college_staff_subject_mapping.semester_id','ASC')->get(array(DB::raw('group_concat(concat(at_college_course_master.course_name," Semester-",at_college_staff_subject_mapping.semester_id  ,"@@@" , at_college_subject_master.subject_name) ) as subNames'),'at_college_staff_subject_mapping.course_id'));
          return view('backend.clgstfsubjectmapping.show',compact('chkStafSubMap','stfName','stfCode'));
         }
         else
         {
          Session::flash('message', 'No data found');
          Session::flash('alert-class', 'alert-warning');
          return redirect()->route('admin/clgstaffsubmapp');
        }
       
   }

    public function actionupdate(Request $request) {
        $uPermission=getUserPermission('clgstaffsubmapp','college');
        if($uPermission[0]->file_delete==0)
        {return view('backend.pageDined'); }
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Staff has been successfully inactivated.";
            $redirect_value = "admin/clgstaffsubmapp";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/clgstaffsubmapp/?token=inactive";
        }
        else
        {
            $msg_value="Invalid request";
        }
            
        
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
             ClgStfSubMasterModel::select('*')->where('clg_stf_sub_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }


  

public function PathFolderFunction($StaffId=null,$courId=null,$semId=null)
{
   $getStaffDetailsCnt=ClgStaffModel::where(['cl_stf_id'=>$StaffId,'active'=>1])->get()->count();
   if($getStaffDetailsCnt>0)
   {

       $getStaffDetails=ClgStaffModel::where('cl_stf_id',$StaffId)->get();
       $staffNameFolder=strtolower($getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name);
       $getCourseNameCnt=ClgCourseModel::where(['course_id'=>$courId,'active'=>1])->get()->count();
       if($getCourseNameCnt>0)
       {
        $getCourseName=ClgCourseModel::where(['course_id'=>$courId,'active'=>1])->get() ;
        $courseName=$getCourseName[0]->course_name;

        return  $staffNameFolder.'/'.$courseName;
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

function insertCourseFileManager($lstInsertId=null,$StaffId=null,$dirPath=null)
{

  $getCourseFM=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$lstInsertId])->distinct()->get(array('course_id'));

  $getAcademicYear=ClgAcademicYearModel::get();
  $academic_year=isset($getAcademicYear[0]->academic_year)?$getAcademicYear[0]->academic_year:'';
   foreach ($getCourseFM as $key => $value) {

    $courId=$value->course_id;
    $getCourseName=ClgCourseModel::where('course_id',$courId)->get();
    $courseName=isset($getCourseName[0]->course_name)?$getCourseName[0]->course_name:'';

    $checkCourFMExit=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'semester_id'=>0,'sub_id'=>0,'file_type'=>'folder','parent_id'=>0,'folder_access'=>0])->get()->count();

    if($checkCourFMExit>0)
    {
       if($courseName !=""){
       $checkCourFMRowId=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'semester_id'=>0,'sub_id'=>0,'file_type'=>'folder','parent_id'=>0,'folder_access'=>0])->get();

       $clg_stf_file_id=$checkCourFMRowId[0]->clg_stf_file_id;
       $updateCourse=ClgFileManagerModel::where('clg_stf_file_id',$clg_stf_file_id)->update(['file_name'=>strtolower($courseName)]);
     }
 
    }
    else
    {
     
      if($courseName!='')
      {

        $inserCourse=ClgFileManagerModel::insert(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'file_name'=>strtolower($courseName),'file_type'=>'folder','academic_year'=>$academic_year,'file_path'=>"$dirPath"]);
      }
    }



    
  }

  return 1;

}
function insertSemesterFileManager($lstInsertId=null,$StaffId=null,$semPath=null)
{

    $getSemesterMap=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$lstInsertId])->groupBy(['course_id','semester_id'])->get();
    $getAcademicYear=ClgAcademicYearModel::get();
    $academic_year=isset($getAcademicYear[0]->academic_year)?$getAcademicYear[0]->academic_year:'';
    foreach ($getSemesterMap as $key => $value) {

      $courId=$value->course_id;
      $semesterId=$value->semester_id;
      $getCourseName=ClgCourseModel::where('course_id',$courId)->get();

      $checkCourFMExit=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'sub_id'=>0,'file_type'=>'folder','folder_access'=>0,'semester_id'=>$semesterId])->whereNotIn('parent_id',[0])->get()->count();
      if($checkCourFMExit>0)
      {



      }
      else
      {

        $getParentId=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'semester_id'=>0,'sub_id'=>0,'file_type'=>'folder','parent_id'=>0,'folder_access'=>0])->get();

        $parent_id=isset($getParentId[0]->clg_stf_file_id)?$getParentId[0]->clg_stf_file_id:'';
        $fileName='semester-'.$semesterId;

        if($parent_id!=''){

          $semPaths=$semPath.'/';

          $insertSem=ClgFileManagerModel::insert(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'file_name'=>$fileName,'file_type'=>'folder','academic_year'=>$academic_year,'parent_id'=>$parent_id,'semester_id'=>$semesterId,'file_path'=>"$semPaths"]);


        }

      }
   
    }


    return 1; 

}
public function  insertSubjectFileManager($lstInsertId=null,$StaffId=null,$subPath=null)
{

   $getSubjectMap=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$lstInsertId])->get();
   $getAcademicYear=ClgAcademicYearModel::get();
   $academic_year=isset($getAcademicYear[0]->academic_year)?$getAcademicYear[0]->academic_year:'';
    foreach ($getSubjectMap as $key => $value) {

      $courId=$value->course_id;
      $semesterId=$value->semester_id;
      $subId=$value->sub_id;
      $getSubName=ClgSubjectModel::where('sub_id',$subId)->get();
      $subjectName=isset($getSubName[0]->subject_name)?$getSubName[0]->subject_name:'';
 
      if($subjectName !="")
      {

        $getParentId=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'semester_id'=>$semesterId,'sub_id'=>0,'file_type'=>'folder','folder_access'=>0])->whereNotIn('parent_id',[0])->get();
 
          $parent_id=isset($getParentId[0]->clg_stf_file_id)?$getParentId[0]->clg_stf_file_id:'';


           if($parent_id!=""){

             $checkSubFMExit=ClgFileManagerModel::where(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'sub_id'=>$subId,'file_type'=>'folder','semester_id'=>$semesterId,'parent_id'=>$parent_id])->get()->count();
             if($checkSubFMExit>0)
             {


             }
             else
             {

                $subSemPath=$subPath.'/semester-'.$semesterId.'/';
                $insertSubject=ClgFileManagerModel::insert(['course_id'=>$courId,'clg_stf_sub_id'=>$lstInsertId,'cl_stf_id'=>$StaffId,'file_name'=>strtolower($subjectName),'file_type'=>'folder','academic_year'=>$academic_year,'parent_id'=>$parent_id,'semester_id'=>$semesterId,'sub_id'=>$subId,'folder_access'=>1,'file_path'=>"$subSemPath"]);

             }

           }

      }
  
     }

}


}
