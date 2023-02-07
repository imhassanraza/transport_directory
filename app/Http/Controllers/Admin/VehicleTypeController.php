<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_permissions('vehicle_types')){
            $query = VehicleType::orderBy('id', 'DESC');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $data['types'] = $query->get();
            return view('admin/vehicle_type/manege_vehicle_types', $data);
        } else{
            return view('common/admin_404');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|unique:vehicle_types',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();
        $response_status = VehicleType::create([
            'vehicle_type' => $data['vehicle_type'],
            'capacity' => $data['capacity'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
        $status = $response_status->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle type successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\VehicleType $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleType $vehicleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\VehicleType $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $query = VehicleType::where('id', $data['id']);
        if(Auth::guard('admin')->user()->type == '1') {
            $query->where('created_by', Auth::guard('admin')->user()->id);
        }
        $vehicle = $query->first();
        $htmlresult = view('admin/vehicle_type/edit_vehicle_type_ajax', compact('vehicle'))->render();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
        return $finalResult;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\VehicleType $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'vehicle_type' => 'required|unique:vehicle_types,vehicle_type,'.$data['id'],
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $status = VehicleType::where('id', $data['id'])->update([
            'vehicle_type' => $data['vehicle_type'],
            'capacity' => $data['capacity'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle type successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\VehicleType $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, VehicleType $vehicleType)
    {
        $data = $request->all();
        $check = count_existing_record('vehicles', 'vehicle_type', $data['id']);
        if($check > 0){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this vehicle type. This vehicle type is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = VehicleType::find($data['id'])->delete();
            if($response_status > 0) {
                $finalResult = response()->json(['msg' => 'success', 'response'=>'Vehicle type successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }
}

