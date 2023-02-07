<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_permissions('drivers')){
            $query = Driver::orderBy('id', 'DESC');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $data['drivers'] = $query->get();
            return view('admin/drivers/manage_drivers', $data);
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
        if(check_permissions('drivers')){
            return view('admin/drivers/add_driver');
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'picture' => 'mimes:jpeg,jpg,png,gif|max:10240',
            'phone_no' => 'required',
            'cnic' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $data = $request->all();
        $image_name = 'driver.png';
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $image_name);
        }

        $query = Driver::create([
            'name' => $data['name'],
            'phone_no' => $data['phone_no'],
            'cnic' => $data['cnic'],
            'city_id' => $data['city'],
            'photo' => $image_name,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        $status = $query->id;
        if($status > 0) {
            if(isset($data['cities'])) {
                foreach ($data['cities'] as $city) {
                    DB::table('driver_routes')->insertGetId([
                        'driver_id' => $query->id,
                        'city_id' => $city,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::guard('admin')->user()->id,
                    ]);
                }
            }

            if(!empty($data['vehicle_number'])){
                $vehicle_id = DB::table('driver_vehicles')->insertGetId([
                    'driver_id' => $query->id,
                    'vehicle_id' => $data['vehicle_number'],
                    'start_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);

                if($vehicle_id > 0) {
                    update_driver_current_vehicle($query->id, $vehicle_id);
                }
            }

            $finalResult = response()->json(['msg' => 'success', 'response'=>'Driver successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show($id='')
    {
        $query = Driver::where('id', $id);
        // if(Auth::guard('admin')->user()->type == '1') {
        //     if(Auth::guard('admin')->user()->view_all_data == '1') {
        //         $query->where('created_by', Auth::guard('admin')->user()->id);
        //     }
        // }
        $data['driver'] = $query->first();

        if(!empty($data['driver'])){
            $query2 = DB::table('driver_routes');
            $query2->where('driver_id', $id);
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query2->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $data['cities'] = $query2->get();

            $query3 = DB::table('driver_vehicles as dv');
            $query3->join('vehicles as v', 'v.id', '=', 'dv.vehicle_id');
            $query3->where('dv.driver_id', $id);
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query3->where('dv.created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $query3->select('dv.*', 'v.vehicle_type', 'v.transporter_id', 'v.vehicle_city', 'v.vehicle_number');
            $query3->orderBy('dv.id', 'DESC');
            $data['vehicles'] = $query3->get();

            $query4 = DB::table('bilties as vl');
            $query4->join('vehicles as v', 'v.id', '=', 'vl.vehicle_id');
            $query4->where('vl.driver_id', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                $query4->where('vl.created_by', Auth::guard('admin')->user()->id);
            }
            $query4->select('vl.*', 'v.vehicle_type', 'v.vehicle_number');
            $query4->orderBy('vl.id', 'DESC');
            $data['loadings'] = $query4->get();

            return view('admin/drivers/show_driver_details', $data);
        }else{
            return view('common/admin_404');
        }
    }


    public function vehicle_detail($num='')
    {
        $data['vehicle'] = DB::table('vehicles')->where('id', $num)->first();
        if(!empty($data['vehicle'])){

            $query = DB::table('driver_vehicles as dv');
            $query->join('drivers as d', 'dv.driver_id', '=', 'd.id');
            $query->join('vehicles as v', 'dv.vehicle_id', '=', 'v.id');
            $query->where('dv.vehicle_id', $num);
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('dv.created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $query->select('d.id', 'd.name', 'd.photo', 'd.phone_no', 'd.cnic', 'd.city_id', 'd.created_at', 'dv.start_date', 'dv.end_date', 'v.vehicle_type');
            $query->orderBy('dv.id', 'DESC');
            $data['drivers'] = $query->get();

            $query2 = DB::table('bilties as vl');
            $query2->join('drivers as d', 'vl.driver_id', '=', 'd.id');
            $query2->join('vehicles as v', 'vl.vehicle_id', '=', 'v.id');
            $query2->where('vl.vehicle_id', $num);
            if(Auth::guard('admin')->user()->type == '1') {
                $query2->where('vl.created_by', Auth::guard('admin')->user()->id);
            }
            $query2->select('vl.*', 'd.name', 'd.photo', 'd.phone_no', 'd.cnic', 'v.vehicle_type');
            $query2->orderBy('vl.id', 'DESC');
            $data['loadings'] = $query2->get();
            return view('admin/drivers/vehicle_details', $data);
        }else{
            return view('common/admin_404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit($id='')
    {
        if(check_permissions('drivers')){
            $query = Driver::where('id', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                $query->where('created_by', Auth::guard('admin')->user()->id);
            }
            $data['driver'] = $query->first();

            if(!empty($data['driver'])){

                $query2 = DB::table('driver_routes');
                $query2->where('driver_id', $id);
                if(Auth::guard('admin')->user()->type == '1') {
                    $query2->where('created_by', Auth::guard('admin')->user()->id);
                }
                $data['cities'] = $query2->get();

                $query3 = DB::table('driver_vehicles');
                $query3->where('driver_id', $id);
                if(Auth::guard('admin')->user()->type == '1') {
                    $query3->where('created_by', Auth::guard('admin')->user()->id);
                }
                $query3->orderBy('id', 'DESC');
                $data['dvehicle'] = $query3->first();

                return view('admin/drivers/edit_driver', $data);
            }else{
                return view('common/admin_404');
            }
        } else{
            return view('common/admin_404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'picture' => 'mimes:jpeg,jpg,png,gif|max:10240',
            'phone_no' => 'required',
            'cnic' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();
        $data['image_name'] = $data['old_pic'];
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $data['image_name'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/img');
            $image->move($destinationPath, $data['image_name']);
        }

        if (isset($data['is_thief'])) {
            $is_thief = '2';
        } else {
            $is_thief = '1';
        }

        $status = Driver::where('id', $data['driver_id'])->update([
            'name' => $data['name'],
            'phone_no' => $data['phone_no'],
            'cnic' => $data['cnic'],
            'city_id' => $data['city'],
            'photo' => $data['image_name'],
            'is_thief' => $is_thief,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Driver successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function update_routes(Request $request, Driver $driver)
    {
        $validator = Validator::make($request->all(), [
            'cities' => 'required',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();
        $response_status = permanently_deleted('driver_routes', 'driver_id', $data['driver_id']);
        foreach ($data['cities'] as $city) {
            $status = DB::table('driver_routes')->insertGetId([
                'driver_id' => $data['driver_id'],
                'city_id' => $city,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::guard('admin')->user()->id,
            ]);
        }

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'Driver routes successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    public function update_vehicles(Request $request, Driver $driver)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required'
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();

        $query = DB::table('driver_vehicles')
        ->where('status', 1)
        ->where('vehicle_id', $data['vehicle_number'])
        ->update([
            'status' => '0',
            'end_date' => date('Y-m-d'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        $query = DB::table('driver_vehicles')
        ->where('status', '1')
        ->where('driver_id', $data['driver_id'])
        ->update([
            'status' => '0',
            'end_date' => date('Y-m-d'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        $vehicle_id = DB::table('driver_vehicles')->insertGetId([
            'driver_id' => $data['driver_id'],
            'vehicle_id' => $data['vehicle_number'],
            'start_date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);

        if($vehicle_id > 0) {
            update_driver_current_vehicle($data['driver_id'], $vehicle_id);

            $finalResult = response()->json(['msg' => 'success', 'response'=>'Driver vehicle successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Driver $driver)
    {
        $data = $request->all();
        $check = count_existing_record('bilties', 'driver_id', $data['id']);
        if($check > 0){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this driver. This driver is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = permanently_deleted('drivers', 'id', $data['id']);
            if($response_status > 0) {
                $response_status = permanently_deleted('driver_routes', 'driver_id', $data['id']);
                $response_status = permanently_deleted('driver_vehicles', 'driver_id', $data['id']);
                $finalResult = response()->json(['msg' => 'success', 'response'=>'Driver successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }
}
