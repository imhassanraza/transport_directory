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
                <strong>Add Driver</strong>
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
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Driver Detail</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" id="add_form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                    <label class="col-sm-1 col-form-label">Photo</label>
                                    <div class="col-sm-4">
                                        <input type="file" name="picture" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no">
                                    </div>
                                    <label class="col-sm-1 col-form-label">Cnic</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="city" id="city">
                                            <option value="">Select City</option>
                                            @foreach(get_complete_table('cities', '', '', '', '') as $city)
                                            <option value="{{ $city->id }} ">
                                                {{ $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Guarantor</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="guarantor" id="guarantor">
                                    </div>
                                </div>

                                <hr>
                                <strong>Driver Routes</strong>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Cities</label>
                                    <div class="col-sm-9">
                                        <select class="select2_demo_2 form-control" name="cities[]" id="cities" multiple="multiple">
                                            @foreach(get_complete_table('cities', '', '', '', '') as $city)
                                            <option value="{{ $city->id }} ">
                                                {{ $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <strong>Driver Vehicles</strong>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Vehicle Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="vehicle_type" id="vehicle_type">
                                            <option value="">Select Vehicle Type</option>
                                            @foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
                                            <option value="{{ $type->id }} ">
                                                {{ $type->vehicle_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Vehicle No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="vehicle_number" id="vehicle_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Vehicle City</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="vehicle_city" id="vehicle_city">
                                            <option value="">Select City</option>
                                            @foreach(get_complete_table('cities', '', '', '', '') as $city)
                                            <option value="{{ $city->id }} ">
                                                {{ $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
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
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".select2_demo_2").select2({
            // theme: 'bootstrap4',
            placeholder: 'Select Cities'
        });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>
<script>
    $(document).on("click" , "#save_button" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_form")[0]);
        $.ajax({
            url:'{{ url('admin/city/store') }}',
            type: 'POST',
            data: formData,
            dataType:'json',
            cache: false,
            contentType: false,
            processData: false,
            success:function(status){
                if(status.msg=='success') {
                    toastr.success(status.response,"Success");
                    $('#add_form')[0].reset();
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else if(status.msg == 'error') {
                    btn.ladda('stop');
                    toastr.error(status.response,"Error");
                } else if(status.msg == 'lvl_error') {
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