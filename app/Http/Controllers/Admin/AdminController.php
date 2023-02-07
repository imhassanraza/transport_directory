<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash, Session, Validator, DB;
use App\Models\Admin\Admin;
class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard');
    }
    public function change_password()
    {
        return view('admin/change_password');
    }
    public function update_password(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
            'c_password' => 'required|same:new_password',
        ],
        [
            'c_password.required'=> 'The confirm password field is required.',
            'c_password.same'=> 'The confirm password must be same with new password.',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        if (Hash::check($data['old_password'], Auth::guard('admin')->user()->password)) {
            try {
                $data_array = Admin::findOrFail(Auth::guard('admin')->user()->id);
                $data_array->password = Hash::make($data['new_password']);
                $data_array->save();
                $finalResult = response()->json(array('msg' => 'success', 'response'=>'Password successfully updated.'));
                return $finalResult;
            } catch(\Illuminate\Database\QueryException $ex){
                $finalResult = response()->json(array('msg' => 'error', 'response'=> $ex->getMessage()));
                return $finalResult;
            }
        } else {
            $finalResult = response()->json(array('msg' => 'error', 'response'=>'Your password is wrong.'));
            return $finalResult;
        }
    }
    public function users()
    {
        if(check_permissions('only_admin')){
            $data['users'] = Admin::where('type', '1')->get();
            return view('admin/users/manage_users', $data);
        }else{
            return view('common/admin_404');
        }
    }
    public function create()
    {
        if(check_permissions('only_admin')){
            return view('admin/users/add_user');
        }else{
            return view('common/admin_404');
        }
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'phone_no' => 'required|unique:admin_users',
            'password' => 'required|min:6',
        ],
        [
            'cpassword.required'=> 'The confirm password field is required.',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        if (empty($data['actions'])) {
            return response()->json(['msg' => 'error', 'response'=>'Select atleast one role.!']);
        }
        $roles = implode(',', $data['actions']);
        $status = Admin::create([
            'username' => $data['name'],
            'phone_no' => $data['phone_no'],
            'password' => Hash::make($data['password']),
            'permissions' => $roles,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
        if($status->id > 0) {
            return response()->json(['msg' => 'success', 'response'=>'User successfully added.']);
        }else{
            return response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
        }
    }
    public function edit( $id='', Request $request)
    {
        if(check_permissions('only_admin')){
            $data['user'] = Admin::where('type', '1')->where('id', $id)->first();
            if(!empty($data['user'])){
                return view('admin/users/edit_user', $data);
            }else{
                return view('common/admin_404');
            }
        }else{
            return view('common/admin_404');
        }
    }
    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'password' => 'nullable',
            'cpassword' => 'required_with:password|same:password',
        ],
        [
            'cpassword.same'=> 'The confirm password must be same with password.'
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        if(!empty($data['password'])){
            DB::table('admin_users')
            ->where('id', $data['id'])
            ->update([
                'password' => Hash::make($data['password'])
            ]);
        }
        if (isset($data['status'])) {
            $status = '1';
        } else {
            $status = '0';
        }

        if (empty($data['actions'])) {
            return response()->json(['msg' => 'error', 'response'=>'Select atleast one role.!']);
        }
        $roles = implode(',', $data['actions']);
        $status = Admin::where('id', $data['id'])->update([
            'username' => $data['name'],
            'status' => $status,
            'permissions' => $roles,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);
        if($status > 0){
            return response()->json(['msg' => 'success', 'response'=>'User successfully updated.']);
        } else{
            return response()->json(['msg' => 'error', 'response'=>'Something wrong.']);
        }
    }

    public function destroy(Request $request)
    {
        if(check_permissions('only_admin')){
            $data = $request->all();
            $check = count_existing_record('drivers', 'created_by', $data['id']);
            $check2 = count_existing_record('bilties', 'created_by', $data['id']);
            $check3 = count_existing_record('cold_storages', 'created_by', $data['id']);
            $check4 = count_existing_record('directories', 'created_by', $data['id']);
            $check5 = count_existing_record('driver_routes', 'created_by', $data['id']);
            $check6 = count_existing_record('vehicles', 'created_by', $data['id']);
            $check7 = count_existing_record('transporters', 'created_by', $data['id']);
            if(($check > 0) || ($check2 > 0) || ($check3 > 0) || ($check4 > 0) || ($check5 > 0) || ($check6 > 0) || ($check7 > 0)){
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this user. This user is associated with other records.']);
                return $finalResult;
            }else{
                $response_status = Admin::find($data['id'])->delete();
                if($response_status > 0) {
                    $finalResult = response()->json(['msg' => 'success', 'response'=>'User successfully deleted.']);
                    return $finalResult;
                } else {
                    $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                    return $finalResult;
                }
            }
        } else{
            return view('common/admin_404');
        }
    }
}