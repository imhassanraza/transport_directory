@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2>Vehicle Details</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Vehicle Details </strong>
            </li>
            <li class="breadcrumb-item active">
                <strong class="label label-primary">{{ $vehicle->vehicle_number}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/drivers') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Drivers
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="show_vehicle_tab"><a class="nav-link active show" data-toggle="tab" href="#tab-1">Driver Detail</a></li>
                    <li class="show_bilty_tab"><a class="nav-link" data-toggle="tab" href="#tab-2">Bilty Detail</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active show" role="tabpanel">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Drivers</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dt-responsive nowrap table_tbl5" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Driver</th>
                                                <th>Phone No</th>
                                                <th>CNIC</th>
                                                <th>City</th>
                                                <th>Joining Date</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @foreach($drivers as $driver)
                                            <tr class="gradeX">
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <a href="{{ admin_url() }}/drivers/show/{{ $driver->id }}" target="_blank" class="text-info" data-placement="top" title="View Details">
                                                        <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $driver->photo }}" style="width: 30px;"><br>
                                                        {{ $driver->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $driver->phone_no }}</td>
                                                <td>{{ $driver->cnic }}</td>
                                                <td>
                                                    <a href="{{ url('admin/city/show') }}/{{$driver->city_id}}" target="_blank" class="text-info">
                                                        {{ get_single_value('cities', 'name', $driver->city_id) }}
                                                    </a>
                                                </td>
                                                <td>{{ date_formated($driver->created_at) }}</td>
                                                <td>{{ date_formated($driver->start_date) }}</td>
                                                <td>
                                                    @if(!empty($driver->end_date))
                                                    {{ date_formated($driver->end_date) }}
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

                    <div id="tab-2" class="tab-pane" role=tabpanel>
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Bilty Detail</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dt-responsive nowrap table_tbl6" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Driver</th>
                                                <th>Phone No</th>
                                                <th>CNIC</th>
                                                <th>Vehicle Type</th>
                                                <th>Source City</th>
                                                <th>Distination City</th>
                                                <th>Guarantor</th>
                                                <th>Bilty Detail</th>
                                                <th>Bilty Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 1)
                                            @foreach($loadings as $lodgs)
                                            <tr class="gradeX">
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <a href="{{ admin_url() }}/drivers/show/{{ $lodgs->driver_id }}" target="_blank" class="text-info" data-placement="top" title="View Details">
                                                        <img class="rounded-circle m-t-xs img-fluid" src="{{ asset('assets/img') }}/{{ $lodgs->photo }}" style="width: 30px;"><br>
                                                        {{ $lodgs->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $lodgs->phone_no }}</td>
                                                <td>{{ $lodgs->cnic }}</td>
                                                <td>
                                                    {{ get_single_value('vehicle_types', 'vehicle_type', $lodgs->vehicle_type) }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/city/show') }}/{{ $lodgs->source_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                                        {{ get_single_value('cities', 'name', $lodgs->source_city) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/city/show') }}/{{ $lodgs->destination_city }}" class="text-info" target="_blank" data-placement="top" title="View Detail">
                                                        {{ get_single_value('cities', 'name', $lodgs->destination_city) }}
                                                    </a>
                                                </td>
                                                <td>{{ $lodgs->guarantor_detail }}</td>
                                                <td>
                                                    <?php echo wordwrap($lodgs->bilty_details, 180, "<br>\n"); ?>
                                                </td>

                                                <td>{{ date_formated($lodgs->bilty_date) }}</td>
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
    $(document).on('click', '.show_bilty_tab', function () {
        if($('#DataTables_Table_1').length > 0){
        }else{
            var table1 = $('.table_tbl6').dataTable({
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
        }
    });
</script>

@endpush