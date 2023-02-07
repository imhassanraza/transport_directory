@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Bilties</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add Bilty</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/bilty') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Bilties
        </a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <form method="post" id="add_form" enctype="multipart/form-data">
        @csrf
        <div class="ibox">
            <div class="ibox-title">
                <h5>Bilty Detail</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Driver Image</label>
                            <div class="col-sm-9">
                                <input type="file" name="driver_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bilty Image</label>
                            <div class="col-sm-9">
                                <input type="file" name="bilty_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bilty Number</label>
                            <div class="col-sm-9">
                                <input type="text" name="bilty_number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Select Vehicle</label>
                            <div class="col-sm-9">
                                <select class="form-control select2_demo_4" name="vehicle" id="vehicle_id">
                                    <option value="">Select Vehicle</option>
                                    @foreach(get_drivers_vehicles() as $vehicle)
                                    <option value="{{ $vehicle->vehicle_id }}" data-id="{{ $vehicle->driver_id }}">
                                        Vehicle Number: {{ $vehicle->vehicle_number }} -
                                        Vehicle Type: {{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }} -
                                        Driver: {{ get_single_value('drivers', 'name', $vehicle->driver_id) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Source City</label>
                            <div class="col-sm-9">
                                <select class="form-control select2_demo_3" name="source_city">
                                    <option value="">Select City</option>
                                    @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $scity)
                                    <option value="{{ $scity->id }}">
                                        {{ $scity->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Destination City</label>
                            <div class="col-sm-9">
                                <select class="form-control select2_demo_3" name="destination_city">
                                    <option value="">Select City</option>
                                    @foreach(get_complete_table('cities', '', '', 'name', 'asc') as $dcity)
                                    <option value="{{ $dcity->id }}">
                                        {{ $dcity->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bilty Date</label>
                            <div class="col-sm-9" id="data_3">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="bilty_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Guarantor</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" name="guarantor_detail" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bilty Details</label>
                            <div class="col-sm-9">
                                <textarea name="bilty_details" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bilty Insurance</label>
                            <div class="col-sm-9">
                                <input class="i-checks" type="checkbox" name="bilty_insurance"> Check if this bilty is insured
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="contact-box">
                            <a class="row" href="javascript:void(0);">
                                <div class="col-4">
                                    <div class="text-center add_remove_thief_cls">
                                        <img class="rounded-circle m-t-xs img-fluid" src="" style="width: 100px;" id="driver_pic">
                                        <input type="hidden" class="form-control" name="driver" id="driver_id">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h3><strong>Driver Information</strong></h3>
                                    <address>
                                        <abbr>Name: </abbr> <strong id="lbl_name"></strong><br>
                                        <abbr>Phone: </abbr> <strong id="lbl_phone"></strong><br>
                                        <abbr>City: </abbr> <strong id="lbl_city"></strong><br>
                                        <abbr>Is Thief: </abbr> <strong id="lbl_thief"></strong>
                                    </address>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-9 offset-3">
                                <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/bilty') }}">Cancel</button>
                                <button type="button" class="ladda-button btn btn-primary btn-sm" id="save_btn" data-style="expand-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(".select2_demo_4").select2({
        placeholder: 'Select Vehicle',
        allowClear: true
    });
    $(".select2_demo_3").select2({
        placeholder: 'Select Destination City',
        allowClear: true
    });
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('#data_3 .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    $(document).on("change" , "#vehicle_id" , function() {
        var id = $(this).val();
        var driver_id = $(this).find(':selected').attr('data-id');
        if(id){
            $.ajax({
                url:'{{ url('admin/bilty/get_driver') }}',
                type: 'POST',
                data:{"_token": "{{ csrf_token() }}", "id": id, "driver_id": driver_id},
                dataType:'json',
                cache: false,
                success:function(status){
                    if(status.msg=='success'){
                        $("#driver_id").val(status.response.id);
                        $("#lbl_name").html(status.response.name);
                        $("#lbl_phone").html(status.response.phone_no);
                        $("#lbl_city").html(status.response.city);
                        $("#lbl_thief").html(status.response.is_thief);
                        if(status.response.is_thief== '2'){
                            $("#lbl_thief").html("Yes");
                            $(".add_remove_thief_cls").addClass('thief_cls');
                        } else {
                            $(".add_remove_thief_cls").removeClass('thief_cls');
                            $("#lbl_thief").html("No");
                        }
                        $('#driver_pic').attr('src', '{{ asset('assets/img') }}/'+status.response.photo);
                    }
                }
            });
        }else{
            $("#driver_id").val('');
            $("#lbl_name").html('');
            $("#lbl_phone").html('');
            $("#lbl_city").html('');
            $("#lbl_thief").html('');
            $(".add_remove_thief_cls").removeClass('thief_cls');
            $('#driver_pic').attr('src', '');
        }
    });
</script>
<script>
    $(document).on("click" , "#save_btn" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_form")[0]);
        $.ajax({
            url:'{{ url('admin/bilty/store') }}',
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