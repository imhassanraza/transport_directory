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
                <strong> Users </strong>
            </li>
        </ol>
    </div>
    @if(check_permissions('only_admin'))
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New User
        </a>
    </div>
    @endif
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="manage_tbl" class="table table-striped table-bordered dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Phone No</th>
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Login Attempts</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($users as $user)
                                <tr  class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->phone_no}}</td>
                                    <td>
                                        @foreach(explode(',', $user->permissions) as $permission)
                                        <label class="label label-primary">{{ $permission }}</label>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($user->status==1)
                                        <label class="label label-primary"> Active </label>
                                        @else
                                        <label class="label label-danger"> Inactive </label>
                                        @endif
                                    </td>
                                    <td>{{date_formated($user->created_at)}}</td>
                                    <td>{{ number_format( count_existing_record('login_logs', 'user_id', $user->id) ); }}</td>
                                    <td>
                                        @if(check_permissions('only_admin'))
                                        <a href="{{ url('admin/edit') }}/{{ $user->id }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{$user->id}}" type="button" data-placement="top" title="Delete"> Delete </button>
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
@endsection
@push('scripts')
<script>
    $('#manage_tbl').dataTable({
        "paging": true,
        "searching": true,
        "bInfo":true,
        "responsive": true,
        "lengthMenu": [ [25, 50, 100, -1], [25, 100, "All"] ],
        "columnDefs": [
        { "responsivePriority": 1, "targets": 0 },
        { "responsivePriority": 2, "targets": -1 },
        ]
    });

    $(document).on("click" , ".btn_delete" , function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this user!",
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
                    url:"{{ url('admin/delete') }}",
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