@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Transporters</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Transporters List
                </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/transporters/create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Transporter
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
                                    <th>Transporter Name</th>
                                    <th>Phone No</th>
                                    <th>City</th>
                                    <th>Vehicle Qty</th>
                                    <th>Date</th>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <th>Added By</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($transporters as $transport)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ url('admin/transporters/show') }}/{{$transport->id}}" target="_blank" class="text-info">
                                            {{ $transport->transporter_name }}
                                        </a>
                                    </td>
                                    <td>{{ $transport->phone_no }}</td>
                                    <td>
                                        <a href="{{ url('admin/city/show') }}/{{$transport->city_id}}" target="_blank" class="text-info">
                                            {{ $transport->city_name }}
                                        </a>
                                    </td>
                                    <td>{{ $transport->total_vehicle }}</td>
                                    <td>{{ date_formated($transport->created_at) }}</td>
                                    @if(Auth::guard('admin')->user()->type == '0')
                                    <td>{{ get_single_value('admin_users', 'username', $transport->created_by) }}</td>
                                    @endif
                                    <td>
                                        @if((Auth::guard('admin')->user()->type == '0') || (Auth::guard('admin')->user()->id == $transport->created_by))
                                        <button type="button" class="btn btn-success btn-sm btn_view_vehicles" data-id="{{ $transport->id }}" data-placement="top" title="View Vehicles"> View Vehicles </button>
                                        <a href="{{ url('admin/transporters/edit') }}/{{ $transport->id }}"> <button type="button" class="btn btn-primary btn-sm" data-placement="top" title="Edit"> Edit </button> </a>
                                        {{-- <button class="btn btn-danger btn-sm btn_delete" data-id="{{ $transport->id }}" type="button" data-placement="top" title="Delete"> Delete </button> --}}
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
<div class="modal inmodal show fade" id="view_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content animated flipInY" id="view_modalbox_body">
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
    $(document).on("click" , ".btn_view_vehicles" , function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url:'{{ url('admin/transporters/show_vehicles') }}',
            type: 'POST',
            dataType:'json',
            data: {"_token": "{{ csrf_token() }}", 'id': id},
            success:function(status){
                $("#view_modalbox_body").html(status.response);
                $("#view_modalbox").modal('show');
            }
        });
    });

    // $(document).on("click" , ".btn_delete" , function(){
    //     var id = $(this).attr('data-id');
    //     swal({
    //         title: "Are you sure?",
    //         text: "You want to delete this transporter!",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Yes, please!",
    //         cancelButtonText: "No, cancel please!",
    //         closeOnConfirm: false,
    //         closeOnCancel: false
    //     },
    //     function(isConfirm) {
    //         if (isConfirm) {
    //             $(".confirm").prop("disabled", true);
    //             $.ajax({
    //                 url:'{{ url('admin/transporters/delete') }}',
    //                 type:'post',
    //                 data:{"_token": "{{ csrf_token() }}", 'id': id},
    //                 dataType:'json',
    //                 success:function(status){
    //                     $(".confirm").prop("disabled", false);
    //                     if(status.msg == 'success'){
    //                         swal({title: "Success!", text: status.response, type: "success"},
    //                             function(data){
    //                                 location.reload();
    //                             });
    //                     } else if(status.msg=='error'){
    //                         swal("Error", status.response, "error");
    //                     }
    //                 }
    //             });
    //         } else {
    //             swal("Cancelled", "", "error");
    //         }
    //     });
    // });

    // $(document).on("click" , ".btn_delete_vehicle" , function(){
    //     var id = $(this).attr('data-id');
    //     var vehicle_num = $(this).attr('data-num');
    //     var transporter_id = $(this).attr('data-transporter-id');
    //     swal({
    //         title: "Are you sure?",
    //         text: "You want to delete this vehicle!",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Yes, please!",
    //         cancelButtonText: "No, cancel please!",
    //         closeOnConfirm: false,
    //         closeOnCancel: false
    //     },
    //     function(isConfirm) {
    //         if (isConfirm) {
    //             $(".confirm").prop("disabled", true);
    //             $.ajax({
    //                 url:'{{ url('admin/transporters/delete_vehicle') }}',
    //                 type:'post',
    //                 data:{"_token": "{{ csrf_token() }}", 'id': id, 'vehicle_num': vehicle_num, 'transporter_id': transporter_id},
    //                 dataType:'json',
    //                 success:function(status){
    //                     $(".confirm").prop("disabled", false);
    //                     if(status.msg == 'success'){
    //                         swal({title: "Success!", text: status.response, type: "success"},
    //                             function(data){
    //                                 location.reload();
    //                             });
    //                     } else if(status.msg=='error'){
    //                         swal("Error", status.response, "error");
    //                     }
    //                 }
    //             });
    //         } else {
    //             swal("Cancelled", "", "error");
    //         }
    //     });
    // });
</script>

@endpush