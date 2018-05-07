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
use Redirect;
use Session;
use DB;


class SchSubjectController extends Controller
{
     
    public function index(){
        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }

        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
           $schSubjectAll = SclSubjectModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_subject_master.sch_cls_id')->where('at_school_subject_master.active',0)->get();
       
        } else {

            $schSubjectAll = SclSubjectModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_subject_master.sch_cls_id')->where('at_school_subject_master.active',1)->get();

             
        }

        $active_count = SclSubjectModel::where('active', 1)->count();
        $inactive_count = SclSubjectModel::where('active', 0)->count();
       return view('backend.schSubject.index', compact('schSubjectAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
    $uPermission=getUserPermission('schsubject','school');
    if($uPermission[0]->file_add==0) /**Check the file permission**/
    { return view('backend.pageDined');}
       
    $classId = SchClassModel::select('sch_cls_id','sch_class','active')->get();
    return view('backend.schSubject.add',compact('classId'));
    }
    

    public function store(Request $request) {
        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         $inputs=$request->all();
         $clsId=$inputs['selectCls'];
         $subName=$inputs['subname'];

        $chkCnt = SclSubjectModel::where(['sch_cls_id'=>$clsId,'sub_name'=>$subName])->get()->count();

        if($chkCnt==0)
        {
      
        $subData = SclSubjectModel::insert([
                              'sch_cls_id'=>$clsId,   
                            'sub_name' =>$subName,
                           ]);

            Session::flash('message', 'Subject created successfully .');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/schsubject/add');

        }
        else
        {

            Session::flash('message', 'Subject already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schsubject/add')->withInput($request->all());

        }
   
}

     

    public function edit($sub_id){
        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
       $classId = SchClassModel::select('sch_cls_id','sch_class','active')->get();
       $getSub=SclSubjectModel::where('sub_id',$sub_id)->get();
        return view('backend.schSubject.edit', compact('classId','sub_id','getSub'));
    }

     public function update(Request $request) {

        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         $inputs=$request->all();
         $id = $request->get('hidid');
         $clsId=$inputs['selectCls'];
         $subname=$inputs['subname'];
         
         $chkCnt = SclSubjectModel::where(['sch_cls_id'=>$clsId,'sub_name'=>$subname])->whereNotIn('sub_id', [$id])->get()->count();
         
         if($chkCnt==0)
         {
            $updateCus=SclSubjectModel::where('sub_id', $id)
                      ->update(['sch_cls_id'=>$clsId,'sub_name'=>$subname]);
            Session::flash('message', 'Subject updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schsubject/') ;  
         }
         else
         {

            Session::flash('message', 'Subject name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schsubject/edit/'.$id);
         }

       
    }

    public function actionupdate(Request $request) {
        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Subject has been successfully inactivated.";
            $redirect_value = "admin/schsubject";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Subject has been successfully activated.";
            $redirect_value = "admin/schsubject/?token=inactive";
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
                SclSubjectModel::select('*')->where('sub_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
    public function show($id) {
        $uPermission=getUserPermission('schsubject','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $schsectionAll = SchSectionModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_section_master.sch_cls_id')->where('at_school_section_master.sec_id',$id)->get();
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
