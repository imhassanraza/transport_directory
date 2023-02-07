<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ColdStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class ColdStorageController extends Controller
{
    public function index()
    {
        if(check_permissions('cold_storages')){
            $query = ColdStorage::orderBy('id', 'DESC');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $data['stores'] = $query->get();
            return view('admin/cold_stores/manage_stores', $data);
        } else{
            return view('common/admin_404');
        }
    }

    public function create()
    {
        if(check_permissions('cold_storages')){
            return view('admin/cold_stores/add_store');
        } else{
            return view('common/admin_404');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required',
            'city' => 'required',
            'owner_name' => 'required',
            'owner_phone' => 'required',
            'manager_name' => 'required',
            'manager_phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $data = $request->all();
        $query = ColdStorage::create([
            'store_name' => $data['store_name'],
            'city_id' => $data['city'],
            'owner_name' => $data['owner_name'],
            'owner_phone' => $data['owner_phone'],
            'manager_name' => $data['manager_name'],
            'manager_phone' => $data['manager_phone'],
            'address' => $data['address'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        $status = $query->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Cold storage successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function show(ColdStorage $coldStorage)
    {

    }

    public function edit($id='')
    {
        if(check_permissions('cold_storages')){
            $query = ColdStorage::where('id', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
            }
            $data['store'] = $query->first();
            if(!empty($data['store'])){
                return view('admin/cold_stores/edit_store', $data);
            }else{
                return view('common/admin_404');
            }
        } else{
            return view('common/admin_404');
        }
    }

    public function update(Request $request, ColdStorage $coldStorage)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'store_name' => 'required',
            'city' => 'required',
            'owner_name' => 'required',
            'owner_phone' => 'required',
            'manager_name' => 'required',
            'manager_phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $status = ColdStorage::where('id', $data['store_id'])->update([
            'store_name' => $data['store_name'],
            'city_id' => $data['city'],
            'owner_name' => $data['owner_name'],
            'owner_phone' => $data['owner_phone'],
            'manager_name' => $data['manager_name'],
            'manager_phone' => $data['manager_phone'],
            'address' => $data['address'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Code storage successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }


    public function destroy(Request $request, ColdStorage $coldStorage)
    {
        $data = $request->all();
        $response_status = permanently_deleted('cold_storages', 'id', $data['id']);
        if($response_status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Code storage successfully deleted.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }
}
