<form method="POST" action="javascript:void(0)" accept-charset="UTF-8" id="">
    @csrf
    <div class="row" x-data="alpineInit">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2 ">
                    <label for="ProcessType">Service : </label>
                    <select class="form-control search_type" id="ProcessTypeId" x-model="selectedProcessTypeId"
                            name="ProcessTypeId" @change="getStatusListByProcessTypeId()">
                        <option value="" selected="selected">Select</option>
                        @foreach($processTypes as $index => $item)
                            <option value="{{\App\Libraries\Encryption::encodeId($index)}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 ">
                    <label for="status">Status: </label>
                    <select class="form-control search_status" id="statusId" name="statusId">
                        <option value="" selected="selected">Select</option>
                        <template x-for="item,index in statusList" :key="index">
                            <option :value="item.id" x-text="item.status_name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="searchText">Search text:<i class="fa fa-info-circle" aria-hidden="true"
                                                           data-placement="right" data-toggle="tooltip" title=""
                                                           data-original-title="Only the application's tracking number and reference data will be searched by the keyword you provided."></i>
                    </label>
                    <input class="form-control search_text" placeholder="Type at least 3 characters" id="searchText"
                           name="search_text" type="text" value="">
                </div>
                <div class="col-md-2">
                    <label for="searchTimeLine">Date within: </label>
                    <select class="form-control search_time" name="searchTimeLine" id="searchTimeLine">
                        @foreach($searchTimeline as $index => $item)
                            <option value="{{$index}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="dateWithin">of</label>
                    <div class="input-group date" id="dateWithinDP" data-target-input="nearest">
                        <input class="form-control search_date date_within" id="dateWithin" name="date_within"
                               type="text" value="">
                        <div class="input-group-append" data-target="#dateWithinDP" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <label for="">&nbsp;</label><br>
                    <input type="button" id="searchBtn" class="btn btn-primary" value="Search">
                </div>

            </div>
        </div>
    </div>
</form>
{{-- searching data show if data is found --}}
<div id="processListDiv" style="margin-top: 20px;display: none">
    <table id="table_search" class="table table-striped table-bordered display" style="width: 100%">
        <thead>
        <tr>
            <th>{!! trans('ProcessEngine::messages.tracking_no') !!}</th>
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
<script>
    // Alpine Data Init
    function alpineInit() {
        return {
            selectedProcessTypeId: '',
            statusList: [],
            getStatusListByProcessTypeId: async function () {
                if (!this.selectedProcessTypeId) return;
                try {
                    const response = await axios.get("{{ route('get-status-list-by-process-type-id') }}", {
                        params: {
                            process_type_id: this.selectedProcessTypeId
                        }
                    });
                    if (response.data.responseCode === 1) {
                        this.statusList = response.data.data;
                    }
                } catch (error) {
                    console.error('Error in status list:', error);
                }
            }
        }
    }
    // search process list
    let searchBtnElement = document.getElementById('searchBtn');
    searchBtnElement.addEventListener('click', function () {
        let processTypeId = document.getElementById('ProcessTypeId').value;
        let statusId = document.getElementById('statusId').value;
        let searchText = document.getElementById('searchText').value;
        let searchTimeLine = document.getElementById('searchTimeLine').value;
        let dateWithin = document.getElementById('dateWithin').value;

        document.getElementById('processListDiv').style.display = 'block';
        showApplicationListByDesk('{{route('application.list.data') }}', 'table_search', function (requestData) {
            requestData.process_search = true;
            requestData.processTypeId = processTypeId;
            requestData.search_time = searchTimeLine;
            requestData.search_text = searchText;
            requestData.search_date = dateWithin;
            requestData.search_status = statusId;
        })

    })
</script>
