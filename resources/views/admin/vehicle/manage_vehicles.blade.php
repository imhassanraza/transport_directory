@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Vehicles</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Vehicles List
                </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/vehicles/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Vehicle
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="manage_tbl" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Owner Name</th>
                                    <th>Phone No</th>
                                    <th>City</th>
                                    <th>Vehicle Number</th>
                                    <th>Vehicle Type</th>
                                    <th>Vehicle City</th>
                                    <th>Date</th>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <th>Added By</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($vehicles as $vehicle)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ url('admin/transporters/show') }}/{{$vehicle->id}}" target="_blank" class="text-info">
                                            {{ $vehicle->transporter_name }}
                                        </a>
                                    </td>
                                    <td>{{ $vehicle->phone_no }}</td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{$vehicle->city_id}}" target="_blank" class="text-info">
                                            {{ $vehicle->city_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/drivers/vehicle_detail') }}/{{$vehicle->vehicle_id}}" target="_blank" class="text-info">
                                            {{ $vehicle->vehicle_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }}
                                    </td>
                                    <td>
                                        {{ get_single_value('cities', 'name', $vehicle->vehicle_city) }}
                                    </td>
                                    <td>{{ date_formated($vehicle->created_at) }}</td>

                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <td>{{ get_single_value('admin_users', 'username', $vehicle->created_by) }}</td>
                                    @endif
                                    <td>
                                        @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $vehicle->created_by))
                                        <a href="{{ url('admin/vehicles/edit') }}/{{ $vehicle->id }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-vehicle-id="{{ $vehicle->vehicle_id }}" data-id="{{ $vehicle->id }}" type="button" data-placement="top" title="Delete"> Delete </button>
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
        "lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 },
            { "responsivePriority": 2, "targets": -1 },
            ]
    });
    $(document).on("click" , ".btn_delete" , function(){
        var id = $(this).attr('data-id');
        var vehicle_id = $(this).attr('data-vehicle-id');
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
                    url:'{{ url('admin/vehicles/delete') }}',
                    type:'post',
                    data:{"_token": "{{ csrf_token() }}", 'id': id, 'vehicle_id': vehicle_id},
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