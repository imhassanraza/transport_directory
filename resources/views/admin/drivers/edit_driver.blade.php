@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Driver</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Driver</strong>
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
                    <li class="show_vehicle_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Driver Detail</a></li>
                    <li class="show_route_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Driver Routes</a></li>
                    <li class="show_vehicle_tab"><a class="nav-link" data-toggle="tab" href="#tab-3">Driver Vehicles</a></li>
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
                                                <form method="post" id="driver_form" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="driver_id" class="form-control" value="{{ $driver->id; }}">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Name</label>
                                                                <div class="col-sm-4">
                                                                    <input type="text" name="name" id="name" class="form-control" value="{{ $driver->name; }}">
                                                                </div>
                                                                <label class="col-sm-1 col-form-label">Photo</label>
                                                                <div class="col-sm-4">
                                                                    <input type="file" name="picture" class="form-control" accept="image/*">
                                                                    <input type="hidden" name="old_pic" class="form-control" value="{{ $driver->photo; }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Phone No</label>
                                                                <div class="col-sm-4">
                                                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ $driver->phone_no; }}">
                                                                </div>
                                                                <label class="col-sm-1 col-form-label">Cnic</label>
                                                                <div class="col-sm-4">
                                                                    <input type="text" class="form-control" name="cnic" id="cnic" value="{{ $driver->cnic; }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">City</label>
                                                                <div class="col-sm-4">
                                                                    <select class="form-control select2_demo_3" name="city" id="city">
                                                                        <option value="">Select City</option>
                                                                        @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $city)
                                                                        <option value="{{ $city->id }}" @if($city->id == $driver->city_id) selected @endif>
                                                                            {{ $city->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <label class="col-sm-1 col-form-label">Thief</label>
                                                                <div class="col-sm-4 col-form-label">
                                                                    <input class="i-checks" type="checkbox" name="is_thief" @if($driver->is_thief == 2) checked @endif> Check if this user is thief
                                                                </div>
                                                            </div>
                                                            <div class="hr-line-dashed"></div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-4 offset-2">
                                                                    <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/drivers') }}">Cancel</button>
                                                                    <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update_driver" data-style="expand-right"> Save Changes </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
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
                                <form method="post" id="driver_route_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="driver_id" class="form-control" value="{{ $driver->id; }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Select Routes</label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $select_value = [];
                                                    foreach ($cities as $added_city) {
                                                        $select_value[] = $added_city->city_id;
                                                    } ?>
                                                    <select class="select2_demo_2 form-control" name="cities[]" id="cities" multiple="multiple">
                                                        @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $mcity)
                                                        <option value="{{ $mcity->id }}" @if(in_array($mcity->id, $select_value)) selected @endif>
                                                            {{ $mcity->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-4 offset-2">
                                                    <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/drivers') }}">Cancel</button>
                                                    <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update_driver_route" data-style="expand-right"> Save Changes </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="tab-3" class="tab-pane" role=tabpanel>
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Driver Vehicles</h5>
                            </div>
                            <div class="ibox-content">
                                <form method="post" id="driver_vehicle_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="driver_id" class="form-control" value="{{ $driver->id; }}">
                                    <input type="hidden" name="vehicle_id" class="form-control" value="{{ @$vehicle->id; }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Select Vehicle</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control select2_demo_4" name="vehicle_number">
                                                        <option value="">Select Vehicle</option>
                                                        @foreach(get_vehicles() as $vehicle)
                                                        <option value="{{ $vehicle->id }}" @if($vehicle->id == @$dvehicle->vehicle_id) selected @endif>
                                                            <strong>Transporter: </strong> {{ $vehicle->transporter_name }} {{ $vehicle->phone_no }} - <strong> Vehicle Type: </strong> {{ $vehicle->vehicle_type }} - <strong> Vehicle Number: </strong> {{ $vehicle->vehicle_number }} - <strong> Vehicle City: </strong> {{ $vehicle->city_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-4 offset-2">
                                                    <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/drivers') }}">Cancel</button>
                                                    <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update_driver_vehicle" data-style="expand-right"> Save Changes </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>

    $(".select2_demo_2").select2({
        placeholder: 'Select Routes'
    });
    $(".select2_demo_3").select2({
        placeholder: 'Select City',
        allowClear: true
    });
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $(document).on('click', '.show_route_tab', function() {
        $(".select2_demo_2").select2({
            placeholder: 'Select Cities'
        });
    });
    $(document).on('click', '.show_vehicle_tab', function() {
        $(".select2_demo_3").select2({
            placeholder: 'Select City',
            allowClear: true
        });
    });
</script>
<script>
    $(document).on("click" , "#btn_update_driver" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#driver_form")[0]);
        $.ajax({
            url:'{{ url('admin/drivers/update') }}',
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

    $(document).on("click" , "#btn_update_driver_route" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#driver_route_form")[0]);
        $.ajax({
            url:'{{ url('admin/drivers/update_routes') }}',
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
    $(document).on("click" , "#btn_update_driver_vehicle" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#driver_vehicle_form")[0]);
        $.ajax({
            url:'{{ url('admin/drivers/update_vehicles') }}',
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