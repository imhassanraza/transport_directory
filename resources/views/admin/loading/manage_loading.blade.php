@extends('admin.admin_app')
@push('styles')
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
                <strong>Bilties List
                </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/bilty/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Bilty
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
                                    <th>Bilty Number</th>
                                    <th>Bilty Image</th>
                                    <th>Drvier Image</th>
                                    <th>Driver</th>
                                    <th>Vehicle Number</th>
                                    <th>Vehicle Type</th>
                                    <th>Source City</th>
                                    <th>Distination City</th>
                                    <th>Guarantor</th>
                                    <th>Bilty Detail</th>
                                    <th>Bilty Date</th>
                                    <th>Insurance</th>
                                    <th>Status</th>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <th>Added By</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($loadings as $loading)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ $loading->bilty_number }}
                                    </td>
                                    <td>
                                        <a href="{{ asset('assets/img') }}/{{ $loading->bilty_image }}" class="text-info" target="_blank" data-placement="top" title="View Full Image">
                                            <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $loading->bilty_image }}" alt="" style="width: 65px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ asset('assets/img') }}/{{ $loading->driver_image }}" class="text-info" target="_blank" data-placement="top" title="View Full Image">
                                            <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $loading->driver_image }}" alt="" style="width: 65px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/drivers/show') }}/{{ $loading->driver_id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                            <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $loading->photo }}" style="width: 30px;"><br>
                                            {{ $loading->driver_name }}<br>
                                            {{ $loading->phone_no }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/drivers/vehicle_detail') }}/{{ $loading->vehicle_id }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                            {{ $loading->vehicle_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ get_single_value('vehicle_types', 'vehicle_type', $loading->vehicle_type) }}
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{ $loading->source_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                            {{ get_single_value('cities', 'name', $loading->source_city) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{ $loading->destination_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                            {{ get_single_value('cities', 'name', $loading->destination_city) }}
                                        </a>
                                    </td>
                                    <td>{{ $loading->guarantor_detail }}</td>
                                    <td>
                                        <?php echo wordwrap($loading->bilty_details, 180, "<br>\n"); ?>
                                    </td>

                                    <td>{{ date_formated($loading->bilty_date) }}</td>
                                    <td>
                                        @if($loading->bilty_insurance == '2')
                                        <label class="label label-primary"> Insured </label>
                                        @else
                                        <label class="label label-danger"> Not Insured </label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loading->status == '1')
                                        <label class="label label-success">Processing</label>
                                        @else
                                        <label class="label label-primary">Delivered</label>
                                        @endif
                                    </td>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <td>{{ get_single_value('admin_users', 'username', $loading->created_by) }}</td>
                                    @endif
                                    <td>
                                        @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $loading->created_by))
                                        @if($loading->status == '1')
                                        <button class="btn btn-success btn-sm btn_delivered" data-id="{{ $loading->id }}" type="button" data-placement="top" title="Delivered"> Delivered </button>
                                        <a href="{{ url('admin/bilty/edit') }}/{{ $loading->id }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{ $loading->id }}" type="button" data-placement="top" title="Delete"> Delete </button>
                                        @endif
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
            { "responsivePriority": 3, "targets": -2 },
            { "responsivePriority": 4, "targets": -3 },
            ]
    });

    $(document).on("click" , ".btn_delete" , function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this bilty!",
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
                    url:'{{ url('admin/bilty/delete') }}',
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

    $(document).on("click" , ".btn_delivered" , function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You want to mark delivered this bilty!",
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
                    url:'{{ url('admin/bilty/delivered_bilties') }}',
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