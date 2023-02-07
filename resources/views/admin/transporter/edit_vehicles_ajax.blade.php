<div>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class="modal-title"><strong class="text-navy">{{ get_single_value('transporters', 'transporter_name', $vehicle->transporter_id) }}</strong> Vehicles</h5>
	</div>
	<div class="modal-body">
		<form method="post" id="edit_vehicle_form" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="id" class="form-control" value="{{ $vehicle->id }}">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">Vehicle Type</label>
						<div class="col-sm-8">
							<select class="form-control" name="vehicle_type" id="vehicle_type">
								<option value="">Select Vehicle Type</option>
								@foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
								<option value="{{ $type->id }}" @if($type->id == $vehicle->vehicle_type) selected @endif>
									{{ $type->vehicle_type }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">Vehicle City</label>
						<div class="col-sm-8">
							<select class="form-control select2_demo_4" name="vehicle_city" id="vehicle_city">
								<option value="">Select City</option>
								@foreach(get_complete_table('cities', '', '', 'name', 'asc') as $vcity)
								<option value="{{ $vcity->id }}" @if($vcity->id == $vehicle->vehicle_city) selected @endif>
									{{ $vcity->name }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">Vehicle No</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="vehicle_number" id="vehicle_number" value="{{ $vehicle->vehicle_number}}">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		<button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update_vehicle" data-style="expand-right">Save Changes</button>
	</div>
</div>