<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Transporters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class VehicleController extends Controller
{

    public function index()
    {
        if(check_permissions('vehicles')){
            $query = DB::table('transporters as t');
            $query->join('vehicles as v', 'v.transporter_id', '=', 't.id');
            $query->join('cities as c', 't.city_id', '=', 'c.id');
            $query->select('t.*', 'c.name as city_name', 'v.id as vehicle_id', 'v.vehicle_number', 'vehicle_type', 'v.vehicle_city');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('t.created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query->where('t.type', '2');
            $query->orderBy('t.id', 'DESC');
            $data['vehicles'] = $query->get();
            return view('admin/vehicle/manage_vehicles', $data);
        } else{
            return view('common/admin_404');
        }
    }


    public function create()
    {
        if(check_permissions('vehicles')){
            return view('admin/vehicle/add_vehicle');
        } else{
            return view('common/admin_404');
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_name' => 'required',
            'city' => 'required',
            'phone_no' => 'required',
            'vehicle_type' =>'required',
            'vehicle_city' =>'required',
            'vehicle_number' => 'required|unique:vehicles',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $data = $request->all();
        $query = Transporters::create([
            'transporter_name' => $data['owner_name'],
            'city_id' => $data['city'],
            'phone_no' => $data['phone_no'],
            'total_vehicle' => '1',
            'type' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        $status = $query->id;
        if($status > 0) {
            $vehicle_id = DB::table('vehicles')->insertGetId([
                'transporter_id' => $query->id,
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_city' => $data['vehicle_city'],
                'vehicle_number' => $data['vehicle_number'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::guard('admin')->user()->id,
            ]);

            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }


    public function show(Transporters $transporters)
    {
        //
    }


    public function edit($id='')
    {
        if(check_permissions('vehicles')){
            $query = Transporters::where('type', '2');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query->where('id', $id);
            $data['owner'] = $query->first();

            if(!empty($data['owner'])){
                $query2 = DB::table('vehicles');
                if(Auth::guard('admin')->user()->type == '1') {
                    // if(Auth::guard('admin')->user()->view_all_data == '1') {
                    $query2->where('created_by', Auth::guard('admin')->user()->id);
                    // }
                }
                $query2->where('transporter_id', $id);
                $data['vehicle'] = $query2->first();
                return view('admin/vehicle/edit_vehicle', $data);
            }else{
                return view('common/admin_404');
            }
        }else{
            return view('common/admin_404');
        }
    }

    public function update(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'owner_name' => 'required',
            'phone_no' => 'required',
            'city' => 'required',
            'vehicle_type' =>'required',
            'vehicle_city' =>'required',
            'vehicle_number' => 'required|unique:vehicles,vehicle_number,'.$data['id'],
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $status = Transporters::where('id', $data['id'])->update([
            'transporter_name' => $data['owner_name'],
            'city_id' => $data['city'],
            'phone_no' => $data['phone_no'],
            'total_vehicle' => '1',
            'type' => '2',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $vehicle_id = DB::table('vehicles')->where('id', $data['vehicle_id'])->update([
                'transporter_id' => $data['id'],
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_city' => $data['vehicle_city'],
                'vehicle_number' => $data['vehicle_number'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }

    public function destroy(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $check = count_existing_record('driver_vehicles', 'vehicle_id', $data['vehicle_id']);
        $check2 = count_existing_record('bilties', 'vehicle_id', $data['vehicle_id']);
        if(($check > 0) || ($check2 > 0)){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this vehicle. This vehicle is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = permanently_deleted('transporters', 'id', $data['id']);
            $response_status = permanently_deleted('vehicles', 'transporter_id', $data['id']);
            if($response_status > 0) {
                $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }
}
