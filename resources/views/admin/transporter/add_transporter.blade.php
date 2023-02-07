@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
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
                <strong>Add Transporter </strong>
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
            <form method="post" id="add_form" enctype="multipart/form-data">
                @csrf
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Transporter Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Transporter Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="transporter_name" id="transporter_name">
                                    </div>
                                    <label class="col-sm-1 col-form-label">City</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2_demo_3" name="city" id="city">
                                            <option value="">Select City</option>
                                            @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $city)
                                            <option value="{{ $city->id }} ">
                                                {{ $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no">
                                        <input type="hidden" class="form-control only_number" name="total_vehicle" id="total_vehicle" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Vehicle Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="table_lists_tbl" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <th>Vehicle Type</th>
                                            <th>Vehicle City</th>
                                            <th>Vehicle Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </tbody>
                                    <tbody id="vehicles_list">
                                        <tr id="tr_0">
                                            <td>
                                                <select class="form-control" name="vehicles[0][vehicle_type]" id="vehicle_type_0" style=" width: 100% !important;">
                                                    <option value="">Select Vehicle Type</option>
                                                    @foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->vehicle_type }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2_demo_3" name="vehicles[0][vehicle_city]" id="vehicle_city_0" style=" width: 100% !important;">
                                                    <option value="">Select City</option>
                                                    @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $vcity)
                                                    <option value="{{ $vcity->id }}">
                                                        {{ $vcity->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="vehicles[0][vehicle_number]" id="vehicle_number_0" style=" width: 100% !important;">
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-circle" onclick="add_more_rows();" type="button"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <div class="col-sm-4 offset-2">
                                        <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/transporters') }}">Cancel</button>
                                        <button type="button" class="ladda-button btn btn-primary btn-sm" id="save_btn" data-style="expand-right">Submit</button>
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
    var row_number = 0;
    function add_more_rows() {
        row_number++;
        html = '<tr class="new_added_rows" id="tr_'+row_number+'"><td><select class="form-control" id="vehicle_type_'+row_number+'" name="vehicles['+row_number+'][vehicle_type]" style=" width: 100% !important;"><option value="">Select Vehicle Type</option>';
        @foreach(get_complete_table('vehicle_types', '', '', '', '') as $type)
        html += '<option value="{{ $type->id }}">{{ $type->vehicle_type }}</option>';
        @endforeach
        html += '</select>';
        html += '</td>';
        html += '<td>';
        html += '<select class="form-control select2_demo_444" id="vehicle_city_'+row_number+'" name="vehicles['+row_number+'][vehicle_city]" style=" width: 100% !important;"><option value="">Select City</option>';
        @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $vcity)
        html += '<option value="{{ $vcity->id }}">{{ $vcity->name }}</option>';
        @endforeach
        html += '</select>';
        html += '</td>';
        html += '<td>';
        html += '<input type="text" id="vehicle_number_'+row_number+'" name="vehicles['+row_number+'][vehicle_number]" class="form-control" style=" width: 100% !important;">';
        html += '</td>';
        html += '<td>';
        html += '<button class="btn btn-danger btn-circle remove_added_rows" type="button"><i class="fa fa-minus-circle"></i></button>';
        html += '</td>';
        html += '</tr>';
        jQuery('#vehicles_list').append(html);
        vehicle_count();
        $(".select2_demo_444").select2({
            placeholder: 'Select City',
            allowClear: true
        });
    }

    $(document).on("click" , ".remove_added_rows" , function() {
        $(this).closest('.new_added_rows').fadeIn(function() { $(this).remove(); });
        vehicle_count();
    });

    function vehicle_count(){
        if($(".remove_added_rows").length > 0){
            var total = 1;
            $(".remove_added_rows").each(function(){
                total++;
            });
        }
        if(total > 0){
            $("#total_vehicle").val(total);
        }else{
            $("#total_vehicle").val('1');
        }
    }


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

    $(document).on("click" , "#save_btn" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_form")[0]);
        $.ajax({
            url:'{{ url('admin/transporters/store') }}',
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