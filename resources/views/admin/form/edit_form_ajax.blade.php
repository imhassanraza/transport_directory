<div>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class="modal-title">Edit Directory Type</h5>
	</div>
	<div class="modal-body">
		<form id="edit_form" method="post" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="id" class="form-control" value="{{ $form['id'] }}">
			<div class="form-group row">
				<label class="col-sm-4 col-form-label"><strong>Directory Type</strong></label>
				<div class="col-sm-8">
					<input type="text" name="name" class="form-control input-sm" placeholder="Directory Type" value="{{ $form['name'] }}">
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" id="update_button"> Save Changes </button>
	</div>
</div>