@extends('admin.admin_app')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2>Drivers</h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong>Driver Details</strong>
			</li>
			<li class="breadcrumb-item active">
				<strong class="label label-primary">{{ $driver->name }}</strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-4 col-sm-4 col-xs-4 text-right">
		<a class="btn btn-primary t_m_25" href="{{ url('admin/drivers') }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Drivers
		</a>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="tabs-container">
				<ul class="nav nav-tabs" role="tablist">
					<li class="show_driver_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Driver Detail</a></li>
					<li class="show_route_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Driver Routes</a></li>
					<li class="show_vehicle_tab"><a class="nav-link" data-toggle="tab" href="#tab-3">Driver Vehicles</a></li>
					<li class="show_bilty_tab"><a class="nav-link" data-toggle="tab" href="#tab-4">Bilty Detail</a></li>
				</ul>
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active show" role="tabpanel">
						<div class="row">
							<div class="col-md-4">
								<div class="ibox ">
									<div class="ibox-title">
										<h5>Driver Photo</h5>
									</div>
									<div>
										<div class="ibox-content no-padding border-left-right text-center @if($driver['is_thief'] == '2') thief_cls @endif">
											<img alt="image" class="img-fluid" src="{{ asset('assets/img') }}/{{ $driver->photo }}">
										</div>
										<div class="ibox-content profile-content">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="ibox ">
									<div class="ibox-title">
										<h5>Driver Detail</h5>
									</div>
									<div class="ibox-content">
										<div>
											<div class="feed-activity-list">
												<div class="row">
													<div class="col-lg-12">
														<div class="form-group row">
															<strong class="col-sm-2 col-form-label">Name</strong>
															<div class="col-sm-4 col-form-label">
																{{ $driver->name }}
															</div>
															<strong class="col-sm-2 col-form-label">Phone No</strong>
															<div class="col-sm-4 col-form-label">
																{{ $driver->phone_no }}
															</div>
														</div>
														<div class="form-group row">
															<strong class="col-sm-2 col-form-label">Cnic</strong>
															<div class="col-sm-4 col-form-label">
																{{ $driver->cnic }}
															</div>
															<strong class="col-sm-2 col-form-label">City</strong>
															<div class="col-sm-4 col-form-label">
																{{ get_single_value('cities', 'name', $driver->city_id) }}
															</div>
														</div>
														<div class="form-group row">
															<strong class="col-sm-2 col-form-label">Vehicle</strong>
															<div class="col-sm-4 col-form-label">
																@if(!empty($driver['vehicle_id']))
																<?php $vehicle_id = get_active_vehicle($driver['vehicle_id']); ?>
																@if(!empty($vehicle_id))
																{{ get_single_value('vehicles', 'vehicle_number', $vehicle_id) }}
																@endif
																@endif
															</div>
															<strong class="col-sm-2 col-form-label">Date</strong>
															<div class="col-sm-4 col-form-label">
																{{ date_formated($driver['created_at']) }}
															</div>
														</div>
														<div class="form-group row">
															<strong class="col-sm-2 col-form-label">Is Thief</strong>
															<div class="col-sm-4 col-form-label">
																@if($driver['is_thief'] == '2')
																<label class="label label-danger">Yes</label>
																@else
																<label class="label label-primary">No</label>
																@endif
															</div>
															<strong class="col-sm-2 col-form-label">Added By</strong>
															<div class="col-sm-4 col-form-label">
																<label class="label label-primary">{{ get_single_value('admin_users', 'username', $driver->created_by) }}</label>
															</div>
														</div>
														<div class="hr-line-dashed"></div>
														@if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $driver->created_by))
														<div class="form-group row">
															<div class="col-sm-4 offset-2">
																<a href="{{ admin_url() }}/drivers/edit/{{ $driver['id'] }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
															</div>
														</div>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div id="tab-2" class="tab-pane" role="tabpanel">

						<div class="ibox">
							<div class="ibox-title">
								<h5>Driver Routes</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Routes</th>
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($cities as $city)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>{{ get_single_value('cities', 'name', $city->city_id) }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="tab-3" class="tab-pane" role=tabpanel>
						<div class="ibox">
							<div class="ibox-title">
								<h5>Driver Vehicles</h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
										<thead>
											<tr>
												<th>Sr #</th>
												<th>Transporter</th>
												<th>Vehicle Nunber</th>
												<th>Vehicle Type</th>
												<th>Vehicle City</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											@php($i = 1)
											@foreach($vehicles as $vehicle)
											<tr class="gradeX">
												<td>{{ $i++ }}</td>
												<td>
													{{ get_single_value('transporters', 'transporter_name', $vehicle->transporter_id) }}
												</td>
												<td>
													<a href="{{ url('admin/drivers/vehicle_detail') }}/{{ $vehicle->vehicle_id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
														{{ $vehicle->vehicle_number }}
													</a>
												</td>
												<td>{{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }}</td>
												<td>
													<a href="{{ url('admin/city/show') }}/{{ $vehicle->vehicle_city }}" target="_blank" class="text-info">
														{{ get_single_value('cities', 'name', $vehicle->vehicle_city) }}
													</a>
												</td>
												<td>{{ $vehicle->start_date }}</td>
												<td>{{ $vehicle->end_date }}</td>
												<td>
													<?php if($vehicle->status == 1){ ?>
														<span class="label label-primary">Active Vehicle</span>
													<?php } else{ ?>
														<span class="label label-danger">Inactive Vehicle</span>
														<?php } ?>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div id="tab-4" class="tab-pane" role=tabpanel>
							<div class="ibox">
								<div class="ibox-title">
									<h5>Bilty Detail</h5>
								</div>
								<div class="ibox-content">
									<div class="table-responsive">
										<table class="table table-striped table-bordered dt-responsive nowrap table_tbl6" style="width:100%">
											<thead>
												<tr>
													<th>Sr #</th>
													<th>Vehicle Number</th>
													<th>Vehicle Type</th>
													<th>Source City</th>
													<th>Distination City</th>
													<th>Guarantor</th>
													<th>Bilty Detail</th>
													<th>Bilty Data</th>
												</tr>
											</thead>
											<tbody>
												@php($i = 1)
												@foreach($loadings as $lodgs)
												<tr class="gradeX">
													<td>{{ $i++ }}</td>
													<td>
														<a href="{{ url('admin/drivers/vehicle_detail') }}/{{ $lodgs->vehicle_id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
															{{ $lodgs->vehicle_number }}
														</a>
													</td>
													<td>
														{{ get_single_value('vehicle_types', 'vehicle_type', $lodgs->vehicle_type) }}
													</td>
													<td>
														<a href="{{ url('admin/city/show') }}/{{ $lodgs->source_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
															{{ get_single_value('cities', 'name', $lodgs->source_city) }}
														</a>
													</td>
													<td>
														<a href="{{ url('admin/city/show') }}/{{ $lodgs->destination_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
															{{ get_single_value('cities', 'name', $lodgs->destination_city) }}
														</a>
													</td>
													<td>{{ $lodgs->guarantor_detail }}</td>
													<td>
														<?php echo wordwrap($lodgs->bilty_details, 180, "<br>\n"); ?>
													</td>

													<td>{{ date_formated($lodgs->bilty_date) }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

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

		$(document).on('click', '.show_bilty_tab', function () {
			if($('#DataTables_Table_2').length > 0){
			}else{
				$('.table_tbl6').dataTable({
					"paging": true,
					"searching": true,
					"bInfo":true,
					"responsive": true,
					"lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
					"columnDefs": [
						{ "responsivePriority": 1, "targets": 0 },
						{ "responsivePriority": 2, "targets": -1 },
						]
				});
			}
		});
	</script>
	@endpush