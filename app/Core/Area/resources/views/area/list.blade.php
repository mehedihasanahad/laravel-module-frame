@extends('Dashboard::admin.layout.index')

@section('title', 'Area List')

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
                        <h4>Area</h4>
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Area'])
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
                        <a href="{{route('area.create')}}" class="button btn-secondary">Create Area</a>
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
                        <th>Area Name</th>
                        <th>Area Name In Bangla</th>
                        <th>Area Type</th>
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
                    url: '{{ route('area.list') }}',
                    method: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'area_nm'},
                    {data: 'area_nm_ban'},
                    {data: 'area_type',
                        render: function (data) {
                            if (data == 1) {
                                return 'Division';
                            } if(data ==2) {
                                return 'District';
                            }
                           if(data ==3) {
                                return 'Thana';
                            }
                        }},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        });
    </script>
@endsection
