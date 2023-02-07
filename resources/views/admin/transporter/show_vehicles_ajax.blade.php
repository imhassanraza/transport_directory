<div>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class="modal-title"><strong class="text-navy">{{ $transporter->transporter_name }}</strong> Vehicles</h5>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
			<table id="manage_tbl" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>Sr #</th>
						<th>Vehicle Number</th>
						<th>Vehicle Type</th>
						<th>Vehicle City</th>
						{{-- <th>Action</th> --}}
					</tr>
				</thead>
				<tbody>
					@php($i = 1)
					@foreach($vehicles as $vehicle)
					<tr class="gradeX">
						<td>{{ $i++ }}</td>
						<td>
							<a href="{{ url('admin/drivers/vehicle_detail') }}/{{$vehicle->id}}" target="_blank" class="text-info">
								{{ $vehicle->vehicle_number }}
							</a>
						</td>
						<td>
							{{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }}
						</td>
						<td>
							{{ get_single_value('cities', 'name', $vehicle->vehicle_city) }}
						</td>
						{{-- <td>
							<button class="btn btn-danger btn-sm btn_delete_vehicle" data-num="{{ $vehicle->vehicle_number }}" data-id="{{ $vehicle->id }}" data-transporter-id="{{ $vehicle->transporter_id }}" type="button" data-placement="top" title="Delete"> Delete </button>
						</td> --}}
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	</div>
</div>