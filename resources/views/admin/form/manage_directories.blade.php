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
        <h2>Directories</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Directories List</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <button type="button" class="btn btn-primary t_m_25" data-toggle="modal" data-target="#add_modalbox" data-placement="top" title="Add New Directory"> <i class="fa fa-plus" aria-hidden="true"></i> Add New Directory</button>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="table_tbl" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Directories</th>
                                    <th>Directory Type</th>
                                    <th>Phone No</th>
                                    <th>City</th>
                                    <td>Added By</td>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($directories as $dir)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $dir->name }}</td>
                                    <td>{{ $dir->form_name }}</td>
                                    <td>{{ $dir->phone_no }}</td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{$dir->city_id}}" target="_blank" class="text-info">
                                            {{ $dir->city_name }}
                                        </a>
                                    </td>
                                    <td>{{ get_single_value('admin_users', 'username', $dir->created_by) }}</td>
                                    <td>
                                         @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $dir->created_by))
                                        <button type="button" class="btn btn-primary btn-sm btn_edit" data-id="{{ $dir->id }}" data-placement="top" title="Edit"> Edit </button>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{ $dir->id }}" type="button" data-placement="top" title="Delete"> Delete </button>
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
<div class="modal inmodal show fade" id="add_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Add New Directory</h5>
            </div>
            <div class="modal-body">
                <form id="add_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><strong>Select Directory</strong></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="form" id="form">
                                <option value="">Select Directory Type</option>
                                @foreach(get_complete_table('forms', '', '', 'name', 'asc') as $form)
                                <option value="{{ $form->id }} ">
                                    {{ $form->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><strong>Name</strong></label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control input-sm" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><strong>Phone No</strong></label>
                        <div class="col-sm-8">
                            <input type="text" name="phone_no" class="form-control input-sm" placeholder="Phone No">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><strong>City</strong></label>
                        <div class="col-sm-8">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_button"> Submit </button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal show fade" id="edit_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content animated flipInY" id="edit_modalbox_body">
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(".select2_demo_3").select2({
        placeholder: 'Select City',
        allowClear: true,
        dropdownParent: $('#add_modalbox')
    });
    // $(".select2_demo_4").select2({
    //     placeholder: 'Select City',
    //     allowClear: true,
    //     dropdownParent: $('#edit_modalbox')
    // });

    $('#table_tbl').dataTable({
        "paging": true,
        "searching": true,
        "bInfo":true,
        "responsive": true,
        "lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
        "columnDefs": [
        { "responsivePriority": 1, "targets": 0 },
        { "responsivePriority": 2, "targets": -1 },
        { "responsivePriority": 3, "targets": -2 },
        ]
    });
    $(document).on("click" , "#save_button" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#add_form")[0]);
        $.ajax({
            url:'{{ url('admin/directories/store') }}',
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
    $(document).on("click" , ".btn_edit" , function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url:'{{ url('admin/directories/edit') }}',
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
    $(document).on("click" , "#update_button" , function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData =  new FormData($("#edit_form")[0]);
        $.ajax({
            url:'{{ url('admin/directories/update') }}',
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
    $(document).on("click" , ".btn_delete" , function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this directory!",
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
                    url:'{{ url('admin/directories/delete') }}',
                    type:'post',
                    data:{"_token": "{{ csrf_token() }}", 'id': id},
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
</script>
@endpush