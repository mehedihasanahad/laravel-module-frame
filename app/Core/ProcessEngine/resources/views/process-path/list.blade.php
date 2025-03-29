@extends('Dashboard::admin.layout.index')

@section('title', 'Process Path List')

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
                        <h4>Process Path</h4>
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Process Path'])
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <a href="{{route('process-path.create')}}" class="btn btn-sm btn-secondary">Create Process
                            Path</a>
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
                        <th>Process Type</th>
                        <th>Desk From</th>
                        <th>Desk To</th>
                        <th>Status From</th>
                        <th>Status to</th>
                        <th>Attachment</th>
                        <th>Remark</th>
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
                    url: '{{ route('process-path.list') }}',
                    method: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {
                        data: 'process_type',
                        render: function (data) {
                            if (data !== null) {
                                return data.name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'desk_from',
                        render: function (data) {
                            if (data !== null) {
                                return data.name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'desk_to',
                        render: function (data) {
                            if (data !== null) {
                                return data.name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'status_from',
                        render: function (data) {
                            if (data !== null) {
                                return data.status_name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'status_to',
                        render: function (data) {
                            if (data !== null) {
                                return data.status_name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'file_attachment',
                        render: function (data) {
                            if (data === 1) {
                                return 'True';
                            } else {
                                return 'False';
                            }
                        }
                    },
                    {
                        data: 'remarks',
                        render: function (data) {
                            if (data === 1) {
                                return 'True';
                            } else {
                                return 'False';
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ],
            });
        });
    </script>
@endsection
