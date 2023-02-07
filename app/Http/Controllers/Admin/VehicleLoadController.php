<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleLoad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class VehicleLoadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_permissions('bilties')){
            $query = DB::table('bilties as vl');
            $query->join('drivers as d', 'vl.driver_id', '=', 'd.id');
            $query->join('vehicles as v', 'vl.vehicle_id', '=', 'v.id');
            $query->select('vl.*', 'd.name as driver_name', 'd.photo', 'd.phone_no', 'v.vehicle_type', 'v.vehicle_number');
            $query->orderBy('vl.id', 'DESC');
            if(Auth::guard('admin')->user()->type == '1') {
                $query->where('vl.created_by', Auth::guard('admin')->user()->id);
            }
            $data['loadings'] = $query->get();
            return view('admin/loading/manage_loading', $data);
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
        if(check_permissions('bilties')){
            return view('admin/loading/add_loading');
        } else{
            return view('common/admin_404');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_driver(Request $request)
    {
        $data = $request->all();
        $data['driver'] = DB::table('drivers')
        ->join('cities', 'drivers.city_id', '=', 'cities.id')
        ->select('drivers.*', 'cities.name as city')
        ->where('drivers.id', $data['driver_id'])
        ->first();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$data['driver']]);
        return $finalResult;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'bilty_number' => 'required|unique:bilties',
            'vehicle' => 'required',
            'driver' => 'required',
            'source_city' => 'required',
            'destination_city' => 'required',
            'bilty_date' => 'required',
            'guarantor_detail' => 'required',
            'bilty_details' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $data['bilty_image'] = '';
        if ($request->hasFile('bilty_image')) {
            $image = $request->file('bilty_image');
            $data['bilty_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $data['bilty_image']);
        }
        $data['driver_image'] = '';
        if ($request->hasFile('driver_image')) {
            $image = $request->file('driver_image');
            $data['driver_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $data['driver_image']);
        }
        if (isset($data['bilty_insurance'])) {
            $bilty_insurance = '2';
        } else {
            $bilty_insurance = '1';
        }

        $query = VehicleLoad::create([
            'bilty_number' => $data['bilty_number'],
            'bilty_image' => $data['bilty_image'],
            'driver_image' => $data['driver_image'],
            'vehicle_id' => $data['vehicle'],
            'driver_id' => $data['driver'],
            'source_city' => $data['source_city'],
            'destination_city' => $data['destination_city'],
            'bilty_details' => $data['bilty_details'],
            'guarantor_detail' => $data['guarantor_detail'],
            'bilty_insurance' => $bilty_insurance,
            'bilty_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['bilty_date']))),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        $status = $query->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Bilty successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\VehicleLoad  $vehicleLoad
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleLoad $vehicleLoad)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\VehicleLoad  $vehicleLoad
     * @return \Illuminate\Http\Response
     */
    public function edit($id='')
    {
        if(check_permissions('bilties')){
            $query = VehicleLoad::where('id', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
            }
            $data['loading'] = $query->first();
            if(!empty($data['loading'])){
                return view('admin/loading/edit_loading', $data);
            }else{
                return view('common/admin_404');
            }
        }else{
            return view('common/admin_404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\VehicleLoad  $vehicleLoad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleLoad $vehicleLoad)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'bilty_number' => 'required|unique:bilties,bilty_number,'.$data['loading_id'],
            'vehicle' => 'required',
            'driver' => 'required',
            'source_city' => 'required',
            'destination_city' => 'required',
            'bilty_date' => 'required',
            'guarantor_detail' => 'required',
            'bilty_details' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        if ($request->hasFile('bilty_image')) {
            $image = $request->file('bilty_image');
            $data['bilty_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $data['bilty_image']);

            $query = DB::table('bilties')
            ->where('id', $data['loading_id'])->update([
                'bilty_image' => $data['bilty_image'],
            ]);
        }
        if ($request->hasFile('driver_image')) {
            $image = $request->file('driver_image');
            $data['driver_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $data['driver_image']);

            $query = DB::table('bilties')
            ->where('id', $data['loading_id'])->update([
                'driver_image' => $data['driver_image'],
            ]);
        }

        if (isset($data['bilty_insurance'])) {
            $bilty_insurance = '2';
        } else {
            $bilty_insurance = '1';
        }

        $status = VehicleLoad::where('id', $data['loading_id'])->update([
            'bilty_number' => $data['bilty_number'],
            'vehicle_id' => $data['vehicle'],
            'driver_id' => $data['driver'],
            'source_city' => $data['source_city'],
            'destination_city' => $data['destination_city'],
            'bilty_details' => $data['bilty_details'],
            'guarantor_detail' => $data['guarantor_detail'],
            'bilty_insurance' => $bilty_insurance,
            'bilty_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['bilty_date']))),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Bilty successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function delivered_bilties(Request $request, VehicleLoad $vehicleLoad)
    {
        $data = $request->all();
        if (isset($data['bilty_insurance'])) {
            $bilty_insurance = '2';
        } else {
            $bilty_insurance = '1';
        }

        $status = VehicleLoad::where('id',  $data['id'])->update([
            'status' => '2',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Bilty successfully completed.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\VehicleLoad  $vehicleLoad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, VehicleLoad $vehicleLoad)
    {
        $data = $request->all();
        $response_status = permanently_deleted('bilties', 'id', $data['id']);
        if($response_status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Bilty successfully deleted.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }
}
