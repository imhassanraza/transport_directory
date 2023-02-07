@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Drivers</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Drivers List
                </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/drivers/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Driver
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
                                    <th>Driver</th>
                                    <th>Phone No</th>
                                    <th>CNIC</th>
                                    <th>City</th>
                                    <th>Vehicle</th>
                                    <th>Date</th>
                                    <th>Is Thief</th>
                                    <th>Count Bilties</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($drivers as $driver)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ url('admin/drivers/show') }}/{{ $driver['id'] }}" class="text-info" target="_blank" data-placement="top" title="View Details">
                                            <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $driver['photo'] }}" style="width: 30px;"><br>
                                            {{ $driver['name'] }}
                                        </a>
                                    </td>
                                    <td>{{ $driver['phone_no'] }}</td>
                                    <td>{{ $driver['cnic'] }}</td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{$driver->city_id}}" target="_blank" class="text-info">
                                            {{ get_single_value('cities', 'name', $driver['city_id']) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(!empty($driver['vehicle_id']))
                                        <?php $vehicle_id = get_active_vehicle($driver['vehicle_id']); ?>
                                        @if(!empty($vehicle_id))
                                        <a href="{{ url('admin/drivers/vehicle_detail') }}/{{$vehicle_id}}" target="_blank" class="text-info">
                                            {{ get_single_value('vehicles', 'vehicle_number', $vehicle_id) }}
                                        </a>
                                        @endif
                                        @endif
                                    </td>
                                    <td>{{ date_formated($driver['created_at']) }}</td>
                                    <td>
                                        @if($driver['is_thief'] == '2')
                                        <label class="label label-danger">Yes</label>
                                        @else
                                        <label class="label label-primary">No</label>
                                        @endif
                                    </td>
                                    <td>{{ number_format( count_existing_record('bilties', 'driver_id', $driver['id']) ); }}</td>
                                    <td>{{ get_single_value('admin_users', 'username', $driver->created_by) }}</td>
                                    <td>
                                        @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $driver->created_by))
                                        <a href="{{ url('admin/drivers/edit') }}/{{ $driver['id'] }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{ $driver['id'] }}" type="button" data-placement="top" title="Delete"> Delete </button>
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
            text: "You want to delete this driver!",
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
                    url:'{{ url('admin/drivers/delete') }}',
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