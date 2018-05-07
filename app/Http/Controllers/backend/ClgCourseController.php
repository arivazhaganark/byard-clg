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
use Redirect;
use Session;
use DB;
class ClgCourseController extends Controller
{
    public function index()
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $clgdepartAll = ClgCourseModel::leftJoin('at_college_depart_master', 'at_college_depart_master.dep_id', '=', 'at_college_course_master.dep_id')->leftJoin('at_division_master', 'at_division_master.division_id', '=', 'at_college_depart_master.division_id')->leftJoin('at_college_year_master', 'at_college_year_master.year_id', '=', 'at_college_course_master.year_id')->leftJoin('at_college_graduate_master', 'at_college_graduate_master.gr_id', '=', 'at_college_course_master.gr_id')->where('at_college_course_master.active', 0)->get();
        }
        else {
            $clgdepartAll = ClgCourseModel::leftJoin('at_college_depart_master', 'at_college_depart_master.dep_id', '=', 'at_college_course_master.dep_id')->leftJoin('at_division_master', 'at_division_master.division_id', '=', 'at_college_depart_master.division_id')->leftJoin('at_college_year_master', 'at_college_year_master.year_id', '=', 'at_college_course_master.year_id')->leftJoin('at_college_graduate_master', 'at_college_graduate_master.gr_id', '=', 'at_college_course_master.gr_id')->where('at_college_course_master.active', 1)->get();
        }

        $active_count = ClgCourseModel::where('active', 1)->count();
        $inactive_count = ClgCourseModel::where('active', 0)->count();
        return view('backend.clgcourse.index', compact('clgdepartAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create()
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $gradId = GraduateModel::select('gr_id', 'grad_name', 'active')->where('active', 1)->get();
        $getDivision = DivisionModel::where(['active' => 1])->get();
        $yearCourse = ClgYearModel::where(['active' => 1])->get();
        return view('backend.clgcourse.add', compact('gradId', 'getDivision', 'yearCourse'));
    }

    public function store(Request $request)
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_add==0)
        {return view('backend.pageDined'); }
        $inputs = $request->all();
        $graId = $inputs['selectGrd'];
        $depId = $inputs['selectDept'];
        $yearId = $inputs['selectYear'];
        $courseName = $inputs['cname'];
        $chkCnt = ClgCourseModel::where(['gr_id' => $graId, 'dep_id' => $depId, 'course_name' => $courseName])->get()->count();
        if ($chkCnt == 0) {
            $sectiondata = ClgCourseModel::insert(['gr_id' => $graId, 'dep_id' => $depId,

            // 'division_id'=>$divId,

            'year_id' => $yearId, 'course_name' => $courseName]);
            Session::flash('message', 'Course created successfully .');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/clgcourse/add');
        }
        else {
            Session::flash('message', 'Course already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/clgcourse/add')->withInput($request->all());
        }
    }

    public function edit($course_id)
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_edit==0)
        {return view('backend.pageDined');} 
        $courseAll = ClgCourseModel::where('course_id', $course_id)->get();
        $gr_id = $courseAll[0]->gr_id;
        $gradId = GraduateModel::select('gr_id', 'grad_name', 'active')->where('active', 1)->get();
        $getDivision = DivisionModel::where(['active' => 1])->get();
        $getDepart = ClgDepartModel::where(['gr_id' => $gr_id, 'active' => 1])->get();
        $yearCourse = ClgYearModel::where(['active' => 1])->get();
        return view('backend.clgcourse.edit', compact('gradId', 'courseAll', 'course_id', 'getDivision', 'yearCourse', 'getDepart'));
    }

    public function update(Request $request)
    {
       $uPermission=getUserPermission('clgcourse','college');
       if($uPermission[0]->file_edit==0)
       {
           return view('backend.pageDined');
       }
        $inputs = $request->all();
        $id = $request->get('hidid');
        $graId = $inputs['selectGrd'];
        $depId = $inputs['selectDept'];
        $yearId = $inputs['selectYear'];
        $courseName = $inputs['cname'];
        $chkCnt = ClgCourseModel::where(['gr_id' => $graId, 'dep_id' => $depId, 'course_name' => $courseName])->whereNotIn('course_id', [$id])->get()->count();
        if ($chkCnt == 0) {
            $updateCus = ClgCourseModel::where('course_id', $id)->update(['gr_id' => $graId, 'dep_id' => $depId, 'year_id' => $yearId, 'course_name' => $courseName]);
            Session::flash('message', 'Course updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/clgcourse/');
        }
        else {
            Session::flash('message', 'Course name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/clgcourse/edit/' . $id);
        }
    }

    public function depDropBox(Request $request)
    {
        $inputs = $request->all();
        $gr_id = $request['selectGrd'];
        $division_id = $request['selectDiv'];
        $getDivisions = ClgDepartModel::where(['gr_id' => $gr_id, 'active' => 1])->get();
        echo json_encode($getDivisions);
    }

    public function actionupdate(Request $request)
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_delete==0)
        {
            return view('backend.pageDined');
        }
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            $action_value = "0";
            $msg_value = "Course has been successfully inactivated.";
            $redirect_value = "admin/clgcourse";
        }
        else
        if ($action == 'Active') {
            $action_value = "1";
            $msg_value = "Course has been successfully activated.";
            $redirect_value = "admin/clgcourse/?token=inactive";
        }
        else {
            $msg_value = "Invalid request";
        }

        foreach($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                ClgCourseModel::select('*')->where('course_id', $update_id)->update($data);
            }
        }

        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

    //  public function show($id) {
    //      $schsectionAll = SchSectionModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_section_master.sch_cls_id')->where('at_school_section_master.sec_id',$id)->get();
    // }

    public function generateRandomString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, $charactersLength - 1) ];
        }

        return $randomString;
    }

    public function bulkstore(Request $request)
    {
        $uPermission=getUserPermission('clgcourse','college');
        if($uPermission[0]->file_add==0)
        {return view('backend.pageDined'); }

        if (Input::hasFile('import_file')) {
            $insetCnt = 0;
            $updateCnt = 0;
            $toralCnt = 0;
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path,
            function ($reader)
            {
            })->get();
            if (!empty($data) && $data->count()) {
                foreach($data as $key => $value) {
                    if (isset($value->graduatetype) && isset($value->departmentname)) {
                        if ($value->graduatetype != "" && $value->departmentname != "") {
                            $graduate_type = $value->graduatetype;
                            $dept_name = $value->departmentname;

                            if($graduate_type=='UG')
                                $graduate_type = 1;
                            else if($graduate_type=='PG')
                                $graduate_type = 2;
                            else
                                $graduate_type = '';

                            $count = ClgDepartModel::where('depart_name', $dept_name)->count();
                            if ($count == 0) { 
                                $saveData = ClgDepartModel::insert(['gr_id' => $graduate_type, 'depart_name' => $dept_name]);
                                $insetCnt++;
                            }
                            else { echo 2;exit;
                                $updateCus = ClgDepartModel::where('depart_name', $dept_name)->update(["gr_id" => $graduate_type, "depart_name" => $dept_name]);
                                //CSF::College staff
                                $updateCnt++;
                            }

                            $toralCnt++;
                        }
                    }
                    else {
                        Session::flash('message', 'Invalid header!');
                        Session::flash('alert-class', 'alert-warning');
                        return Redirect::to('admin/clgdepart/add');
                        exit;
                    }
                }

                if ($toralCnt > 0) {
                    Session::flash('message', "Department added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                    Session::flash('alert-class', 'alert-success');
                    return Redirect::to('admin/clgdepart/add');
                    exit;
                }

                //  $insert[] = ['title' => $value->title, 'description' => $value->description];

            }
            else {
                Session::flash('message', 'File content is empty!');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/clgdepart/add');
            }

            // echo "<pre>";print_r($data);

        }
        else {
            Session::flash('message', 'Please upload xls file ');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/clgdepart/add');
        }
    }

}
