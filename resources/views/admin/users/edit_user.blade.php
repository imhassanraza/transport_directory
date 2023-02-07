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
                <strong>Edit User </strong>
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
                    <form method="post" id="edit_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" name="id" value="{{$user->id}}">
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Name</strong>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="name" value="{{$user->username}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Phone No</strong>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="phone_no" value="{{$user->phone_no}}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Password</strong>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="password" placeholder="Note: Leave blank if you don't want to update.">
                            </div>
                        </div>

                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Permissions</strong>
                            <div class="col-sm-7">
                                @php
                                $actions = explode(',', $user->permissions);
                                @endphp
                                <label class="checkbox-inline i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('cities', $actions)) checked @endif value="cities"> Cities
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('vehicle_types', $actions)) checked @endif value="vehicle_types"> Vehicle Types
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('vehicles', $actions)) checked @endif value="vehicles"> Vehicles
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('transporters', $actions)) checked @endif value="transporters"> Transporters
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('drivers', $actions)) checked @endif value="drivers"> Drivers
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('bilties', $actions)) checked @endif value="bilties"> Bilties
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('cold_storages', $actions)) checked @endif value="cold_storages"> Cold Storages
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('directory_types', $actions)) checked @endif value="directory_types"> Directory Types
                                </label>
                                <label class="i-checks col-sm-3">
                                    <input type="checkbox" name="actions[]" @if(in_array('directories', $actions)) checked @endif value="directories"> Directories
                                </label>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label">Permissions</strong>
                            <div class="col-sm-7">
                                <input class="i-checks" type="checkbox" name="view_all_data" @if($user->view_all_data == 2) checked @endif> Check if this user view all application data
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <strong class="col-sm-1 offset-lg-2 col-form-label"> Is active </strong>
                            <div class="col-sm-7">
                                <input class="i-checks" type="checkbox" name="status" @if($user->status == 1) checked @endif> Check if active
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-7 offset-3">
                                <button type="button" class="btn btn-white" id="cancel_btn" data-url="{{ url('admin/users') }}">Cancel</button>
                                @if(check_permissions('only_admin'))
                                <button type="button" class="ladda-button btn btn-primary" id="btn_update" data-style="expand-right">Save Changes</button>
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

    $(document).on("click" , "#btn_update" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#edit_form")[0]);
        $.ajax({
            url:"{{ url('admin/update') }}",
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
                        window.location.href = "{{ url('admin/users') }}";
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