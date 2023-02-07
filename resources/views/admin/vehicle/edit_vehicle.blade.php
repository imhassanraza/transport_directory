@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Vehicles </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Vehicle </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/vehicles') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Vehicles
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <form method="post" id="edit_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" class="form-control" value="{{ $owner->id }}">
                <input type="hidden" name="vehicle_id" class="form-control" value="{{ $vehicle->id }}">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Owner Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Owner Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="owner_name" value="{{ $owner->transporter_name}}">
                                    </div>
                                    <label class="col-sm-1 col-form-label">Owner City</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2_demo_3" name="city">
                                            <option value="">Select City</option>
                                            @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $city)
                                            <option value="{{ $city->id }}" @if($city->id == $owner->city_id) selected @endif>
                                                {{ $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ $owner->phone_no}}">
                                    </div>
                                </div>
                                <h3>Vehicle Details</h3>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Vehicle Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="vehicle_type" id="vehicle_type">
                                            <option value="">Select Vehicle Type</option>
                                            @foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
                                            <option value="{{ $type->id }}" @if($type->id == $vehicle->vehicle_type) selected @endif>
                                                {{ $type->vehicle_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Vehicle City</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2_demo_3" name="vehicle_city" id="vehicle_city">
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
                                    <label class="col-sm-2 col-form-label">Vehicle No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="vehicle_number" id="vehicle_number" value="{{ $vehicle->vehicle_number}}">
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <div class="col-sm-4 offset-2">
                                        <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/vehicles') }}">Cancel</button>
                                        <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update" data-style="expand-right">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".select2_demo_3").select2({
            placeholder: 'Select City',
            allowClear: true
        });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>
<script>
    $(document).on("click" , "#btn_update" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#edit_form")[0]);
        $.ajax({
            url:'{{ url('admin/vehicles/update') }}',
            type: 'POST',
            data: formData,
            dataType:'json',
            cache: false,
            contentType: false,
            processData: false,
            success:function(status){
                if(status.msg=='success') {
                    toastr.success(status.response,"Success");
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else if(status.msg == 'error') {
                    btn.ladda('stop');
                    toastr.error(status.response,"Error");
                } else if(status.msg == 'lvl_error') {
                    btn.ladda('stop');
                    var message = "";
                    $.each(status.response, function (key, value) {
                        message += value+"<br>";
                    });
                    toastr.error(message, "Error");
                }
            }
        });
    });
</script>
@endpush