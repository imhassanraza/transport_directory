@extends('admin.admin_app')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2>Cities</h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong>City Details</strong>
			</li>
			<li class="breadcrumb-item active">
				<strong class="label label-primary">{{ $city->name }}</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-4 col-sm-4 col-xs-4 text-right">
		<a class="btn btn-primary t_m_25" href="{{ url('admin/city') }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Cities
		</a>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="tabs-container">
				<ul class="nav nav-tabs" role="tablist">
					<li class="show_vehicle_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Drivers</a></li>
					<li class="show_route_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Vehicles</a></li>
					<li class="show_vehicle_tab"><a class="nav-link" data-toggle="tab" href="#tab-4">Transporters</a></li>
					<li class="show_vehicle_tab"><a class="nav-link" data-toggle="tab" href="#tab-3">Cold Storages</a></li>
					@foreach(get_complete_table('forms', '', '', 'name', 'asc') as $form)
					<li class="show_vehicle_tab"><a class="nav-link" data-toggle="tab" href="#tab-dir-{{ $form->id }}">{{ $form->name }}</a></li>
					@endforeach
				</ul>
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active show" role="tabpanel">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Drivers</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Driver</th>
												<th>Phone No</th>
												<th>CNIC</th>
												<th>Is Theif</th>
												<th>Vehicle Number</th>
												<th>Added By</th>
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($drivers as $driver)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>
													<a href="{{ url('admin/drivers/show') }}/{{$driver->id}}" target="_blank" class="text-info">
														<img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $driver->photo }}" style="width: 30px;"><br>
														{{ $driver->name }}
													</a>
												</td>
												<td>{{ $driver->phone_no }}</td>
												<td>{{ $driver->cnic }}</td>
												<td>
													@if($driver->is_thief == '2')
													<label class="label label-danger">Yes</label>
													@else
													<label class="label label-primary">No</label>
													@endif
												</td>
												<td>
													<?php if(!empty($driver->vehicle_id)) {
														$vehicle_id = get_single_value('driver_vehicles', 'vehicle_id', $driver->vehicle_id);
														echo get_single_value('vehicles', 'vehicle_number', $vehicle_id);
													} ?>
												</td>
												<td>{{ get_single_value('admin_users', 'username', $driver->created_by) }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="tab-2" class="tab-pane" role="tabpanel">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Vehicles</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Vehicle Number</th>
												<th>Vehicle Type</th>
												<th>Transporter</th>
												<th>Phone No</th>
												@if(Auth::guard('admin')->user()->type == '0')
												<th>Added By</th>
												@endif
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($vehicles as $vehicle)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>
													<a href="{{ url('admin/drivers/vehicle_detail') }}/{{ $vehicle->id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
														{{ $vehicle->vehicle_number }}
													</a>
												</td>
												<td>{{ $vehicle->vehicle_type }}</td>
												<td>
													<a href="{{ url('admin/transporters/show') }}/{{ $vehicle->transporter_id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
														{{ $vehicle->transporter_name }}
													</a>
												</td>
												<td>{{ $vehicle->phone_no }}</td>
												@if(Auth::guard('admin')->user()->type == '0')
												<td>{{ get_single_value('admin_users', 'username', $vehicle->created_by) }}</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="tab-4" class="tab-pane" role="tabpanel">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Transporters</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Transporter Name</th>
												<th>Phone No</th>
												<th>Vehicle Qty</th>
												@if(Auth::guard('admin')->user()->type == '0')
												<th>Added By</th>
												@endif
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($transporters as $transporter)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>
													<a href="{{ url('admin/transporters/show') }}/{{ $transporter->id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
														{{ $transporter->transporter_name }}
													</a>
												</td>
												<td>{{ $transporter->phone_no }}</td>
												<td>{{ $transporter->total_vehicle }}</td>
												@if(Auth::guard('admin')->user()->type == '0')
												<td>{{ get_single_value('admin_users', 'username', $transporter->created_by) }}</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="tab-3" class="tab-pane" role="tabpanel">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Cold Storages</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Store Name</th>
												<th>Owner Name</th>
												<th>Manager Name</th>
												<th>Address</th>
												@if(Auth::guard('admin')->user()->type == '0')
												<th>Added By</th>
												@endif
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($stores as $store)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>
													<a href="{{ url('admin/cold-storage') }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
														{{ $store->store_name }}
													</a>
												</td>
												<td>
													{{ $store->owner_name }}
													<br>
													{{ $store->owner_phone }}
												</td>
												<td>
													{{ $store->manager_name }}
													<br>
													{{ $store->manager_phone }}
												</td>
												<td>{{ $store->address }}</td>
												@if(Auth::guard('admin')->user()->type == '0')
												<td>{{ get_single_value('admin_users', 'username', $store->created_by) }}</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					@foreach(get_complete_table('forms', '', '', 'name', 'asc') as $form)
					<div id="tab-dir-{{ $form->id }}" class="tab-pane" role="tabpanel">
						<div class="ibox">
							<div class="ibox-title">
								<h5>{{ $form->name }}</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Name</th>
												<th>Phone No</th>
												<th>Added By</th>
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach(get_directories($form->id, $city->id) as $directory)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>{{ $directory->name }}</td>
												<td>{{ $directory->phone_no }}</td>
												<td>{{ get_single_value('admin_users', 'username', $directory->created_by) }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@push('scripts')
<script>
	$('.table_tbl5').dataTable({
		"paging": true,
		"searching": true,
		"bInfo":true,
		"responsive": true,
		"lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
		"columnDefs": [
			{ "responsivePriority": 1, "targets": 0 },
			{ "responsivePriority": 2, "targets": -1 },
			{ "responsivePriority": 3, "targets": -2 },
			]
	});
</script>
@endpush