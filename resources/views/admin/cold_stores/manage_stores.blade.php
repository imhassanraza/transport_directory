@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Cold Storages</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Cold Storages List
                </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/cold-storage/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Cold Store
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="drivers_tbl" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Store Name</th>
                                    <th>City</th>
                                    <th>Owner Name</th>
                                    <th>Manager Name</th>
                                    <th>Address</th>
                                    <th>Date</th>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <th>Added By</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($stores as $store)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $store['store_name'] }}</td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{$store->city_id}}" target="_blank" class="text-info">
                                            {{ get_single_value('cities', 'name', $store['city_id']) }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $store['owner_name'] }}
                                        <br>
                                        {{ $store['owner_phone'] }}
                                    </td>
                                    <td>
                                        {{ $store['manager_name'] }}
                                        <br>
                                        {{ $store['manager_phone'] }}
                                    </td>
                                    <td>{{ $store['address'] }}</td>
                                    <td>{{ date_formated($store['created_at']) }}</td>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <td>{{ get_single_value('admin_users', 'username', $store->created_by) }}</td>
                                    @endif
                                    <td>
                                        @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $store->created_by))
                                        <a href="{{ url('admin/cold-storage/edit') }}/{{ $store['id'] }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{ $store['id'] }}" type="button" data-placement="top" title="Delete"> Delete </button>
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
    $('#drivers_tbl').dataTable({
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
    $(document).on("click" , ".btn_delete" , function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this cold store!",
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
                    url:'{{ url('admin/cold-storage/delete') }}',
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