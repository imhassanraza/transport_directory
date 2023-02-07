@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Cold Storages </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add Cold Store </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/cold-storage') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Cold Storages
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
                        <h5>Cold Storage Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Store Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="store_name" id="store_name" class="form-control">
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
                                    <label class="col-sm-2 col-form-label">Owner Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="owner_name" id="owner_name">
                                    </div>
                                    <label class="col-sm-1 col-form-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="owner_phone" id="owner_phone">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Manager Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="manager_name" id="manager_name">
                                    </div>
                                    <label class="col-sm-1 col-form-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="manager_phone" id="manager_phone">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <div class="col-sm-4 offset-2">
                                        <button type="button" class="btn btn-white btn-sm" id="cancel_btn" data-url="{{ url('admin/cold-storage') }}">Cancel</button>
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
    $(document).ready(function() {
        $(".select2_demo_2").select2({
            placeholder: 'Select Cities'
        });
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
    $(document).on("click" , "#save_btn" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_form")[0]);
        $.ajax({
            url:'{{ url('admin/cold-storage/store') }}',
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