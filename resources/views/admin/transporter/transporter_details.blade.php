@extends('admin.admin_app')
@push('styles')
<link href="{{ asset('admin_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/plugins/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Transporters </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Transporter Details </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        @if($transporter->type == '1')
        <a class="btn btn-primary t_m_25" href="{{ url('admin/transporters') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Transporters</a>
        @else
        <a class="btn btn-primary t_m_25" href="{{ url('admin/vehicles') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Vehicles</a>
        @endif
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="show_driver_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Transporter Detail</a></li>
                    <li class="show_route_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Transporter Vehicles</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active show" role="tabpanel">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Transporter Detail</h5>
                            </div>
                            <div class="ibox-content">
                                <input type="hidden" name="id" class="form-control" value="{{ $transporter->id }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <strong class="col-sm-2 col-form-label">Transporter Name</strong>
                                            <div class="col-sm-4 col-form-label">
                                                {{ $transporter->transporter_name }}
                                            </div>
                                            <strong class="col-sm-1 col-form-label">City</strong>
                                            <div class="col-sm-4 col-form-label">
                                                {{ get_single_value('cities', 'name', $transporter->city_id) }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <strong class="col-sm-2 col-form-label">Phone No</strong>
                                            <div class="col-sm-4 col-form-label">
                                                {{ $transporter->phone_no }}
                                            </div>
                                            <strong class="col-sm-1 col-form-label">Vehicle Qty</strong>
                                            <div class="col-sm-4 col-form-label">
                                                <span class="label label-primary">{{ $transporter->total_vehicle }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <strong class="col-sm-2 col-form-label">Date</strong>
                                            <div class="col-sm-4 col-form-label">
                                                {{ date_formated($transporter->created_at) }}
                                            </div>
                                            @if(Auth::guard('admin')->user()->type == '0')
                                            <strong class="col-sm-1 col-form-label">Added By</strong>
                                            <div class="col-sm-4 col-form-label">
                                                <span class="label label-primary">{{ get_single_value('admin_users', 'username', $transporter->created_by) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane" role="tabpanel">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Transporter Vehicles</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Vehicle Number</th>
                                                <th>Vehicle Type</th>
                                                <th>Vehicle City</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @foreach($vehicles as $vehicle)
                                            <tr class="gradeX">
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <a href="{{ url('admin/drivers/vehicle_detail') }}/{{$vehicle->id}}" target="_blank" class="text-info">
                                                        {{ $vehicle->vehicle_number }}
                                                    </a>
                                                </td>
                                                <td>{{ get_single_value('vehicle_types', 'vehicle_type', $vehicle->vehicle_type) }}</td>
                                                <td>
                                                    <a href="{{ url('admin/city/show') }}/{{$vehicle->vehicle_city}}" target="_blank" class="text-info">
                                                        {{ get_single_value('cities', 'name', $vehicle->vehicle_city) }}
                                                    </a>
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
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $('.table_tbl5').dataTable({
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
</script>
@endpush