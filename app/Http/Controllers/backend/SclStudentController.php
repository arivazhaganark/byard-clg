<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SclStudentModel;
//use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SchClsSectionMapModel;
use App\Model\backend\User;
use Redirect;
use Session;
use DB;
use Excel;



class SclStudentController extends Controller
{
     
    public function index(){

           
            $uPermission=getUserPermission('schstudent','school');

            if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
            {
            return view('backend.pageDined');
            }

            $input = Input::all(); 
            $curr_month=date('m');
            $curr_year=date('y');
            if($curr_month >= 6 )
            { 

                $year=$curr_year.(date('y')+1);
            }
            else
            {
                $year=(date('y')-1).$curr_year;

            }



        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
          $StudentAll=SclStudentModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_student_master.sch_cls_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_student_master.sec_id')->where('at_school_student_master.active',0)->where('at_school_student_master.academic_year',$year)->orderBy('at_school_student_master.sch_cls_id')->get(); 
                     
      
        } else {

            $StudentAll=SclStudentModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_student_master.sch_cls_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_student_master.sec_id')->where('at_school_student_master.academic_year',$year)->where('at_school_student_master.active',1)->orderBy('at_school_student_master.sch_cls_id')->get(); 
                     
        }


            $active_count = SclStudentModel::where('active', 1)->count();
        $inactive_count = SclStudentModel::where('active', 0)->count();
            return view('backend.sclStudent.index', compact('StudentAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
    $uPermission=getUserPermission('schstudent','school');
    if($uPermission[0]->file_add==0) /**Check the file permission**/
    { return view('backend.pageDined');}
      $classId = SchClassModel::select('sch_cls_id','sch_class','active')->where('active',1)->get();    
      return view('backend.sclStudent.add',compact('classId'));
    }

    public function depDropBox(Request $request)
    {
 
        $inputs=$request->all();

        if($request['mode']=='single')
        {
        $selectCls=$request['selectCls']; 
        }
        else
        {
        $selectCls=$request['selectbulkCls'];
        }


        $getSection=SchClsSectionMapModel::leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_section_mapping.sec_id')->where(['at_school_class_section_mapping.sch_cls_id'=>$selectCls,'at_school_section_master.active'=>1])->orderBy('at_school_section_master.sec_id')->get();

        echo json_encode($getSection);

        
    }
    public function checkroll()
    {
        return false;
    }
    

      public function store(Request $request) {
        $uPermission=getUserPermission('schstudent','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
   
     	$inputs=$request->all();
        $selectCls=$inputs['selectCls'];
        $selectClsSec=$inputs['selectClsSec'];
        $sName=$inputs['sname']; 
        $srollno=$inputs['rollno']; 
        $ayear=$inputs['ayear'];
        $GetCodeUnique = SclStudentModel:: where(['sch_cls_id'=>$selectCls,'sec_id'=>$selectClsSec,'academic_year' => "$ayear",'roll_no' => "$srollno"])->get()->count();
        $GetAdminLoginUniqe=User::where(['email'=>$srollno])->get()->count();
 
        if($GetCodeUnique>0 && $GetAdminLoginUniqe==0)
         {
            Session::flash('message', 'Roll no already exits in this academic year');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schstudent/add')->withInput($request->all());
         }
         else
          {
            $data = SclStudentModel::create([
              'sch_cls_id'=>$selectCls, 
              'sec_id'=>$selectClsSec, 
              'roll_no'=>$srollno, 
              'academic_year'=>$ayear, 
              'student_name'=>$sName
        ]); 
 
        $adminData=User::create(['name'=>"$sName",'email'=>"$srollno",'password'=>bcrypt($srollno),'usertype'=>'SS']); //SS::SchoolStudent

       Session::flash('message', 'Student has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/schstudent/add');
    }
}

public function bulkstore(Request $request) {

            $uPermission=getUserPermission('schstudent','school');
            if($uPermission[0]->file_add==0) /**Check the file permission**/
            { return view('backend.pageDined');}

            $inputs=$request->all();
            $selectCls=$inputs['selectbulkCls'];
            $selectClsSec=$inputs['selectbulkClsSec'];
            $ayear=$inputs['ayearbulk'];

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
            $GetCodeUnique = SclStudentModel::where(['sch_cls_id'=>$selectCls,'sec_id'=>$selectClsSec,'academic_year' => "$ayear",'roll_no' => "$srollno"])->get()->count();
            $GetAdminLoginUniqe=User::where(['email'=>$srollno])->get()->count();

            if($GetCodeUnique==0 && $GetAdminLoginUniqe==0 )
            {

            $data = SclStudentModel::create([
            'sch_cls_id'=>$selectCls, 
            'sec_id'=>$selectClsSec, 
            'roll_no'=>$srollno, 
            'academic_year'=>$ayear, 
            'student_name'=>$sName
            ]); 

            $adminData=User::create(['name'=>"$sName",'email'=>"$srollno",'password'=>bcrypt($srollno),'usertype'=>'SS']); //SS::SchoolStudent

            $insetCnt++;


            }
            else
            {

            $updateCus=SclStudentModel::where('roll_no', $srollno)
            ->update(["student_name"=> "$sName"]);

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
            return Redirect::to('admin/schstudent/add');
            exit;
            }

            }


            if($toralCnt>0) 
            {

            Session::flash('message', "Student added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/schstudent/add');
            exit;
            }
 
            }
            else
            {
            Session::flash('message', 'File content is empty!');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstudent/add');
            }

            } 
            else
            {
            Session::flash('message', 'Please upload xl,xls file ');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstudent/add');
            }   
}

     

    public function edit($id){
        $uPermission=getUserPermission('schstudent','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}  
    	$sclStaff = SchStaffModel::where(['scl_stf_id' => "$id"])->get();
        return view('backend.sclStudent.edit', compact('sclStaff'));
    }

    public function update(Request $request) {

        $uPermission=getUserPermission('schstudent','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $id = $request->get('hidid');
        $sCode=$request->get('scode');
        $sName=$request->get('sname');
        
        $GetStaffUnique = SchStaffModel:: where(['staff_code' => "$sCode"])->whereNotIn('scl_stf_id', [$id])->get()->count();
        if($GetStaffUnique==0)
        {
            $updateCus=SchStaffModel::where('scl_stf_id', $id)
                      ->update(["staff_name"=> "$sName","staff_code"=>"$sCode"]);
            Session::flash('message', 'Class Name updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schstudent/') ;          
        }
        else
        {

          Session::flash('message', 'Staff code is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstudent/edit/'.$id);
        }

        
    }

    public function actionupdate(Request $request) {
        $uPermission=getUserPermission('schstudent','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Student has been successfully inactivated.";
            $redirect_value = "admin/schstudent";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Student has been successfully activated.";
            $redirect_value = "admin/schstudent/?token=inactive";
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
                SclStudentModel::select('*')->where('sch_stu_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}
