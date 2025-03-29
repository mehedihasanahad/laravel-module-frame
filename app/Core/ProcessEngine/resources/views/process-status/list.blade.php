@extends('Dashboard::admin.layout.index')

@section('title', 'Process Status List')

@section('custom-css')
    <!-- DataTables Styles -->
    @includeIf('Dashboard::admin.subviews.datatables_styles')
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4>Process Status</h4>
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Process Status'])
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <a href="{{route('process-statuses.create')}}" class="btn btn-sm btn-secondary">Create Process
                            Status</a>
                    </div>
                </div>
            </div>

            <!-- card-body -->
            <div class="card-body">
                <table id="table_desk" class="table table-striped table-bordered display"
                       style="width: 100%">
                    <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Process Type</th>
                        <th>Status</th>
                        <th style="min-width: 70px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--card-body -->
        </div>
        <!-- Default box -->
    </section>
    <!--Main content -->
@endsection

@section('custom-scripts-links')
    <!-- DataTables  Scripts -->
    @includeIf('Dashboard::admin.subviews.datatables_scripts')
@endsection

@section('custom-scripts')
    <script>
        $(function () {
            $('#table_desk').DataTable({
                iDisplayLength: '{{ 25 }}',
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                "bDestroy": true,
                ajax: {
                    url: '{{ route('process-statuses.list',['process_type_id'=>request()->query('process_type_id')]) }}',
                    method: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'status_name'},
                    {
                        data: 'process_type',
                        render: function (data) {
                            if (data) {
                                return data.name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'status',
                        render: function (data) {
                            if (data === 1) {
                                return '<span class="badge badge-success"> Active</span>';
                            } else {
                                return '<span class="badge badge-danger">Inactive</span>';
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        });
    </script>
@endsection
