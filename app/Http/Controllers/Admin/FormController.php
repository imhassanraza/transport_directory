<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator;

class FormController extends Controller
{

    public function index()
    {
        if(check_permissions('directory_types')){
            $query = Form::orderBy('name', 'ASC');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $data['forms'] = $query->get();
            return view('admin/form/manage_forms', $data);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:forms',
        ],
        [
            'name.required'=> 'The directory type title is required.',
            'unique.required'=> 'The directory type already added.',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();
        $response_status = Form::create([
            'name' => $data['name'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
        $status = $response_status->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory Type successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function show(Form $form)
    {
        //
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $form = Form::find($data['id']);
        $htmlresult = view('admin/form/edit_form_ajax', compact('form'))->render();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
        return $finalResult;
    }

    public function update(Request $request, Form $form)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|unique:forms,name,'.$data['id'],
        ],
        [
            'name.required'=> 'The directory type title is required.',
            'unique.required'=> 'The directory type already added.',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $status = Form::where('id', $data['id'])->update([
            'name' => $data['name'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory Type successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function destroy(Request $request, Form $form)
    {
        $data = $request->all();
        $check = count_existing_record('directories', 'form_id', $data['id']);
        if($check > 0){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this directory type. This directory type is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = Form::find($data['id'])->delete();
            if($response_status > 0) {
                $finalResult = response()->json(['msg' => 'success', 'response'=>'Directory Type successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }
}
