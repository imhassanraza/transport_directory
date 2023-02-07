@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2> Users </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add User </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/users') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Users
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>User Detail</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" id="add_form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Name</strong>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Phone No</strong>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="phone_no" placeholder="Phone Number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Password</strong>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="password" placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Permissions</strong>
                            <div class="col-sm-7">
                                <label class="checkbox-inline i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="cities"> Cities
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="vehicle_types"> Vehicle Types
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="vehicles"> Vehicles
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="transporters"> Transporters
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="drivers"> Drivers
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="bilties"> Bilties
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="cold_storages"> Cold Storages
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="directory_types"> Directory Types
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" value="directories"> Directories
                                </label>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Permissions</strong>
                            <div class="col-sm-7">
                                <input class="i-checks" type="checkbox" name="view_all_data"> Check if this user view all application data
                            </div>
                        </div> --}}

                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-7 offset-3">
                                <button type="button" class="btn btn-white" id="cancel_btn" data-url="{{ url('admin/users') }}">Cancel</button>
                                @if(check_permissions('only_admin'))
                                <button type="button" class="ladda-button btn btn-primary" id="save_btn" data-style="expand-right">Submit</button>
                                @endif
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
<script>
    $(document).ready(function () {
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
            url:"{{ url('admin/store') }}",
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
                        location.reload(true);
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
                    btn.ladda('stop');
                    toastr.error(message, "Error");
                }
            }
        });
    });
</script>
@endpush