<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class DirectoryController extends Controller
{

    public function index()
    {
        if(check_permissions('directories')){
            $query = DB::table('directories as d');
            $query->join('forms as f', 'd.form_id', '=', 'f.id');
            $query->join('cities as c', 'd.city_id', '=', 'c.id');
            $query->select('d.*', 'f.name as form_name', 'c.name as city_name');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $query->orderBy('d.name', 'ASC');
            $data['directories'] = $query->get();
            return view('admin/form/manage_directories', $data);
        } else{
            return view('common/admin_404');
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'form' => 'required',
            'name' => 'required',
            'phone_no' => 'required',
            'city' => 'required',
        ],
        [
            'form.required'=> 'The directory type is required.',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $response_status = Directory::create([
            'form_id' => $data['form'],
            'name' => $data['name'],
            'phone_no' => $data['phone_no'],
            'city_id' => $data['city'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
        $status = $response_status->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function show(Directory $directory)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $data['dir'] = Directory::find($data['id']);
        $htmlresult = view('admin/form/edit_directory_ajax', $data)->render();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
        return $finalResult;
    }

    public function update(Request $request, Directory $directory)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'form' => 'required',
            'name' => 'required',
            'phone_no' => 'required',
            'city' => 'required',
        ],
        [
            'form.required'=> 'The directory type is required.',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $status = Directory::where('id', $data['id'])->update([
            'form_id' => $data['form'],
            'name' => $data['name'],
            'phone_no' => $data['phone_no'],
            'city_id' => $data['city'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function destroy(Request $request, Directory $directory)
    {
        $data = $request->all();
        $response_status = Directory::find($data['id'])->delete();
        if($response_status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory successfully deleted.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }
}
