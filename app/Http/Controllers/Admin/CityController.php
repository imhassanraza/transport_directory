<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session, Validator, DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_permissions('cities')){
            $query = City::orderBy('name', 'ASC');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $data['cities'] = $query->get();
            return view('admin/city/manage_cities', $data);
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
            'name' => 'required|unique:cities',
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }

        $data = $request->all();
        $response_status = City::create([
            'name' => $data['name'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::guard('admin')->user()->id,
        ]);
        $status = $response_status->id;
        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'City successfully added.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city, $id='')
    {
        $data['city'] = City::where('id', $id)->first();

        if(!empty($data['city'])){

            $query1 = DB::table('drivers');
            // if(Auth::guard('admin')->user()->type == '1') {
            //     if(Auth::guard('admin')->user()->view_all_data == '1') {
            //         $query1->where('created_by', Auth::guard('admin')->user()->id);
            //     }
            // }
            $query1->where('city_id', $id)->orderBy('name', 'ASC');
            $data['drivers'] = $query1->get();

            $query2 = DB::table('vehicles as v');
            $query2->join('vehicle_types as vt', 'v.vehicle_type', '=', 'vt.id');
            $query2->join('transporters as t', 'v.transporter_id', '=', 't.id');
            $query2->select('v.id', 'v.vehicle_number', 'v.vehicle_city', 'v.transporter_id', 'v.created_by', 't.transporter_name', 't.phone_no', 'vt.vehicle_type');
            $query2->where('v.vehicle_city', $id);
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query2->where('v.created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query2->orderBy('v.id', 'DESC');
            $data['vehicles'] = $query2->get();

            $query3 = DB::table('cold_storages');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query3->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query3->where('city_id', $id);
            $query3->orderBy('store_name', 'ASC');
            $data['stores'] = $query3->get();

            $query4 = DB::table('transporters');
            if(Auth::guard('admin')->user()->type == '1') {
                // if(Auth::guard('admin')->user()->view_all_data == '1') {
                $query4->where('created_by', Auth::guard('admin')->user()->id);
                // }
            }
            $query4->where('city_id', $id);
            $query4->orderBy('transporter_name', 'ASC');
            $data['transporters'] = $query4->get();

            return view('admin/city/show_city_details', $data);
        }else{
            return view('common/admin_404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $query = City::where('id', $data['id']);
        if(Auth::guard('admin')->user()->type == '1') {
            $query->where('created_by', Auth::guard('admin')->user()->id);
        }
        $city = $query->first();
        $htmlresult = view('admin/city/edit_city_ajax', compact('city'))->render();
        $finalResult = response()->json(['msg' => 'success', 'response'=>$htmlresult]);
        return $finalResult;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|unique:cities,name,'.$data['id'],
        ]);

        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        $status = City::where('id', $data['id'])->update([
            'name' => $data['name'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::guard('admin')->user()->id,
        ]);

        if($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response'=>'City successfully updated.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, City $city)
    {
        $data = $request->all();
        $check = count_existing_record('drivers', 'city_id', $data['id']);
        $check2 = count_existing_record('transporters', 'city_id', $data['id']);
        $check3 = count_existing_record('cold_storages', 'city_id', $data['id']);
        $check4 = count_existing_record('directories', 'city_id', $data['id']);
        $check5 = count_existing_record('driver_routes', 'city_id', $data['id']);
        $check6 = count_existing_record('vehicles', 'vehicle_city', $data['id']);
        $check7 = count_existing_record('bilties', 'source_city', $data['id']);
        $check8 = count_existing_record('bilties', 'destination_city', $data['id']);
        if(($check > 0) || ($check2 > 0) || ($check3 > 0) || ($check4 > 0) || ($check5 > 0) || ($check6 > 0) || ($check7 > 0) || ($check8 > 0)){
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Unable to delete this city. This city is associated with other records.']);
            return $finalResult;
        }else{
            $response_status = City::find($data['id'])->delete();
            if($response_status > 0) {
                $finalResult = response()->json(['msg' => 'success', 'response'=>'City successfully deleted.']);
                return $finalResult;
            } else {
                $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
                return $finalResult;
            }
        }
    }
}
