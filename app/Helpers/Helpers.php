<?php

if (!function_exists('admin_url')) {
	function admin_url() {
		return url('admin');
	}
}

if (!function_exists('formated_date')) {
	function formated_date($datee){
		return date("d/m/Y" , strtotime($datee));
	}
}
if (!function_exists('date_formated')) {
	function date_formated($datee){
		return date("d-m-Y" , strtotime($datee));
	}
}
if (!function_exists('db_date')) {
	function db_date($datee){
		return date("Y-m-d" , strtotime($datee));
	}
}
if (!function_exists('js_date_formate')) {
	function js_date_formate(){
		return "dd/mm/yyyy";
	}
}
if (!function_exists('dateTimeCC')) {
	function date_time ($time) {
		return $newDateTime = formated_date($time)." ".date('h:i A', strtotime($time));
	}
}

if (!function_exists('get_complete_table')){
	function get_complete_table($table='', $primary_key='', $where_value='', $orderby='', $sorted='') {
		$query = DB::table($table);
		if ($primary_key) {
			$query->where($primary_key, $where_value);
		}
		if ($sorted) {
			$query->orderBy($orderby, $sorted);
		}else{
			$query->orderBy('id', 'DESC');
		}
		$data = $query->get();
		return $data;
	}
}

if (!function_exists('get_vehicles')){
	function get_vehicles() {
		$query = DB::table('vehicles as v');
		$query->join('transporters as t', 'v.transporter_id', '=', 't.id');
		$query->join('vehicle_types as vt', 'v.vehicle_type', '=', 'vt.id');
		$query->join('cities as c', 'v.vehicle_city', '=', 'c.id');
		if(Auth::guard('admin')->user()->type == '1') {
			$query->where('v.created_by', Auth::guard('admin')->user()->id);
		}
		$query->select('v.id', 'v.vehicle_number', 't.transporter_name','t.phone_no', 't.id as transporter_id', 'vt.vehicle_type', 'c.name as city_name');
		$query->orderBy('t.transporter_name', 'asc');
		$data = $query->get();
		return $data;
	}
}

if (!function_exists('get_drivers_vehicles')){
	function get_drivers_vehicles() {
		$query = DB::table('driver_vehicles as dv');
		$query->join('vehicles as v', 'v.id', '=', 'dv.vehicle_id');
		// $query->join('drivers as d', 'd.id', '=', 'dv.driver_id');
		$query->select('dv.*', 'v.vehicle_type', 'v.vehicle_city', 'v.vehicle_number');
		$query->where('dv.status', '1');
		// $query->where('d.is_thief', '1');
		$query->orderBy('dv.id', 'DESC')->get();
		$data = $query->get();
		return $data;
	}
}

if (!function_exists('get_single_value')) {
	function get_single_value($table, $value, $id) {
		$query = DB::table($table)
		->select($value)
		->where('id', $id)
		->first();
		return $query->$value;
	}
}

if (!function_exists('get_section_content')) {
	function get_section_content($meta_tag, $meta_key)
	{
		$query = DB::table('settings')
		->select('meta_value')
		->where('meta_tag', $meta_tag)
		->where('meta_key',$meta_key)
		->first();
		return $query->meta_value;
	}
}

if ( ! function_exists('update_driver_current_vehicle')) {
	function update_driver_current_vehicle($driver_id, $vehicle_id) {
		$query = DB::table('drivers')
		->where('id', $driver_id)
		->update([
			'vehicle_id' => $vehicle_id
		]);
	}
}

if ( ! function_exists('update_vehicle_qty')) {
	function update_vehicle_qty($id, $qty) {
		$query = DB::table('transporters')
		->where('id', $id)
		->update([
			'total_vehicle' => $qty
		]);
	}
}

if ( ! function_exists('permanently_deleted')) {
	function permanently_deleted($table, $primary_key, $where_id) {
		$query = DB::table($table)->where($primary_key, $where_id)->delete();
		return $query;
	}
}

if (!function_exists('count_table_records')) {
	function count_table_records($table,  $type='', $status='') {
		$query = DB::table($table);
		if ($type) {
			$query->where('type', $type);
		}
		if ($status == 'only') {
			if(Auth::guard('admin')->user()->type == '1') {
				$query->where('created_by', Auth::guard('admin')->user()->id);
			}
		}else{
		}
		return $query->count();
	}
}

if (!function_exists('count_existing_record')) {
	function count_existing_record($table, $primary_key, $where_id) {
		$query = DB::table($table)->where($primary_key, $where_id)->count();
		return $query;
	}
}

if (!function_exists('get_directories')){
	function get_directories($form_id, $city_id) {
		$query = DB::table('directories');
		$query->where('form_id', $form_id);
		$query->where('city_id', $city_id);
		// if(Auth::guard('admin')->user()->type == '1') {
		// 	if(Auth::guard('admin')->user()->view_all_data == '1') {
		// 		$query->where('created_by', Auth::guard('admin')->user()->id);
		// 	}
		// }
		$query->orderBy('name', 'ASC');
		$data = $query->get();
		return $data;
	}
}

if (!function_exists('check_permissions')) {
	function check_permissions($value) {
		if( Auth::guard('admin')->user()->type == 0) {
			return 1;
		} else {
			$roles = get_single_value('admin_users', 'permissions', Auth::guard('admin')->user()->id);
			$role = explode(',', $roles);
			if(in_array($value, $role)) {
				return 1;
			} else {
				return 0;
			}
		}
	}
}

if ( ! function_exists('add_login_logs')) {
	function add_login_logs($id) {
		DB::table('login_logs')->insertGetId([
			'user_id' => $id,
			'ip' => request()->ip(),
			'login_time' => date('Y-m-d H:i:s'),
			'created_at' => date('Y-m-d H:i:s')
		]);
	}
}

if ( ! function_exists('update_login_logs')) {
	function update_login_logs($id) {
		$latest = DB::table('login_logs')->where('user_id', $id)->orderBy('id', 'DESC')->first();
		$query = DB::table('login_logs')
		->where('id', $latest->id)
		->update([
			'logout_time' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		]);
		return true;
	}
}
if (!function_exists('get_active_vehicle')) {
	function get_active_vehicle($id) {
		$query = DB::table('driver_vehicles')
		->select('vehicle_id')
		->where('status', '1')
		->where('id', $id)
		->first();
		if(!empty($query->vehicle_id)) {
			return $query->vehicle_id;
		} else {
			return 0;
		}
	}
}