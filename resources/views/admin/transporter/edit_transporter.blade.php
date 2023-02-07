@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
<style type="text/css">
    .select2{
        width: 100% !important;
    }
    .select2-container{
        z-index: 999999 !important;

    }
</style>
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Transporters </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Transporter </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/transporters') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Transporters
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="show_driver_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Transporter Detail</a></li>
                    <li class="show_route_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Transporter Vehicles</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active show" role="tabpanel">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Transporter Detail</h5>
                            </div>
                            <div class="ibox-content">
                                <form method="post" id="edit_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" class="form-control" value="{{ $transporter->id }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Transporter Name</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="transporter_name" id="transporter_name" value="{{ $transporter->transporter_name }}">
                                                </div>
                                                <label class="col-sm-1 col-form-label">City</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control select2_demo_3" name="city" id="city">
                                                        <option value="">Select City</option>
                                                        @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $city)
                                                        <option value="{{ $city->id }}" @if($city->id == $transporter->city_id) selected @endif>
                                                            {{ $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Phone No</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ $transporter->phone_no }}">
                                                    <input type="hidden" class="form-control only_number" name="total_vehicle" id="total_vehicle" value="{{ $transporter->total_vehicle }}">
                                                </div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-4 offset-2">
                                                    <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/transporters') }}">Cancel</button>
                                                    <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_update" data-style="expand-right">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane" role="tabpanel">

                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Transporter Vehicles</h5>
                                <div class="pull-right" style="top: 8px;">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modalbox" data-placement="top" title="Add New Vehicle"> <i class="fa fa-plus" aria-hidden="true"></i> Add New Vehicle</button>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Vehicle Number</th>
                                                <th>Vehicle Type</th>
                                                <th>Vehicle City</th>
                                                <th>Action</th>
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
                                                <td>{{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }}</td>
                                                <td>
                                                    <a href="{{ url('admin/city/show') }}/{{$vehicle->vehicle_city}}" target="_blank" class="text-info">
                                                        {{ get_single_value('cities', 'name', $vehicle->vehicle_city) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $vehicle->created_by))
                                                    <button type="button" class="btn btn-success btn-sm btn_edit_vehicles" data-id="{{ $vehicle->id }}" data-placement="top" title="Edit"> Edit </button>
                                                    <button class="btn btn-danger btn-sm btn_delete_vehicle" data-num="{{ $vehicle->vehicle_number }}" data-id="{{ $vehicle->id }}" data-transporter-id="{{ $vehicle->transporter_id }}" type="button" data-placement="top" title="Delete"> Delete </button>
                                                    @endif
                                                </td>
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
<div class="modal inmodal show fade" id="add_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dm" role="document">
        <div class="modal-content animated flipInY" id="add_modalbox_body">
            <div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title"><strong class="text-navy">{{ $transporter->transporter_name }}</strong> Vehicles</h5>
                </div>
                <div class="modal-body">
                    <form method="post" id="add_vehicle_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="transporter_id" class="form-control" value="{{ $transporter->id }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Vehicle Type</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="vehicle_type" id="vehicle_type">
                                            <option value="">Select Vehicle Type</option>
                                            @foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->vehicle_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Vehicle City</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2_demo_3" name="vehicle_city" id="vehicle_city">
                                            <option value="">Select City</option>
                                            @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $vcity)
                                            <option value="{{ $vcity->id }}">
                                                {{ $vcity->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Vehicle No</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="vehicle_number" id="vehicle_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="ladda-button btn btn-primary btn-sm" id="btn_add_vehicle" data-style="expand-right">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal show fade" id="edit_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dm" role="document">
        <div class="modal-content animated flipInY" id="edit_modalbox_body">
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
            allowClear: true,
            dropdownParent: $('#add_modalbox')
        });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    $(document).on("click" , "#btn_update" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#edit_form")[0]);
        $.ajax({
            url:'{{ url('admin/transporters/update') }}',
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

    $('.table_tbl5').dataTable({
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

    $(document).on("click" , ".btn_delete_vehicle" , function(){
        var id = $(this).attr('data-id');
        var vehicle_num = $(this).attr('data-num');
        var transporter_id = $(this).attr('data-transporter-id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this vehicle!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, please!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $(".confirm").prop("disabled", true);
                $.ajax({
                    url:'{{ url('admin/transporters/delete_vehicle') }}',
                    type:'post',
                    data:{"_token": "{{ csrf_token() }}", 'id': id, 'vehicle_num': vehicle_num, 'transporter_id': transporter_id},
                    dataType:'json',
                    success:function(status){
                        $(".confirm").prop("disabled", false);
                        if(status.msg == 'success'){
                            swal({title: "Success!", text: status.response, type: "success"},
                                function(data){
                                    location.reload();
                                });
                        } else if(status.msg=='error'){
                            swal("Error", status.response, "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "", "error");
            }
        });
    });

    $(document).on("click" , ".btn_edit_vehicles" , function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url:'{{ url('admin/transporters/edit_vehicle') }}',
            type: 'POST',
            dataType:'json',
            data: {"_token": "{{ csrf_token() }}", 'id': id},
            success:function(status){
                $("#edit_modalbox_body").html(status.response);
                $("#edit_modalbox").modal('show');
                $(".select2_demo_4").select2({
                    placeholder: 'Select City',
                    allowClear: true,
                    dropdownParent: $('#edit_modalbox')
                });
            }
        });
    });

    $(document).on("click" , "#btn_update_vehicle" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#edit_vehicle_form")[0]);
        $.ajax({
            url:'{{ url('admin/transporters/update_vehicle') }}',
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

    $(document).on("click" , "#btn_add_vehicle" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_vehicle_form")[0]);
        $.ajax({
            url:'{{ url('admin/transporters/add_vehicle') }}',
            type: 'POST',
            data: formData,
            dataType:'json',
            cache: false,
            contentType: false,
            processData: false,
            success:function(status){
                if(status.msg=='success') {
                    toastr.success(status.response,"Success");
                    $('#add_vehicle_form')[0].reset();
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