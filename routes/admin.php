<?php
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\VehicleLoadController;
use App\Http\Controllers\Admin\ColdStorageController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\DirectoryController;
use App\Http\Controllers\Admin\TransportersController;
use App\Http\Controllers\Admin\VehicleController;


Route::group(['prefix'  =>  'admin'], function () {
	Route::get('login', [AdminLoginController::class, 'index'])->name('login');
	Route::post('verify_login', [AdminLoginController::class, 'verify_login'])->name('verify_login');
	Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');

	Route::group(['middleware' => ['auth:admin']], function () {
		Route::get('/', [AdminController::class, 'index']);
		Route::get('admin', [AdminController::class, 'index']);
		Route::get('change_password', [AdminController::class, 'change_password']);
		Route::post('update_password', [AdminController::class, 'update_password']);

		Route::get('users', [AdminController::class, 'users']);
		Route::get('create', [AdminController::class, 'create']);
		Route::post('store', [AdminController::class, 'store']);
		Route::get('edit/{id}', [AdminController::class, 'edit']);
		Route::post('update', [AdminController::class, 'update']);
		Route::post('delete', [AdminController::class, 'destroy']);

		Route::group(['prefix'  =>   'city'], function() {
			Route::get('/', [CityController::class, 'index']);
			Route::post('store', [CityController::class, 'store']);
			Route::post('edit', [CityController::class, 'edit']);
			Route::get('show/{id}', [CityController::class, 'show']);
			Route::post('update', [CityController::class, 'update']);
			Route::post('delete', [CityController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'vehicle-types'], function() {
			Route::get('/', [VehicleTypeController::class, 'index']);
			Route::post('store', [VehicleTypeController::class, 'store']);
			Route::post('edit', [VehicleTypeController::class, 'edit']);
			Route::post('update', [VehicleTypeController::class, 'update']);
			Route::post('delete', [VehicleTypeController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'drivers'], function() {
			Route::get('/', [DriverController::class, 'index']);
			Route::get('create', [DriverController::class, 'create']);
			Route::post('store', [DriverController::class, 'store']);
			Route::get('edit/{id}', [DriverController::class, 'edit']);
			Route::get('show/{id}', [DriverController::class, 'show']);
			Route::get('vehicle_detail/{num}', [DriverController::class, 'vehicle_detail']);
			Route::post('update_routes', [DriverController::class, 'update_routes']);
			Route::post('update_vehicles', [DriverController::class, 'update_vehicles']);
			Route::post('update', [DriverController::class, 'update']);
			Route::post('delete', [DriverController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'bilty'], function() {
			Route::get('/', [VehicleLoadController::class, 'index']);
			Route::get('create', [VehicleLoadController::class, 'create']);
			Route::post('store', [VehicleLoadController::class, 'store']);
			Route::post('get_driver', [VehicleLoadController::class, 'get_driver']);
			Route::get('edit/{id}', [VehicleLoadController::class, 'edit']);
			Route::post('update', [VehicleLoadController::class, 'update']);
			Route::post('delete', [VehicleLoadController::class, 'destroy']);
			Route::post('delivered_bilties', [VehicleLoadController::class, 'delivered_bilties']);
		});

		Route::group(['prefix'  =>   'cold-storage'], function() {
			Route::get('/', [ColdStorageController::class, 'index']);
			Route::get('create', [ColdStorageController::class, 'create']);
			Route::post('store', [ColdStorageController::class, 'store']);
			Route::get('edit/{id}', [ColdStorageController::class, 'edit']);
			Route::post('update', [ColdStorageController::class, 'update']);
			Route::post('delete', [ColdStorageController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'directory_types'], function() {
			Route::get('/', [FormController::class, 'index']);
			Route::post('store', [FormController::class, 'store']);
			Route::post('edit', [FormController::class, 'edit']);
			Route::post('update', [FormController::class, 'update']);
			Route::post('delete', [FormController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'directories'], function() {
			Route::get('/', [DirectoryController::class, 'index']);
			Route::post('store', [DirectoryController::class, 'store']);
			Route::post('edit', [DirectoryController::class, 'edit']);
			Route::post('update', [DirectoryController::class, 'update']);
			Route::post('delete', [DirectoryController::class, 'destroy']);
		});

		Route::group(['prefix'  =>   'transporters'], function() {
			Route::get('/', [TransportersController::class, 'index']);
			Route::get('/create', [TransportersController::class, 'create']);
			Route::post('store', [TransportersController::class, 'store']);
			Route::get('edit/{id}', [TransportersController::class, 'edit']);
			Route::get('show/{id}', [TransportersController::class, 'show']);
			Route::post('update', [TransportersController::class, 'update']);
			Route::post('delete', [TransportersController::class, 'destroy']);
			Route::post('delete_vehicle', [TransportersController::class, 'destroy_vehicle']);
			Route::post('show_vehicles', [TransportersController::class, 'show_vehicles']);
			Route::post('add_vehicle', [TransportersController::class, 'add_vehicle']);
			Route::post('edit_vehicle', [TransportersController::class, 'edit_vehicle']);
			Route::post('update_vehicle', [TransportersController::class, 'update_vehicle']);
		});

		Route::group(['prefix'  =>   'vehicles'], function() {
			Route::get('/', [VehicleController::class, 'index']);
			Route::get('create', [VehicleController::class, 'create']);
			Route::post('store', [VehicleController::class, 'store']);
			Route::get('edit/{id}', [VehicleController::class, 'edit']);
			Route::post('update', [VehicleController::class, 'update']);
			Route::post('delete', [VehicleController::class, 'destroy']);
		});

	});
});