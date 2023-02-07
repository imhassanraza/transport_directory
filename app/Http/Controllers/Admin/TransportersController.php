<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Transporters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class TransportersController extends Controller
{
    public function index()
    {
        if(check_permissions('transporters')){
            $query = DB::table('transporters as t');
            $query->join('cities as c', 't.city_id', '=', 'c.id');
            $query->select('t.*', 'c.name as city_name');
            $query->where('t.type', '1');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('t.created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query->orderBy('t.id', 'DESC');
            $data['transporters'] = $query->get();
            return view('admin/transporter/manage_transporters', $data);
        } else{
            return view('common/admin_404');
        }
    }

    public function create()
    {
        if(check_permissions('transporters')){
            return view('admin/transporter/add_transporter');
        } else{
            return view('common/admin_404');
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'transporter_name' => 'required',
            'city' => 'required',
            'phone_no' => 'required',
            'total_vehicle' => 'required',
            // 'vehicles.*.vehicle_type' => 'required',
            // 'vehicles.*.vehicle_city' => 'required',
            'vehicles.*.vehicle_number' => 'distinct',
        ],
        [
            // 'vehicles.*.vehicle_type.required' => 'The vehicle type field is required.',
            // 'vehicles.*.vehicle_city.required' => 'The vehicle city field is required.',
            // 'vehicles.*.vehicle_number.required' => 'The vehicle number field is required.',
            'vehicles.*.vehicle_number.distinct' => 'The vehicle number must be different.',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        foreach ($data['vehicles'] as $key => $vehicle) {
            if(empty($vehicle['vehicle_type']) || empty($vehicle['vehicle_city']) || empty($vehicle['vehicle_number'])) {
                $finalResult = response()->json(array('msg' => 'error', 'response'=>'The vehicle detail fields are required.'));
                return $finalResult;
            }

            $vehicle = DB::table('vehicles')->where('vehicle_number', $vehicle['vehicle_number'])->first();
            if(!empty($vehicle)){
                $finalResult = response()->json(array('msg' => 'error', 'response'=>'This '.$vehicle->vehicle_number.' vehicle is already added.'));
                return $finalResult;
            }
        }

        $query = Transporters::create([
            'transporter_name' => $data['transporter_name'],
            'city_id' => $data['city'],
            'phone_no' => $data['phone_no'],
            'total_vehicle' => $data['total_vehicle'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        $status = $query->id;
        if($status > 0) {
            foreach ($data['vehicles'] as $key => $vehicle) {
                $vehicle_id = DB::table('vehicles')->insert([
                    'transporter_id' => $query->id,
                    'vehicle_type' => $vehicle['vehicle_type'],
                    'vehicle_city' => $vehicle['vehicle_city'],
                    'vehicle_number' => $vehicle['vehicle_number'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);
            }

            $finalResult = response()->json(['msg' => 'success', 'response'=>'Transporter successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function show($id='')
    {
        if(check_permissions('transporters') || check_permissions('vehicles')){

            $query = Transporters::where('id', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $data['transporter'] = $query->first();

            if(!empty($data['transporter'])){
                $query2 = DB::table('vehicles');
                $query2->where('transporter_id', $id);
                if(Auth::guard('admin')->user()->type == '1') {
                    // if(Auth::guard('admin')->user()->view_all_data == '1') {
                    $query2->where('created_by', Auth::guard('admin')->user()->id);
                    // }
                }
                $data['vehicles'] = $query2->get();
                return view('admin/transporter/transporter_details', $data);
            }else{
                return view('common/admin_404');
            }
        }else{
            return view('common/admin_404');
        }
    }

    public function edit($id='')
    {
        if(check_permissions('transporters')){

            $query = Transporters::where('id', $id)->where('type', '1');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $data['transporter'] = $query->first();

            if(!empty($data['transporter'])){
                $query2 = DB::table('vehicles');
                $query2->where('transporter_id', $id);
                if(Auth::guard('admin')->user()->type == '1') {
                    // if(Auth::guard('admin')->user()->view_all_data == '1') {
                    $query2->where('created_by', Auth::guard('admin')->user()->id);
                    // }
                }
                $data['vehicles'] = $query2->get();
                return view('admin/transporter/edit_transporter', $data);
            }else{
                return view('common/admin_404');
            }
        }else{
            return view('common/admin_404');
        }
    }

    public function updateAsBk(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'transporter_name' => 'required',
            'city' => 'required',
            'phone_no' => 'required',
            'total_vehicle' => 'required',
            // 'vehicles.*.vehicle_type' => 'required',
            // 'vehicles.*.vehicle_city' => 'required',
            'vehicles.*.vehicle_number' => 'distinct',
        ],
        [
            // 'vehicles.*.vehicle_type.required' => 'The vehicle type field is required.',
            // 'vehicles.*.vehicle_city.required' => 'The vehicle city field is required.',
            // 'vehicles.*.vehicle_number.required' => 'The vehicle number field is required.',
            'vehicles.*.vehicle_number.distinct' => 'The vehicle number must be different.',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        foreach ($data['vehicles'] as $key => $vehicle) {
            if(empty($vehicle['vehicle_type']) || empty($vehicle['vehicle_city']) || empty($vehicle['vehicle_number'])) {
                $finalResult = response()->json(array('msg' => 'error', 'response'=>'The vehicle detail fields are required.'));
                return $finalResult;
            }

            $vehicle = DB::table('vehicles')->where('vehicle_number', $vehicle['vehicle_number'])->where('transporter_id', '!=', $data['id'])->first();
            if(!empty($vehicle)){
                $finalResult = response()->json(array('msg' => 'error', 'response'=>'This '.$vehicle->vehicle_number.' vehicle is already added.'));
                return $finalResult;
            }
        }

        $status = Transporters::where('id', $data['id'])->update([
            'transporter_name' => $data['transporter_name'],
            'city_id' => $data['city'],
            'phone_no' => $data['phone_no'],
            'total_vehicle' => $data['total_vehicle'],
        ]);

        if($status > 0) {
            $response_status = permanently_deleted('vehicles', 'transporter_id', $data['id']);
            foreach ($data['vehicles'] as $key => $vehicle) {
                $vehicle_id = DB::table('vehicles')->insert([
                    'transporter_id' => $data['id'],
                    'vehicle_type' => $vehicle['vehicle_type'],
                    'vehicle_city' => $vehicle['vehicle_city'],
                    'vehicle_number' => $vehicle['vehicle_number']
                ]);
            }

            $finalResult = response()->json(['msg' => 'success', 'response'=>'Transporter successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }

    public function update(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'transporter_name' => 'required',
            'city' => 'required',
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $status = Transporters::where('id', $data['id'])->update([
            'transporter_name' => $data['transporter_name'],
            'city_id' => $data['city'],
            'phone_no' => $data['phone_no'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Transporter successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }

    public function destroy(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $check = count_existing_record('vehicles', 'transporter_id', $data['id']);
        if(($check > 0)){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this transporter. First delete transporter vehicles.']);
            return $finalResult;
        }else{
            $response_status = permanently_deleted('transporters', 'id', $data['id']);
            if($response_status > 0) {
                $finalResult = response()->json(['msg' => 'success', 'response'=>'Transporter successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }

    public function show_vehicles(Request $request)
    {
        $data = $request->all();
        $data['transporter'] = Transporters::where('id', $data['id'])->where('type', '1')->first();
        if(!empty($data['transporter'])){
            $data['vehicles'] = DB::table('vehicles')->where('transporter_id', $data['id'])->get();
            $htmlresult = view('admin/transporter/show_vehicles_ajax', $data)->render();
            $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
            return $finalResult;
        }
    }

    public function edit_vehicle(Request $request)
    {
        $data = $request->all();
        $data['vehicle'] = DB::table('vehicles')->where('id', $data['id'])->first();
        $htmlresult = view('admin/transporter/edit_vehicles_ajax', $data)->render();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
        return $finalResult;
    }

    public function update_vehicle(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'vehicle_type' =>'required',
            'vehicle_city' =>'required',
            'vehicle_number' => 'required|unique:vehicles,vehicle_number,'.$data['id'],
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $status = DB::table('vehicles')->where('id', $data['id'])->update([
            'vehicle_type' => $data['vehicle_type'],
            'vehicle_city' => $data['vehicle_city'],
            'vehicle_number' => $data['vehicle_number'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }

    public function add_vehicle(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'vehicle_type' =>'required',
            'vehicle_city' =>'required',
            'vehicle_number' => 'required|unique:vehicles',
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $vehicle_id = DB::table('vehicles')->insertGetId([
            'transporter_id' => $data['transporter_id'],
            'vehicle_type' => $data['vehicle_type'],
            'vehicle_city' => $data['vehicle_city'],
            'vehicle_number' => $data['vehicle_number'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        if($vehicle_id > 0) {
            $qty = get_single_value('transporters', 'total_vehicle', $data['transporter_id']);
            $new_qty = ($qty + 1);
            update_vehicle_qty($data['transporter_id'], $new_qty);
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }

    public function destroy_vehicle(Request $request, Transporters $transporters)
    {
        $data = $request->all();
        $check = count_existing_record('driver_vehicles', 'vehicle_id', $data['id']);
        $check2 = count_existing_record('bilties', 'vehicle_id', $data['id']);
        if(($check > 0) || ($check2 > 0)){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this vehicle. This vehicle is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = permanently_deleted('vehicles', 'id', $data['id']);
            if($response_status > 0) {
                $check3 = count_existing_record('vehicles', 'transporter_id', $data['transporter_id']);
                if($check3 > 0){
                    $qty = get_single_value('transporters', 'total_vehicle', $data['transporter_id']);
                    $new_qty = ($qty - 1);
                    update_vehicle_qty($data['transporter_id'], $new_qty);
                    $finalResult = response()->json(['msg' => 'success', 'response'=>$new_qty.'Vehicles successfully deleted.']);
                    return $finalResult;
                }else{
                    $response_status = permanently_deleted('transporters', 'id', $data['transporter_id']);
                    $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicles and transporter successfully deleted.']);
                    return $finalResult;
                }
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }

}