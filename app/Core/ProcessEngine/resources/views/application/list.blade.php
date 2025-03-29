@extends('Dashboard::admin.layout.index')

@section('custom-css')
    <!-- Tempus dominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- DataTables Styles -->
    @includeIf('Dashboard::admin.subviews.datatables_styles')
@endsection

@section('content')
    @inject('encryption','\App\Libraries\Encryption')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4>{{  $processInfo->name }}</h4>
                        <input type="hidden" name="processTypeId" id="processTypeId"
                               value="{{$encryption::encodeId($processInfo->id) }}">
                        <!-- Breadcrumb -->
                        @includeIf('Dashboard::admin.subviews.breadcrumb', ['active_module_name' => 'Process List'])
                    </div>
                    @can('application-add')
                        <div class="col d-flex justify-content-end align-items-center">
                            <a href="{{route('application.create',['process_type_id'=>$encryption::encodeId($processInfo->id),'form_type'=>$encryption::encodeId(1)])}}"
                               class="btn btn-sm btn-secondary">Create</a>
                        </div>
                    @endcan
                </div>
            </div>

            <!-- card-body -->
            <div class="card-body">
                <div class="clearfix">
                    <div class="" id="statusWiseAppsDiv"></div>
                </div>
                <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0 5px;">
                    <nav class="navbar navbar-expand-md justify-content-center">
                        <ul class="nav nav-tabs">
                            @can('access-my-desk')
                                <li id="tab1" class="nav-item">
                                    <a data-toggle="tab" href="#list_desk" id="myDesk" class="nav-link active"
                                       aria-expanded="true">
                                        <b>{{ trans('ProcessEngine::messages.my_desk') }}</b>
                                    </a>
                                </li>
                            @endcan
                            @can('access-delegation-desk')
                                <li id="tab2">
                                    <a data-toggle="tab" href="#delegationList" aria-expanded="false" class="nav-link"
                                       id="delegationDeskLists">
                                        <b>{{ trans('ProcessEngine::messages.delegation_desk') }}</b>
                                    </a>
                                </li>
                            @endcan
                            @can('access-my-list')
                                <li id="tab1" class="nav-item active">
                                    <a data-toggle="tab" href="#list_desk" id="myDesk" class="nav-link active"
                                       aria-expanded="true">
                                        <b>{{ trans('ProcessEngine::messages.list') }}</b>
                                    </a>
                                </li>
                            @endcan
                            <li id="tab4" class="nav-item">
                                <a data-toggle="tab" href="#favoriteList" id="favoriteLists" class="nav-link"
                                   aria-expanded="true">
                                    <b>{{ trans('ProcessEngine::messages.favourite') }}</b>
                                </a>
                            </li>
                            <li id="tab3" class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#list_search"
                                   id="search_by_keyword" aria-expanded="false">
                                    <b>{{ trans('ProcessEngine::messages.search') }}</b>
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="process_type_tab nav-item" id="processDropdown"></li>
                        </ul>
                    </nav>
                    <div class="tab-content">
                        <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                            <table id="table_desk" class="table table-striped table-bordered display"
                                   style="width: 100%">
                                <thead>
                                <tr>
                                    <th style="width: 15%;">{!! trans('ProcessEngine::messages.tracking_no') !!}</th>
                                    <th>{!! trans('ProcessEngine::messages.current_desk') !!}</th>
                                    <th>{!! trans('ProcessEngine::messages.process_type') !!}</th>
                                    <th style="width: 35%">{!! trans('ProcessEngine::messages.reference_data') !!}</th>
                                    <th>{!! trans('ProcessEngine::messages.status_') !!}</th>
                                    <th>{!! trans('ProcessEngine::messages.modified') !!}</th>
                                    <th>{!! trans('ProcessEngine::messages.action') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="list_search" class="tab-pane" style="margin-top: 20px">
                            @include('ProcessEngine::application.search')
                        </div>
                        {{--  delegation list datatable --}}
                        <div id="delegationList" class="tab-pane" style="margin-top: 20px">
                            <div class="table-responsive">
                                <table id="table_delg_desk" class="table table-striped table-bordered display"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 15%;">{!! trans('ProcessEngine::messages.tracking_no') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.current_desk') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.process_type') !!}</th>
                                        <th style="width: 35%">{!! trans('ProcessEngine::messages.reference_data') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.status_') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.modified') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.action') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{--  favorite list datatable --}}
                        <div id="favoriteList" class="tab-pane" style="margin-top: 20px">
                            <div class="table-responsive">
                                <table id="favorite_list" class="table table-striped table-bordered display"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 15%;">{!! trans('ProcessEngine::messages.tracking_no') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.current_desk') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.process_type') !!}</th>
                                        <th style="width: 35%">{!! trans('ProcessEngine::messages.reference_data') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.status_') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.modified') !!}</th>
                                        <th>{!! trans('ProcessEngine::messages.action') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--card-body -->
        </div>
        <!-- Default box -->
    </section>
    <!--Main content -->
@endsection

@section('custom-scripts-links')
    <!-- moment -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <!-- Tempus dominus Bootstrap 4 -->
    <script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- DataTables  Scripts -->
    @includeIf('Dashboard::admin.subviews.datatables_scripts')
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/core/ProcessEngine/ProcessHelper.js') }}"></script>
    <script>
        // my-desk list
        document.addEventListener('DOMContentLoaded', function () {
            showApplicationListByDesk('{{route('application.list.data') }}', 'table_desk', function (requestData) {
                requestData.processTypeId = processTypeId;
                requestData.status = '-1000';
                requestData.desk = 'my-desk';
            });
        });
        // delegation desk list
        let delegationDeskListsElement = document.getElementById('delegationDeskLists');
        if (delegationDeskListsElement) {
            delegationDeskListsElement.addEventListener('click', function () {
                showApplicationListByDesk('{{route('application.list.data') }}', 'table_delg_desk', function (requestData) {
                    requestData.processTypeId = processTypeId;
                    requestData.status = '-1000';
                    requestData.desk = 'my-delegation-desk';
                })
            })
        }
        // favorite list
        document.getElementById('favoriteLists').addEventListener('click', function () {
            showApplicationListByDesk('{{route('application.list.data') }}', 'favorite_list',function (requestData) {
                requestData.processTypeId = processTypeId;
                requestData.status = '-1000';
                requestData.desk = 'favorite-list';
            })
        })
        // init datepicker for search data
        $(function () {
            $('#dateWithinDP').datetimepicker({
                viewMode: 'days',
                format: 'DD-MMM-YYYY',
                maxDate: 'now'
            });
        });
    </script>
@endsection



