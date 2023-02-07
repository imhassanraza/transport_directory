@extends('admin.admin_app')
@section('content')
<div class="wrapper wrapper-content animated fadeIn">
	<div class="row">
		@if(check_permissions('ony_admin'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Users</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('admin_users', '1', 'all') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/users') }}"><span class="label label-primary">View</span></a></div>
					<small>Users</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('cities'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Cities</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('cities', '', 'all') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/city') }}"><span class="label label-primary">View</span></a></div>
					<small>Cities</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('vehicle_types'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Vehicle Types</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('vehicle_types', '', 'all') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/vehicle-types') }}"><span class="label label-primary">View</span></a></div>
					<small>Vehicle Types</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('vehicles'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Vehicles</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('transporters', '2', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/vehicles') }}"><span class="label label-primary">View</span></a></div>
					<small>Vehicles</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('transporters'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Transporters</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('transporters', '1', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/transporters') }}"><span class="label label-primary">View</span></a></div>
					<small>Transporters</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('drivers'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Drivers</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('drivers', '', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/drivers') }}"><span class="label label-primary">View</span></a></div>
					<small>Drivers</small>
				</div>
			</div>
		</div>
		@endif

		@if(check_permissions('bilties'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Bilties</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('bilties', '', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/bilty') }}"><span class="label label-primary">View</span></a></div>
					<small>Bilties</small>
				</div>
			</div>
		</div>
		@endif


		@if(check_permissions('cold_storages'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Cold Storages</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('cold_storages', '', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/cold-storage') }}"><span class="label label-primary">View</span></a></div>
					<small>Cold Storages</small>
				</div>
			</div>
		</div>
		@endif
		@if(check_permissions('directory_types'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Directory Types</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('forms', '', 'all') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/directory_types') }}"><span class="label label-primary">View</span></a></div>
					<small>Directory Types</small>
				</div>
			</div>
		</div>
		@endif
		@if(check_permissions('directories'))
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Directories</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{ number_format( count_table_records('directories', '', 'only') ); }}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/directories') }}"><span class="label label-primary">View</span></a></div>
					<small>Directories</small>
				</div>
			</div>
		</div>
		@endif

	</div>
</div>
@endsection