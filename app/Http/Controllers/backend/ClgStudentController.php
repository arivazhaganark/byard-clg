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
use App\Model\backend\ClgAcademicYearModel;
use App\Model\backend\User;
use Redirect;
use Session;
use DB;
use Excel;


class ClgStudentController extends Controller
{
     
    public function index(){

        $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }

        $input = Input::all(); 
        // $curr_month=date('m');
        // $curr_year=date('y');
        // if($curr_month >= 6 )
        // { 
        //   $year=$curr_year.(date('y')+1);
        // }
        // else
        // {
        //     $year=(date('y')-1).$curr_year;
        // }

        $GetAcademicYear=ClgAcademicYearModel::get();
        $year=$GetAcademicYear[0]->academic_year;
        $courseAll=DB::select("SELECT b.course_id,course_name,a.roll_no,b.course_name,c.grad_name,e.depart_name FROM  at_college_student_master  a,at_college_course_master b,at_college_graduate_master c,at_college_depart_master e WHERE a.course_id=b.course_id AND c.gr_id=b.gr_id AND  e.dep_id=b.dep_id AND a.academic_year=$year GROUP by a.course_id ");
         $active_count = 0;
         $inactive_count = 0;
       return view('backend.clgstudent.index', compact('courseAll','clgdepartAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
        $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $GetAcademicYear=ClgAcademicYearModel::get();
        $year=$GetAcademicYear[0]->academic_year;
        $gradId = GraduateModel::select('gr_id','grad_name','active')->where('active',1)->get();
        $getDivision=DivisionModel::where(['active'=>1])->get();
        $yearCourse=ClgYearModel::where(['active'=>1])->get();
        return view('backend.clgstudent.add',compact('gradId','getDivision','yearCourse','year'));
    }
    

    public function store(Request $request) {
        $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         $inputs=$request->all();
         $graId=$inputs['selectGrd'];
         $depId=$inputs['selectDept'];
         $courseId=$inputs['selectCourse'];
         $rollno=$inputs['rollno'];
         $stuName=$inputs['sname'];
         $ayear=$inputs['ayear'];
         $join_year=date('Y');

         $chkCnt = ClgStudentModel::where(['roll_no'=>$rollno])->get()->count();
         $GetAdminLoginUniqe=User::where(['email'=>$rollno])->get()->count();

        if($chkCnt==0 && $GetAdminLoginUniqe==0)
        {
      
        $sectiondata = ClgStudentModel::insert(['gr_id'=>$graId,
                                               'dep_id'=>$depId,
                                               //'division_id'=>$divId,
                                               'course_id'=>$courseId,
                                               'roll_no'=>$rollno,
                                               'student_name'=>$stuName,
                                               'dob'=>'',
                                               'join_year'=>$join_year,
                                               'academic_year'=>$ayear,
                           ]);
            $adminData=User::create(['name'=>"$stuName",'email'=>"$rollno",'password'=>bcrypt($rollno),'usertype'=>'CS']); //CS::College student

            Session::flash('message', 'Student created successfully .');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/clgstudent/add');

        }
        else
        {

            Session::flash('message', 'Student already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/clgstudent/add')->withInput($request->all());

        }
   
}


   public function viewalledit($course_id,$str=null)
   {   
            $uPermission=getUserPermission('clgstudent','college');
            if($uPermission[0]->file_edit==0) /**Check the file permission**/
            { return view('backend.pageDined');}
            $input = Input::all(); 
            // $curr_month=date('m');
            // $curr_year=date('y');
            // if($curr_month >= 6 )
            // { 
            //     $year=$curr_year.(date('y')+1);
            // }
            // else
            // {
            //     $year=(date('y')-1).$curr_year;
            // }
            $GetAcademicYear=ClgAcademicYearModel::get();
            $year=$GetAcademicYear[0]->academic_year;
  
         if (isset($str) && $str == 'inactive') {
           $courseAll=DB::select("SELECT a.clg_stu_id,a.roll_no,a.student_name as stu_name,b.course_id,course_name,a.roll_no,b.course_name,c.grad_name,e.depart_name FROM  at_college_student_master  a,at_college_course_master b,at_college_graduate_master c,at_college_depart_master e WHERE a.course_id=b.course_id AND c.gr_id=b.gr_id   AND e.dep_id=b.dep_id AND a.academic_year=$year  AND  a.course_id=$course_id AND a.active=0 ");

        
        } else {

           $courseAll=DB::select("SELECT a.clg_stu_id,a.roll_no,a.student_name as stu_name,b.course_id,course_name,a.roll_no,b.course_name,c.grad_name,e.depart_name FROM  at_college_student_master  a,at_college_course_master b,at_college_graduate_master c,at_college_depart_master e WHERE a.course_id=b.course_id AND c.gr_id=b.gr_id   AND e.dep_id=b.dep_id AND a.academic_year=$year  AND  a.course_id=$course_id   AND a.active=1");


        }
        $active_count = ClgStudentModel::where(['active'=>1,"course_id"=>$course_id])->count();
         $inactive_count = ClgStudentModel::where(['active'=>0,"course_id"=>$course_id])->count();       
         
       return view('backend.clgstudent.viewall', compact('courseAll','clgdepartAll', 'active_count', 'inactive_count','course_id','str','uPermission'));
   }

   public function bulkstore(Request $request) {

       $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}

    
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
                                $GetAdminLoginUniqe=User::where(['email'=>$srollno])->get()->count();
                                 
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

                                    $adminData=User::create(['name'=>"$sName",'email'=>"$srollno",'password'=>bcrypt($srollno),'usertype'=>'CS']); //CS::Cpllege Student
                                    $insetCnt++;


                                }
                                else
                                {
                                  $updateCus=ClgStudentModel::where('roll_no', $srollno)
                                    ->update(["student_name"=>$sName]);

                                  $adminData=User::where(['email'=>"$srollno"])->update(['name'=>"$sName"]); 
                                  $updateCnt++;
                                }
                                $toralCnt++;
   
                              }

                        }
                        else
                        {

                            Session::flash('message', 'Invalid header!');
                            Session::flash('alert-class', 'alert-warning');
                            return Redirect::to('admin/clgstudent/add');
                            exit;
                        }

                        
                       
                    }


                    if($toralCnt>0) 
                        {

                            Session::flash('message', "Student added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                            Session::flash('alert-class', 'alert-success');
                            return Redirect::to('admin/clgstudent/add');
                            exit;
                        }
                
                 }
                 else
                 {

                    Session::flash('message', 'File content is empty!');
                    Session::flash('alert-class', 'alert-warning');
                    return Redirect::to('admin/clgstudent/add');
                 }

            } 
            else
            {

                Session::flash('message', 'Please upload xl,xls file ');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/clgstudent/add');

            }

   }
     
     public function update(Request $request) {

        $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}


         $inputs=$request->all();
         $graId=$inputs['selectGrd'];
         $depId=$inputs['selectDept'];
         $courseId=$inputs['selectCourse'];
         $rollno=$inputs['rollno'];
         $stuName=$inputs['sname'];
         $stuRwId=$inputs['hidid'];

         $chkCnt = ClgStudentModel::where(['roll_no'=>$rollno])->whereNotIn('clg_stu_id', [$stuRwId])->get()->count();
         $GetAdminLoginUniqe=User::where(['email'=>$rollno])->get()->count();

         if($chkCnt==0)
         {

              $updateStu=ClgStudentModel::where('clg_stu_id', $stuRwId)
                      ->update(['course_id'=>$courseId,'gr_id'=>$graId,'dep_id'=>$depId,'student_name'=>$stuName,'roll_no'=>$rollno]);
            
            if($GetAdminLoginUniqe==1)
            {
               
              $GetAdminLoginId=User::where(['email'=>$rollno])->get(array('id')) ;
              $AdInd=$GetAdminLoginId[0]->id;
            $adminData=User::where('id',"$AdInd")->update(['name'=>"$stuName",'email'=>"$rollno",'password'=>bcrypt($rollno)]);

            }
            Session::flash('message', 'Student updated successfully');
            Session::flash('alert-class', 'alert-success');

            return redirect("admin/clgstudent/stuedit/$stuRwId") ;

         }
         else
         {

            Session::flash('message', 'Student roll no already exits');
            Session::flash('alert-class', 'alert-warning');

            return redirect("admin/clgstudent/stuedit/$stuRwId") ;  

         }
         
   
    }

    public function depDropBox(Request $request)
    {
 
      $inputs=$request->all();
      if($request['mode']=='single')
      {

        $gr_id=$request['selectGrd'];
         

      }
      else
      {
        $gr_id=$request['selectBulkGrd'];
        

      }
      
      $getDivisions=ClgDepartModel::where(['gr_id'=>$gr_id,'active'=>1])->get();
      echo json_encode($getDivisions);
    }

    public function courDropBox(Request $request)
    {
        $inputs=$request->all();
        if($request['mode']=='single')
        {
            $gr_id=$request['selectGrd'];
       // $division_id=$request['selectDiv'];
        $selectDept=$request['selectDept'];

        }
        else
        {
            $gr_id=$request['selectBulkGrd'];
        //$division_id=$request['selectBulkDiv'];
        $selectDept=$request['selectBulkDept'];

        }
        
        $getCourse=ClgCourseModel::where(['gr_id'=>$gr_id,'dep_id'=>$selectDept])->get();
        echo json_encode($getCourse);


    }

    public function semDropBox(Request $request)
    {
        $inputs=$request->all();
        if($request['mode']=='single')
        {
          $selectCourse=$request['selectBulkCourse'];
        }
        else
        {
          $selectCourse=$request['selectBulkCourse'];
        }
        
        $getCourse=ClgCourseModel::where(['course_id'=>$selectCourse])->get();
        echo json_encode($getCourse[0]->year_id*2);


    }


    public function stuedit($stuRowId)
    {

   

     $getStuCnt=ClgStudentModel::where('clg_stu_id',$stuRowId)->get()->count();

     if($getStuCnt==1)
     {

        $getStu=ClgStudentModel::where('clg_stu_id',$stuRowId)->get() ;
        $course_id=$getStu[0]->course_id;
        $sName=$getStu[0]->student_name;
        $roll_no=$getStu[0]->roll_no;
        $courseAll=ClgCourseModel::where('course_id',$course_id)->get();
        $gr_id=$courseAll[0]->gr_id;
        $division_id=$courseAll[0]->division_id;
        $dep_id=$courseAll[0]->dep_id;
        $getCourse=ClgCourseModel::where(['gr_id'=>$gr_id,'division_id'=>$division_id,'dep_id'=>$dep_id])->get();
        $gradId = GraduateModel::select('gr_id','grad_name','active')->where('active',1)->get();
        $getDivision=DivisionModel::where(['active'=>1])->get();
        $getDepart=ClgDepartModel::where(['gr_id'=>$gr_id,'division_id'=>$division_id,'active'=>1])->get();
        //$yearCourse=ClgYearModel::where(['active'=>1])->get();

        return view('backend.clgstudent.edit', compact('gradId','courseAll','course_id','getDivision','getDepart','stuRowId','getStu','getCourse','sName','roll_no'));

     }
     else
     {
          return redirect('/admin/clgstudent')->send();
     }
   
    

    }

    

    public function actionupdate(Request $request) {
        $uPermission=getUserPermission('clgstudent','college');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $c_ids=$inputs['c_ids'];
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Student has been successfully inactivated.";
            $redirect_value = "admin/clgstudent/viewalledit/".$c_ids;
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Student has been successfully activated.";
            $redirect_value = "admin/clgstudent/viewalledit/$c_ids/inactive";
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
                ClgStudentModel::select('*')->where('clg_stu_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
       
    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     }
}
