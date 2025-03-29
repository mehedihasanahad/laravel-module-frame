<style>
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
        color: {{config('engine.viewApplicationInfos.textColor', '#000000')}} !important;
    }
</style>
<div class="card with-nav-tabs" style="color: {{config('engine.viewApplicationInfos.textColor', '#000000')}} !important;">
    <div class="card-header" style="padding-bottom: 0; background: {{config('engine.viewApplicationInfos.headerColor', '#f1f1f1')}} !important;">
        <ul class="nav nav-tabs nav-item">
            <li>
                <a class="nav-link active" data-toggle="tab" role="tab" href="#appStatus"
                   aria-controls="appStatus" aria-expanded="true">
                    <b><i class="fa fa-info-circle"></i> {!! trans('messages.application_status') !!}
                    </b>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" role="tab" href="#paymentInfo"
                   aria-controls="paymentInfo" aria-expanded="true">
                    <b><i class="fa fa-money"></i> {!! trans('messages.payment_info') !!}
                    </b>
                </a>
            </li>
            @can('process-map')
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" href="#processMap"
                       aria-controls="processMap" aria-expanded="true">
                        <b><i class="fa fa-map"></i> {!! trans('messages.process_map') !!}
                        </b>
                    </a>
                </li>
            @endcan
            @can('process-history')
                <li class="nav-link" class="nav-item">
                    <a data-toggle="tab" role="tab" href="#processHistory"
                       aria-controls="processHistory" aria-expanded="true">
                        <b><i class="fa fa-history"></i> {!! trans('messages.process_history') !!}
                        </b>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
    <div class="card-body" style="background: {{config('engine.viewApplicationInfos.bodyColor', '#f1f1f1')}} !important;">
        <div class="tab-content">
            <div class="tab-pane active" id="appStatus">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="clearfix no-margin row">
                                    <label
                                        class="col-md-5 col-xs-5">{!! trans('messages.tracking_no') !!}
                                        . </label>
                                    <div class="col-md-7 col-xs-7">
                                        <span>:  </span>
                                    </div>
                                </div>

                                <div class="clearfix no-margin row">
                                    <label
                                        class="col-md-5 col-xs-5">{!! trans('messages.date_of_submission') !!} </label>
                                    <div class="col-md-7 col-xs-7">
                                        :
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="clearfix no-margin row">
                                    <label
                                        class="col-md-5 col-xs-5">{!! trans('messages.current_desk') !!} </label>
                                    <div class="col-md-7 col-xs-7">
                                                            <span>
                                                                :
                                                            </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <ul class="nav ">

                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="paymentInfo">
                <div class="text-center" id="payment-loading">
                    <br/>
                    <br/>
                    <i class="fa fa-spinner fa-pulse fa-4x"></i>
                    <br/>
                    <br/>
                </div>
                <div id="payment_content_area"></div>
            </div>
            @can('process-map')
                <div class="tab-pane" id="processMap">
                    <div class="text-center" id="map-loading">
                        <br/>
                        <br/>
                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                        <br/>
                        <br/>
                    </div>

                    <h5 id="mapShortfallStatus"></h5>

                    <svg width="100%" height="220">
                        <g></g>
                    </svg>
                </div>

                <div class="tab-pane" id="shadowFileHistory">
                    <div class="overlay" id="shadow-file-loading">
                        <div class="col-md-12">
                            <div class="row  d-flex justify-content-center">
                                <i class="fas fa-3x fa-sync-alt fa-spin text-center"></i>
                            </div>
                            <div class="row  d-flex justify-content-center">
                                <div class="text-bold pt-2 text-center">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="shadow_file_content_area"></div>
                </div>
            @endcan
            @can('process-history')
                <div class="tab-pane" id="processHistory">
                    <div class="text-center" id="history-loading">
                        <br/>
                        <br/>
                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                        <br/>
                        <br/>
                    </div>
                    <div id="history_content_area"></div>
                </div>
            @endcan
        </div>
    </div>
</div>
