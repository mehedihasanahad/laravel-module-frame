@extends('Dashboard::admin.layout.index')

@section('title', 'Process Type List')

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
                        <h4>Process Type</h4>
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Process Type'])
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <a href="{{route('process-type.create')}}" class="btn btn-sm btn-secondary">Create Process
                            Type</a>
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
                        <th>Bn Name</th>
                        <th>Status</th>
                        <th style="min-width: 70px;">Statuses</th>
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
                    url: '{{ route('process-type.list') }}',
                    method: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'name_bn'},
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
                    {data: 'statuses', name: 'statuses', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ],
            });
        });
    </script>
@endsection
