@extends('Dashboard::admin.layout.index')

@section('custom-css')
    @if(isset($form) && $form->template_type === 2)
        <!-- Step Js Style -->
        <link rel="stylesheet" href="{{ asset('assets/css/jquery.steps.min.css') }}">
        <style>
            .wizard > .steps > ul > li {
                /*width: 25% !important;*/
                width: calc(100% / {{ count(explode(',', $form->steps_name)) }}) !important;
            }

            .wizard {
                overflow: visible;
            }

            .wizard > .content {
                overflow: visible;
            }

            .wizard > .actions {
                top: -54px;
            }

            .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
                background: {{config('engine.wizard.current.background','#027DB4')  }};
                cursor: {{config('engine.wizard.current.cursor','default') }} ;
            }


            .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
                background: {{config('engine.wizard.done.background','#027DB4') }} ;
                color: {{config('engine.wizard.done.color','#027DB4') }} ;
            }

            .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
                background: {{config('engine.wizard.disabled.background','#F2F2F2') }};
                color: {{config('engine.wizard.disabled.color','#028FCA') }} ;
                cursor: {{config('engine.wizard.disabled.cursor','default')  }} ;
            }
        </style>
    @endif
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{  $processType->name }}</h1>
                    <input type="hidden" name="processTypeId" id="processTypeId"
                           value="{{ \App\Libraries\Encryption::encodeId($processType->process_type_id) }}">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('application.list',[\App\Libraries\Encryption::encodeId($processType->process_type_id)]) }}">Process
                                List</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        @if( !in_array(request()->route()->getName(), ['application.create','application.edit']) && auth()->user()->can('verification-process-data') )
            <div class="card-header">

                <!-- Process Panel -->
                @if ($hasDeskOfficeWisePermission === true && Auth::user()->can('view-batch-process-panel'))
                    <div class="row">
                        <div class="col-sm-12">
                            @includeIf('ProcessEngine::batch-process')
                        </div>
                    </div>
                @endif
                <!--Process Panel -->

                <!-- Application Panel -->
                {{--                    <div class="card with-nav-tabs card-info">--}}
                {{--                        <div class="card-header">--}}
                {{--                            <ul class="nav nav-tabs nav-item">--}}
                {{--                                <li>--}}
                {{--                                    <a class="nav-link active" data-toggle="tab" role="tab" href="#appStatus"--}}
                {{--                                       aria-controls="appStatus" aria-expanded="true">--}}
                {{--                                        <b><i class="fa fa-info-circle"></i> {!! trans('ProcessEngine::messages.application_status') !!}--}}
                {{--                                        </b>--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}

                {{--                                <li class="nav-item">--}}
                {{--                                    <a class="nav-link" data-toggle="tab" role="tab" href="#paymentInfo"--}}
                {{--                                       aria-controls="paymentInfo" aria-expanded="true">--}}
                {{--                                        <b><i class="fa fa-money"></i> {!! trans('ProcessEngine::messages.payment_info') !!}--}}
                {{--                                        </b>--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                @can('process-map')--}}
                {{--                                    <li class="nav-item">--}}
                {{--                                        <a class="nav-link" data-toggle="tab" role="tab" href="#processMap"--}}
                {{--                                           aria-controls="processMap" aria-expanded="true">--}}
                {{--                                            <b><i class="fa fa-map"></i> {!! trans('ProcessEngine::messages.process_map') !!}--}}
                {{--                                            </b>--}}
                {{--                                        </a>--}}
                {{--                                    </li>--}}
                {{--                                @endcan--}}
                {{--                                @can('process-history')--}}
                {{--                                    <li class="nav-link" class="nav-item">--}}
                {{--                                        <a data-toggle="tab" role="tab" href="#processHistory"--}}
                {{--                                           aria-controls="processHistory" aria-expanded="true">--}}
                {{--                                            <b><i class="fa fa-history"></i> {!! trans('ProcessEngine::messages.process_history') !!}--}}
                {{--                                            </b>--}}
                {{--                                        </a>--}}
                {{--                                    </li>--}}
                {{--                                @endcan--}}
                {{--                            </ul>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-body">--}}
                {{--                            <div class="tab-content">--}}
                {{--                                <div class="tab-pane active" id="appStatus">--}}
                {{--                                    <div class="row">--}}
                {{--                                        <div class="col-md-12">--}}
                {{--                                            <div class="card well-sm bg-deep disabled color-palette no-margin">--}}
                {{--                                                <div class="card-body p-2">--}}
                {{--                                                    <div class="row">--}}
                {{--                                                        <div class="col-sm-6">--}}
                {{--                                                            <div class="clearfix no-margin row">--}}
                {{--                                                                <label--}}
                {{--                                                                    class="col-md-5 col-xs-5">{!! trans('ProcessEngine::messages.tracking_no') !!}--}}
                {{--                                                                    . </label>--}}
                {{--                                                                <div class="col-md-7 col-xs-7">--}}
                {{--                                                                    <span>:  </span>--}}
                {{--                                                                </div>--}}
                {{--                                                            </div>--}}

                {{--                                                            <div class="clearfix no-margin row">--}}
                {{--                                                                <label--}}
                {{--                                                                    class="col-md-5 col-xs-5">{!! trans('ProcessEngine::messages.date_of_submission') !!} </label>--}}
                {{--                                                                <div class="col-md-7 col-xs-7">--}}
                {{--                                                                    :--}}
                {{--                                                                </div>--}}
                {{--                                                            </div>--}}
                {{--                                                        </div>--}}
                {{--                                                        <div class="col-sm-6">--}}
                {{--                                                            <div class="clearfix no-margin row">--}}
                {{--                                                                <label--}}
                {{--                                                                    class="col-md-5 col-xs-5">{!! trans('ProcessEngine::messages.current_desk') !!} </label>--}}
                {{--                                                                <div class="col-md-7 col-xs-7">--}}
                {{--                                                            <span>--}}
                {{--                                                                :--}}
                {{--                                                            </span>--}}
                {{--                                                                </div>--}}
                {{--                                                            </div>--}}

                {{--                                                        </div>--}}
                {{--                                                    </div>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                            <ul class="nav ">--}}

                {{--                                            </ul>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="tab-pane" id="paymentInfo">--}}
                {{--                                    <div class="text-center" id="payment-loading">--}}
                {{--                                        <br/>--}}
                {{--                                        <br/>--}}
                {{--                                        <i class="fa fa-spinner fa-pulse fa-4x"></i>--}}
                {{--                                        <br/>--}}
                {{--                                        <br/>--}}
                {{--                                    </div>--}}
                {{--                                    <div id="payment_content_area"></div>--}}
                {{--                                </div>--}}
                {{--                                @can('process-map')--}}
                {{--                                    <div class="tab-pane" id="processMap">--}}
                {{--                                        <div class="text-center" id="map-loading">--}}
                {{--                                            <br/>--}}
                {{--                                            <br/>--}}
                {{--                                            <i class="fa fa-spinner fa-pulse fa-4x"></i>--}}
                {{--                                            <br/>--}}
                {{--                                            <br/>--}}
                {{--                                        </div>--}}

                {{--                                        <h5 id="mapShortfallStatus"></h5>--}}

                {{--                                        <svg width="100%" height="220">--}}
                {{--                                            <g></g>--}}
                {{--                                        </svg>--}}
                {{--                                    </div>--}}

                {{--                                    <div class="tab-pane" id="shadowFileHistory">--}}
                {{--                                        <div class="overlay" id="shadow-file-loading">--}}
                {{--                                            <div class="col-md-12">--}}
                {{--                                                <div class="row  d-flex justify-content-center">--}}
                {{--                                                    <i class="fas fa-3x fa-sync-alt fa-spin text-center"></i>--}}
                {{--                                                </div>--}}
                {{--                                                <div class="row  d-flex justify-content-center">--}}
                {{--                                                    <div class="text-bold pt-2 text-center">--}}
                {{--                                                        Loading...--}}
                {{--                                                    </div>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                        <div id="shadow_file_content_area"></div>--}}
                {{--                                    </div>--}}
                {{--                                @endcan--}}
                {{--                                @can('process-history')--}}
                {{--                                    <div class="tab-pane" id="processHistory">--}}
                {{--                                        <div class="text-center" id="history-loading">--}}
                {{--                                            <br/>--}}
                {{--                                            <br/>--}}
                {{--                                            <i class="fa fa-spinner fa-pulse fa-4x"></i>--}}
                {{--                                            <br/>--}}
                {{--                                            <br/>--}}
                {{--                                        </div>--}}
                {{--                                        <div id="history_content_area"></div>--}}
                {{--                                    </div>--}}
                {{--                                @endcan--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}

                @includeIf('dashboard.viewApplicationInfos')
                <!-- Application Panel -->
            </div>
        @endif
        <!-- Dynamic  Load full content here  -->
        <div class="row">
            <div class="col-md-12">
                <div> {!! $moduleBladeForm !!} </div>
            </div>
        </div>
        <!--  Dynamic full content here  -->
    </section>
    <!--Main content -->
@endsection
@section('custom-scripts-links')
    <script src="{{ asset('js/core/ProcessEngine/ProcessHelper.js') }}"></script>
    @if( isset($form) && $form->template_type === 2)
        <!-- Step Js Script -->
        <script src="{{ asset('assets/js/jquery.steps.min.js')}}"></script>
    @endif
@endsection
@section('custom-scripts')
    <script>
        {{--CommonFunction.validFormV2('{{$form->form_id}}');--}}
        @if( isset($form) && $form->template_type === 2 )
        const form = $("#{{$form->form_id}}");
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            autoFocus: true,
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex === form.find("fieldset").length - 1) {
                    form.find('#submitForm').css('display', 'block');
                    form.find('ul[aria-label=Pagination] li[aria-hidden=false]').css('display', 'none');
                } else {
                    form.find('#submitForm').css('display', 'none');
                }
            },
        });
        @endif

        function addRow(parentElement) {
            const button = parentElement.children[0].children[1];
            const html = parentElement.outerHTML.replace(button.outerHTML, `
        <div class="card-tools">
            <button type="button" class="btn btn-tool" id="addRowButton" onclick="removeRow(this.parentElement.parentElement.parentElement)">
               <i class="fas fa-minus"></i>
            </button>
        </div>`);
            parentElement.parentElement.insertAdjacentHTML('beforeend', html);
        }

        function removeRow(parentElement) {
            parentElement.remove();
        }

        function addRowTable(parentElement) {
            const row = parentElement.children[5];
            const lastElement = parentElement.parentElement.children[parentElement.parentElement.children.length - 1];
            let num = lastElement.children[0].innerText;
            num++;
            let html = parentElement.outerHTML.replace(row.outerHTML, `<td>
                <button class="btn btn-sm btn-success rounded-circle" type="button" onclick="removeRow(this.parentElement.parentElement)">
                <i class="fas fa-minus" ></i>
            </button>
                </td>`);
            html = html.replace(`<td>1</td>`, `<td>${num}</td>`);
            parentElement.parentElement.insertAdjacentHTML('beforeend', html);
        }
    </script>
@endsection



