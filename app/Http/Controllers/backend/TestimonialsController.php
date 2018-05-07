<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\Testimonials;
use Redirect;
use Session;
use DB;


class TestimonialsController extends Controller
{
    //
    public function index(){
    	$input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $testimonials = Testimonials::where('status', 'N')->get();
        
        } else {
            $testimonials = Testimonials::where('status', 'Y')->get();

        }
        $active_count = Testimonials::where('status', 'Y')->count();
        $inactive_count = Testimonials::where('status', 'N')->count();
        return view('backend.testimonials.index', compact('testimonials', 'active_count', 'inactive_count'));
    }

    public function create() {       
        return view('backend.testimonials.add');
    }

    public function store(Request $request) {
    	$inputs=$request->all();    
    	$data = Testimonials::create([
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'image' => '',
            'status' => 'Y'
        ]);    	
        $id = $data->id;

        $file  = $request->file('image');
        if (isset($file) && $file != '') {
            $diretory_dir = getcwd() . '/uploads/testimonials_photo/' . $id;
            if (!is_dir($diretory_dir)) {
                mkdir($diretory_dir, 0777);
            }
            $temp_dir = $diretory_dir . "/temp";
            if (!is_dir($temp_dir)) {
                mkdir($temp_dir, 0777);
            }
            $original_image_path = 'uploads/testimonials_photo/' . $id . "/temp";
            $resize_destinationPath = 'uploads/testimonials_photo/' . $id;
            $filename_value = time() . rand(100000, 10000);
            $filename = md5($filename_value) . "." . $file->getClientOriginalExtension();
            $file->move($original_image_path, $filename);

            $oldPath = $original_image_path . "/" . $filename;
            $newName = $resize_destinationPath . "/" . $filename;
            copy($oldPath, $newName);

            $image = Image::make(sprintf($resize_destinationPath . '/%s', $filename))->resize(800 , 900)->save();
            DB::table('testimonials')->where('id', $id)->update(array('image' => $filename));   
        }

        Session::flash('message', 'Testimonials has been added successfully');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/testimonials/add');
    }

    public function show($id) {
        $testimonials = Testimonials::find($id);
        return view('backend.testimonials.show', compact('testimonials'));
    }

    public function edit($id){
    	$testimonials = Testimonials::find($id);
        return view('backend.testimonials.edit', compact('testimonials'));
    }

    public function update(Request $request) {
        $id = $request->get('hidid');
        $inputs = $request->all();
     	$insert_arr['name']=$inputs['name'];
    	$insert_arr['description']=$inputs['description'];
    
    	$insert_arr['status']='Y';
    	$file  = $request->file('image');
    	$file = @$inputs['image'];
        if (isset($file) && $file != '') {
            //Get photo by id
            $att = DB::table('testimonials')->where('id', $id)->first();
            $photo = $att->image;
            
            //Unlink old image
            if (file_exists(getcwd() . '/uploads/testimonials_photo/' . $id . '/' . $photo)) {
                @unlink(getcwd() . '/uploads/testimonials_photo/' . $id . '/' . $photo);
            }
            if (file_exists(getcwd() . '/uploads/testimonials_photo/' . $id . '/temp/' . $photo)) {
                @unlink(getcwd() . '/uploads/testimonials_photo/' . $id . '/temp/' . $photo);
            }

            $diretory_dir = getcwd() . '/uploads/testimonials_photo/' . $id;
            if (!is_dir($diretory_dir)) {
                mkdir($diretory_dir, 0777);
            }
            $temp_dir = $diretory_dir . "/temp";
            if (!is_dir($temp_dir)) {
                mkdir($temp_dir, 0777);
            }
            $original_image_path = getcwd() . '/uploads/testimonials_photo/' . $id . "/temp";
            $resize_destinationPath = getcwd() . '/uploads/testimonials_photo/' . $id;
            $filename_value = time() . rand(100000, 10000);
            $filename = md5($filename_value) . "." . $file->getClientOriginalExtension();
            $file->move($original_image_path, $filename);

            $oldPath = $original_image_path . "/" . $filename;
            $newName = $resize_destinationPath . "/" . $filename;
            copy($oldPath, $newName);

            $image = Image::make(sprintf($resize_destinationPath . '/%s', $filename))->resize(800, 900)->save();
            DB::table('testimonials')->where('id', $id)->update(array('image' => $filename));      
        }

        $update_data = array('name' => $inputs['name'], 'description' => $inputs['description']);
        DB::table('testimonials')->where('id', $id)->update($update_data);      

        Session::flash('message', 'Testimonials has been updated successfully');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/testimonials/edit/'.$id);
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            $column_name = "status";
            $action_value = "N";
            $msg_value = "Testimonials has been successfully inactivated.";
            $redirect_value = "admin/testimonials";
            
        } else if ($action == 'Active') {
            $column_name = "status";
            $action_value = "Y";
            $msg_value = "Testimonials has been successfully activated.";
            $redirect_value = "admin/testimonials/?token=inactive";
            
        } else if ($action == 'Delete') {
            $msg_value = "Testimonials has been successfully deleted.";
            $redirect_value = "admin/testimonials/?token=inactive";
            
        }
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                Testimonials::select('*')->where('id', $update_id)->update($data);
                
            } else {
                $cms_data = Testimonials::find($update_id);
                $cms_data->delete();
            }
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

    public function check_name(Request $request)
    {
        $inputs = $request->all();       
        $name = $inputs['name'];
        $id = isset($inputs['id'])?$inputs['id']:'';
        $check_name = Testimonials::where('name', $name)->where('id', '!=', $id)->first();
        if($check_name != ''){
            $get_name = $check_name->name;
            if($get_name != $name){
                return "true";
            }
            else{
                return "false";
            }
        }
        else{
            return "true";
        }
    }
}
