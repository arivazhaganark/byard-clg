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
use Redirect;
use Session;
use DB;
use Excel;
use Auth;


class ClgDepartController extends Controller
{
  
    public function index()
    {
    
       $uPermission=getUserPermission('clgdepart','college');

       
       
      if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0 )
      {
        return view('backend.pageDined');
      }
 
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $clgdepartAll = ClgDepartModel::leftJoin('at_college_graduate_master', 'at_college_graduate_master.gr_id', '=', 'at_college_depart_master.gr_id')->where('at_college_depart_master.active', 0)->get();
        }
        else {
            $clgdepartAll = ClgDepartModel::leftJoin('at_college_graduate_master', 'at_college_graduate_master.gr_id', '=', 'at_college_depart_master.gr_id')->where('at_college_depart_master.active', 1)->get();
        }

        $active_count = ClgDepartModel::where('active', 1)->count();
        $inactive_count = ClgDepartModel::where('active', 0)->count();
        return view('backend.clgdepartment.index', compact('clgdepartAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create()
    {

      $uPermission=getUserPermission('clgdepart','college');
      
      if($uPermission[0]->file_add==0)
      {
        return view('backend.pageDined');

      }

        $gradId = GraduateModel::select('gr_id', 'grad_name', 'active')->where('active', 1)->get();
        $getDivision = DivisionModel::where(['active' => 1])->get();
        return view('backend.clgdepartment.add', compact('gradId', 'getDivision'));
    }

    public function store(Request $request)
    {
        $uPermission=getUserPermission('clgdepart','college');
      
          if($uPermission[0]->file_add==0)
          {
            return view('backend.pageDined');

          }
        $inputs = $request->all();
        $clsId = $inputs['selectGrd'];
        $depName = $inputs['dname'];
        $chkCnt = ClgDepartModel::where(['gr_id' => $clsId, 'depart_name' => $depName])->get()->count();
        if ($chkCnt == 0) {
            $sectiondata = ClgDepartModel::create(['gr_id' => $clsId, 'depart_name' => $depName,
            ]);
            Session::flash('message', 'Department created successfully .');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/clgdepart/add');
        }
        else {
            Session::flash('message', 'Department already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/clgdepart/add')->withInput($request->all());
        }
    }

    public function edit($dep_id)
    {
        $uPermission=getUserPermission('clgdepart','college');
      
          if($uPermission[0]->file_edit==0)
          {
            return view('backend.pageDined');

          }
        $departAll = ClgDepartModel::select('gr_id', 'depart_name', 'active', 'division_id')->where('dep_id', $dep_id)->get();
        $gradId = GraduateModel::select('gr_id', 'grad_name', 'active')->where('active', 1)->get();

        return view('backend.clgdepartment.edit', compact('gradId', 'departAll', 'dep_id'));
    }

    public function update(Request $request)
    {
        $uPermission=getUserPermission('clgdepart','college');
      
          if($uPermission[0]->file_edit==0)
          {
            return view('backend.pageDined');

          }

        $id = $request->get('hidid');
        $GradId = $request->get('selectGrd');
        $dname = $request->get('dname');
        $GetDepUnique = ClgDepartModel::where(['depart_name' => "$dname", "gr_id" => "$GradId"])->whereNotIn('dep_id', [$id])->get()->count();
        if ($GetDepUnique == 0) {
            $updateCus = ClgDepartModel::where('dep_id', $id)->update(["depart_name" => "$dname", "gr_id" => "$GradId"]);
            Session::flash('message', 'Department updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/clgdepart/');
        }
        else {
            Session::flash('message', 'Department name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/clgdepart/edit/' . $id);
        }
    }

    public function actionupdate(Request $request)
    {
        $uPermission=getUserPermission('clgdepart','college');
      
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
            $msg_value = "Department has been successfully inactivated.";
            $redirect_value = "admin/clgdepart";
        }
        else
        if ($action == 'Active') {
            $action_value = "1";
            $msg_value = "Department has been successfully activated.";
            $redirect_value = "admin/clgdepart/?token=inactive";
        }
        else {
            $msg_value = "Invalid request";
        }

        foreach($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                ClgDepartModel::select('*')->where('dep_id', $update_id)->update($data);
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
