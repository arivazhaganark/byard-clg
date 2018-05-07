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
use App\Model\backend\Admin;
use Redirect;
use Session;
use DB;
use Excel;


class ClgSubjectController extends Controller
{
     
    public function index(){

        $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }
 
        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {

           $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where('at_college_subject_master.active',1)->groupBy('at_college_subject_master.course_id')->get();

       
        } else {

           $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where('at_college_subject_master.active',1)->groupBy('at_college_subject_master.course_id')->get();

        }

        $active_count = ClgCourseModel::where('active', 1)->count();
        $inactive_count = ClgCourseModel::where('active', 0)->count();
       return view('backend.clgsubject.index', compact('clgCourseSubAll', 'active_count', 'inactive_count','uPermission'));
        
    }

    public function create() {
        $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $yearCourse=ClgAcademicYearModel::get();
        $year=$yearCourse[0]->academic_year;
        $getCourse=ClgCourseModel::leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_depart_master.gr_id')
            ->where('at_college_course_master.active',1)->get();
        return view('backend.clgsubject.add',compact('getCourse','year'));
    }
    

    public function store(Request $request) {
        $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_add==0)
        {return view('backend.pageDined'); }
         $inputs=$request->all();
         $subName=trim($inputs['subName']);
         $selectSem=$inputs['selectSem'];
         $courseId=$inputs['selectCourse'];
         $ayear=$inputs['ayear'];
         $chkCnt = ClgSubjectModel::where(['course_id'=>$courseId,'subject_name'=>$subName,'semester_id'=>$selectSem])->get()->count();
     
        if($chkCnt==0)
        {
      
        $sectiondata = ClgSubjectModel::insert(['course_id'=>$courseId,
                                               'subject_name'=>$subName,
                                               'semester_id'=>$selectSem,
                                               
                           ]);
         
        Session::flash('message', 'Subject created successfully .');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/clgsubject/add');

        }
        else
        {
            Session::flash('message', 'Subject already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/clgsubject/add')->withInput($request->all());
        }
   
}


   public function viewalledit($subject_id,$str=null)
   {
        $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_edit==0)
        {return view('backend.pageDined');} 

        $input = Input::all();
        if (isset($str) && $str == 'inactive') {
           $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where(['at_college_subject_master.active'=>0,'at_college_subject_master.course_id'=>$subject_id])->orderBy('at_college_subject_master.semester_id')->get();
        
        } else {

           $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where(['at_college_subject_master.active'=>1,'at_college_subject_master.course_id'=>$subject_id])->orderBy('at_college_subject_master.semester_id')->get();
        }
       
        $active_count = ClgSubjectModel::where(['active'=>1,"course_id"=>$subject_id])->count();
        $inactive_count = ClgSubjectModel::where(['active'=>0,"course_id"=>$subject_id])->count();       
      
       return view('backend.clgsubject.viewall', compact('clgCourseSubAll', 'active_count', 'inactive_count','subject_id','str','uPermission'));
   }
   public function bulkstore(Request $request)  
   {
    $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_add==0)
        {return view('backend.pageDined'); }

    $inputs=$request->all();
    if(Input::hasFile('import_file')) 
    {

      $path = Input::file('import_file')->getRealPath();
      $data = Excel::load($path, function($reader) { 
                 })->get();
        $depErrArr=[];
        $courErrArr=[]; 
        $subErrArr=[]; 
        $subSuccessArr=[];
      if(!empty($data) && $data->count())
      {
  
         //$getDepartment=$this->InsertDepartment();
        foreach ($data as $key => $value) {

         if(isset($value->graduation) && isset($value->department) &&  isset($value->coursename) && isset($value->totalyear) && isset($value->semester) && isset($value->subject))
           {  
              if( $value->graduation !="" && $value->department !=""  &&  $value->coursename !="" &&  $value->totalyear >0 &&  $value->semester >0 &&  $value->subject !="" )
              {

                 $graduationVal=trim($value->graduation);
                 $departmentVal=trim($value->department);
                 $coursenameVal=trim($value->coursename);  
                 $totalyearVal=trim($value->totalyear); 
                 $semester=trim($value->semester); 
                 $subject=trim($value->subject); 
                 
                 $getDepartment=$this->InsertDepartment(strtoupper($graduationVal),$departmentVal);
                 if($getDepartment['status']==1 )
                 {

                     $gr_id=$getDepartment['gr_id'];
                     $dep_id=$getDepartment['dep_id'];
                     if($gr_id>0 && $dep_id>0 )
                     {


                      $getCourse=$this->InsertCourse($gr_id,$dep_id,$coursenameVal,$totalyearVal);
                      if($getCourse['status']==1)
                      {
                         
                        $courseId=$getCourse['course_id'];
                        $totYear=$getCourse['totYear'];
 
                        $getSubject=$this->InsertSubject($gr_id,$dep_id,$courseId,$subject,$totYear,$semester);

                        if($getSubject['status']==1)
                        {

                          $subSuccessArr[]=$subject;
                           

                        }
                        else
                        {
                           $subErrArr[]=$getSubject['errorMsg'];
                        }

                      }
                      else
                      {
                       
                        $courErrArr[]='course error';

                      }



                     }
                     else
                     {
                      $depErrArr[]='dep id/grad id null';

                     }


                 }
                 else
                 {
                 $depErrArr[]=$getDepartment['errorMsg'];
                 }

              }

           }

         

        }

        Session::flash('message', 'Subject insert successfully total subject inserted = '.count($subSuccessArr));
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/clgsubject/add');


      }
      else
      {
        Session::flash('message', 'File content is empty!');
        Session::flash('alert-class', 'alert-warning');
        return Redirect::to('admin/clgsubject/add');
      }
    }
    else
    {

      Session::flash('message', 'Please upload xl,xls file ');
      Session::flash('alert-class', 'alert-warning');
      return Redirect::to('admin/clgsubject/add');


    }


 
   }
   public function InsertSubject($gr_id=null,$dep_id=null,$courseId=null,$subject=null,$totYear=null,$semester=null){


    $subjectArr['status']=0;
    $subjectArr['errorMsg']='';
    $subjectArr['course_id']=0;

    $splitTotSemId=$totYear*2;
    if($splitTotSemId>0) //Chk semester not greater then total semester
    {
      if($splitTotSemId>=$semester)
      {

         $chkCnt = ClgSubjectModel::where(['course_id'=>$courseId,'subject_name'=>$subject,'semester_id'=>$semester])->get()->count();
         if($chkCnt>0)
         {
          $subjectArr['errorMsg']='Subject already exists';
         }
         else
         {

          $subjectData = ClgSubjectModel::insert(['course_id'=>$courseId,
                                               'subject_name'=>$subject,
                                               'semester_id'=>$semester,
                                               
                           ]);
          $subjectArr['errorMsg']='subject inserted';
          $subjectArr['status']=1;

         }


      }
      else
      {
        $subjectArr['errorMsg']='Invalid semester id';
      }

    }
    else
    {
      $subjectArr['errorMsg']='Invalid semester';
    }

    return $subjectArr;





   }
   public function InsertCourse($gr_id=null,$dep_id=null,$coursenameVal=null,$totalyearVal=null)
   {

    $courArr['status']=0;
    $courArr['errorMsg']='';
    $courArr['course_id']=0;
    $courArr['totYear']=0;
   
    $chkCnt = ClgCourseModel::where(['gr_id' => $gr_id, 'dep_id' => $dep_id, 'course_name' => $coursenameVal])->get()->count();
    if($chkCnt>0)
    {
      $chkCntVal = ClgCourseModel::where(['gr_id' => $gr_id, 'dep_id' => $dep_id, 'course_name' => $coursenameVal])->get();
      $courArr['course_id']=$chkCntVal[0]->course_id;
      $courArr['totYear']=$chkCntVal[0]->year_id;
      $courArr['status']=1;
    }
    else
    {

      $insert_arrVal['gr_id']=$gr_id;
      $insert_arrVal['dep_id']=$dep_id;
      $insert_arrVal['course_name']=$coursenameVal;
      $insert_arrVal['year_id']=$totalyearVal;
      $data = ClgCourseModel::create($insert_arrVal);
      $courArr['course_id']=$data->course_id;
      $courArr['totYear']=$totalyearVal;
      $courArr['status']=1;
    }

   return $courArr ;



   }
   public function InsertDepartment($gradu=null,$depart=null)
   {

    $depArr['status']=0;
    $depArr['errorMsg']='';
    $depArr['gr_id']=0;
    $depArr['dep_id']=0;

    $getGrIdCnt=GraduateModel::where(['grad_name'=>"$gradu","active"=>1])->count();
    if($getGrIdCnt>0)
    {

      $getGrId=GraduateModel::where(['grad_name'=>"$gradu","active"=>1])->get();
      $gr_id=isset($getGrId[0]->gr_id)?$getGrId[0]->gr_id:'0';
      $grad_name=isset($getGrId[0]->grad_name)?$getGrId[0]->grad_name:'';
      if($gr_id>0 && $grad_name !="")
      {

          $chkCnt = ClgDepartModel::where(['gr_id' => $gr_id, 'depart_name' => $depart])->get()->count();
          if($chkCnt>0)
          {

            $chkCntVal = ClgDepartModel::where(['gr_id' => $gr_id, 'depart_name' => $depart])->get();
            $depArr['status']=1;
            $depArr['gr_id']=$gr_id;
            $depArr['dep_id']=isset($chkCntVal[0]->dep_id)?$chkCntVal[0]->dep_id:'0'; 
          }
          else
          {

              $insert_arr['gr_id']=$gr_id;
              $insert_arr['depart_name']=$depart;
              $data = ClgDepartModel::create($insert_arr);

              $depArr['status']=1;
              $depArr['gr_id']=$gr_id;
              $depArr['dep_id']=$data->dep_id;  

          }

 
      }
      else
      {
         $depArr['errorMsg']='Invalid graduation';
      }

    }
    else
    {
     
      $depArr['errorMsg']='Invalid graduation';

    }
    return $depArr;
  }

   public function bulkstoreOld(Request $request) {
         $inputs=$request->all();
         $graId=$inputs['selectBulkGrd'];
         //$divId=$inputs['selectBulkDiv'];
         $depId=$inputs['selectBulkDept'];
         $courseId=$inputs['selectBulkCourse'];
         $ayear=$inputs['ayearBulk'];
         $join_year=date('Y');

            if(Input::hasFile('import_file')){

                $insetCnt=0;
                $updateCnt=0;
                $toralCnt=0;
                $path = Input::file('import_file')->getRealPath();
                $data = Excel::load($path, function($reader) { 
                 })->get();
                 if(!empty($data) && $data->count()){

                    foreach ($data as $key => $value) {

                        if(isset($value->rollno) && isset($value->name))
                        {
                            if($value->rollno !="" && $value->name !="" )
                            {

                                $srollno=$value->rollno;
                                $sName=$value->name;
                                $GetCodeUnique = ClgStudentModel::where(['roll_no' => "$srollno"])->get()->count();
                                $GetAdminLoginUniqe=Admin::where(['email'=>$srollno])->get()->count();
                                 
                                if($GetCodeUnique==0 && $GetAdminLoginUniqe==0 )
                                {

                                   $sectiondata = ClgStudentModel::insert(['gr_id'=>$graId,
                                                                            'dep_id'=>$depId,
                                                                            //'division_id'=>$divId,
                                                                            'course_id'=>$courseId,
                                                                            'roll_no'=>$srollno,
                                                                            'student_name'=>$sName,
                                                                            'dob'=>'',
                                                                            'join_year'=>$join_year,
                                                                            'academic_year'=>$ayear,
                                    ]);

                                    $adminData=Admin::create(['name'=>"$sName",'email'=>"$srollno",'password'=>bcrypt($srollno),'usertype'=>'CS']); //CS::Cpllege Student
                                    $insetCnt++;


                                }
                                else
                                {
                                  $updateCus=ClgStudentModel::where('roll_no', $srollno)
                                    ->update(["student_name"=>$sName]);

                                  $adminData=Admin::where(['email'=>"$srollno"])->update(['name'=>"$sName"]); 
                                  $updateCnt++;
                                }
                                $toralCnt++;
   
                              }

                        }
                        else
                        {

                            Session::flash('message', 'Invalid header!');
                            Session::flash('alert-class', 'alert-warning');
                            return Redirect::to('admin/clgsubject/add');
                            exit;
                        }

                        
                       
                    }


                    if($toralCnt>0) 
                        {

                            Session::flash('message', "Student added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                            Session::flash('alert-class', 'alert-success');
                            return Redirect::to('admin/clgsubject/add');
                            exit;
                        }
                
                 }
                 else
                 {

                    Session::flash('message', 'File content is empty!');
                    Session::flash('alert-class', 'alert-warning');
                    return Redirect::to('admin/clgsubject/add');
                 }

            } 
            else
            {

                Session::flash('message', 'Please upload xl,xls file ');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/clgsubject/add');

            }

   }
  
     public function update(Request $request) {
      $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_edit==0 )
        {
          return view('backend.pageDined');
        }
 
       $inputs=$request->all();
       $subName=trim($inputs['subName']);
       $stuRwId=$inputs['hidid'];
       if($subName !="" && $stuRwId>0 )
       {

           $getValidCnt=ClgSubjectModel::where('sub_id',$stuRwId)->get()->count();
           if($getValidCnt>0)
           {
                $getValid=ClgSubjectModel::where('sub_id',$stuRwId)->get();
                $semId=$getValid[0]->semester_id;
                $courseId=$getValid[0]->course_id;
                $getExits=ClgSubjectModel::where(['course_id'=>$courseId,
                                              'semester_id'=>$semId,
                                               'subject_name'=>$subName])->whereNotIn('sub_id', [$stuRwId])->get()->count();
                if($getExits==0)
                {

                  $updateSubject=ClgSubjectModel::where('sub_id',$stuRwId)->update(['subject_name'=>$subName]);
                  Session::flash('message', 'Subject updated successfully');
                  Session::flash('alert-class', 'alert-success');
                }
                else
                {
                    Session::flash('message', 'Subject already exists');
                    Session::flash('alert-class', 'alert-warning');
                }

               return redirect("admin/clgsubject/subedit/$stuRwId") ;  
           }
           else
           {
            Session::flash('message', 'Invalid subject ');
            Session::flash('alert-class', 'alert-warning');
            return redirect("admin/clgsubject") ;  
           }
       }
       else
       {
          return redirect("admin/clgsubject") ;  

       }
    
    }

    public function dropboxsemester(Request $request)
    {
 
      $inputs=$request->all();
      if($request['mode']=='single')
      {
        $cour_id=$request['selectCourse'];
  
      }
      else
      {
        $cour_id=$request['selectCourse'];
      }
      
      $getDivisions=ClgCourseModel::where(['course_id'=>$cour_id,'active'=>1])->get();
      $getYr=$getDivisions[0]->year_id;
      $yrSem=0; 
      $getSemester=ClgYearModel::where('year_id',$getYr)->get();
      if(isset($getSemester[0]->cour_year))
      {
        $yrSem=$getSemester[0]->cour_year*2;
      }
      echo  $yrSem ;
    }

    
    public function subedit($subRowId)
    {

      $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_edit==0 )
        {
          return view('backend.pageDined');
        }

   

     $getsubjectCnt=ClgSubjectModel::where('sub_id',$subRowId)->get()->count();

     if($getsubjectCnt==1)
     {

        $getsubject=ClgSubjectModel::where('sub_id',$subRowId)->get();
        $getCourseId=$getsubject[0]->course_id;
        $getSemId=$getsubject[0]->semester_id;
        $getsubjectName=$getsubject[0]->subject_name;
        $getCourse=ClgCourseModel::leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_depart_master.gr_id')
            ->where('at_college_course_master.course_id',$getCourseId)->get();

            $courseName=$getCourse[0]->course_name;

        return view('backend.clgsubject.edit', compact('getSemId','courseName','getsubjectName','subRowId'));

     }
     else
     {
          return redirect('/admin/clgsubject')->send();
     }
   
    

    }

    

    public function actionupdate(Request $request) {
      $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_delete==0 )
        {
          return view('backend.pageDined');
        }
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $c_ids=$inputs['c_ids'];
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Student has been successfully inactivated.";
            $redirect_value = "admin/clgsubject/viewalledit/".$c_ids;
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Student has been successfully activated.";
            $redirect_value = "admin/clgsubject/viewalledit/$c_ids/inactive";
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
                ClgSubjectModel::select('*')->where('sub_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
      public function show($id) {

        $uPermission=getUserPermission('clgsubject','college');
        if($uPermission[0]->file_view==0 )
        {
          return view('backend.pageDined');
        }


        $clgCourseSubAll =ClgSubjectModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_subject_master.course_id')->leftJoin('at_college_graduate_master','at_college_graduate_master.gr_id','=','at_college_course_master.gr_id')->leftJoin('at_college_depart_master','at_college_depart_master.dep_id','=','at_college_course_master.dep_id')->where('at_college_subject_master.course_id',$id)->orderBy('at_college_subject_master.semester_id')->groupBy('at_college_subject_master.semester_id')->get(array(DB::raw('group_concat(at_college_subject_master.subject_name) as subNames'),'at_college_course_master.course_name','at_college_depart_master.depart_name','at_college_graduate_master.grad_name','at_college_subject_master.semester_id'));

         $courseName='';
         $depName='';
         $gruad='';

         if(count($clgCourseSubAll)>0)
         {
            $courseName=$clgCourseSubAll[0]->course_name;
            $depName=$clgCourseSubAll[0]->depart_name;
            $gruadName=$clgCourseSubAll[0]->grad_name;
         }


         return view('backend.clgsubject.show', compact('clgCourseSubAll','courseName','depName','gruadName'));
    }


}
