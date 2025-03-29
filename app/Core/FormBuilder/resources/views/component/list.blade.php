@extends('Dashboard::admin.layout.index')

@section('title', 'Component List')

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
                        <h4>Component</h4>
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Component'])
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <a href="{{route('component.create')}}" class="btn btn-sm btn-secondary">Create Component</a>
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
                        <th>Form Name</th>
                        <th>Component Name</th>
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
                    url: '{{ route('component.index') }}',
                    method: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {
                        data: 'form',
                        render: function (data) {
                            return data ? data.title : '';
                        }
                    },
                    {
                      data: 'title',
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
