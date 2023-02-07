<div>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class="modal-title">Edit Directory</h5>
	</div>
	<div class="modal-body">
		<form id="edit_form" method="post" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="id" class="form-control" value="{{ $dir->id }}">
			<div class="form-group row">
				<label class="col-sm-4 col-form-label"><strong>Select Directory</strong></label>
				<div class="col-sm-8">
					<select class="form-control" name="form" id="form">
						<option value="">Select Directory Type</option>
						@foreach(get_complete_table('forms', '', '', 'name', 'asc') as $form)
						<option value="{{ $form->id }}" @if($form->id == $dir->form_id) selected @endif>
							{{ $form->name }}
						</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 col-form-label"><strong>Name</strong></label>
				<div class="col-sm-8">
					<input type="text" name="name" class="form-control input-sm" placeholder="Name" value="{{ $dir->name }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 col-form-label"><strong>Phone No</strong></label>
				<div class="col-sm-8">
					<input type="text" name="phone_no" class="form-control input-sm" placeholder="Phone No" value="{{ $dir->phone_no }}">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 col-form-label"><strong>City</strong></label>
				<div class="col-sm-8">
					<select class="form-control select2_demo_4" name="city" id="city">
						<option value="">Select City</option>
						@foreach(get_complete_table('cities', '', '', 'name', 'asc') as $city)
						<option value="{{ $city->id }}"  @if($city->id == $dir->city_id) selected @endif>
							{{ $city->name }}
						</option>
						@endforeach
					</select>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" id="update_button"> Save Changes </button>
	</div>
</div>