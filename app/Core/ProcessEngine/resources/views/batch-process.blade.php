<div class="card" style="color: {{config('engine.batchProcess.textColor', '#000000')}} !important;">
    <form method="POST" action="{{ route('process.update') }}" accept-charset="UTF-8"
          id="batch-process-form"
          enctype="multipart/form-data">
        @csrf
        <div class="card-header" style="background: {{config('engine.batchProcess.headerColor', '#f1f1f1')}} !important;">
            <div class="col-sm-12">
                <div class="pull-left d-flex justify-content-between">
                    <h4 class="no-margin no-padding" style="line-height: 30px">Application
                        Process :</h4>
                    <a data-toggle="modal" data-target="#remarksHistoryModal"
                       class="float-right">
                        <button type="button" class="btn btn-sm btn-secondary"><i
                                class="fa fa-eye"></i> Last Remarks
                        </button>
                    </a>
                </div>
                <!-- Last Remarks Modal -->
                <div class="pull-right">
                    <div class="modal fade" id="remarksHistoryModal" tabindex="-1"
                         role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header"></div>
                                <div class="modal-body">
                                    <div class="list-group">
                                                            <span class="list-group-item" style="color: rgba(0,0,0,0.8);">
                                                                <h4 class="list-group-item-heading">Remarks</h4>
                                                                @if (!empty( $processInfo->remarks))
                                                                    <p class="list-group-item-text">{{ $processInfo->remarks }}</p>
                                                                @endif
                                                            </span>
                                    </div>
                                </div>
                                <div class="modal-footer" style="text-align:left;">
                                    <button type="button" class="btn btn-danger btn-md pull-right" data-dismiss="modal">Close</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="card-body" style="background: {{config('engine.batchProcess.bodyColor', '#ffff')}} !important;">
            <div class="row" x-data="dataInit">
                <div class="col-sm-12">
                    <!-- Batch Process Panel -->
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                            </div>
                        </div>

                        <div class="row" style="max-height: fit-content;">
                            <div class="loading" style="display: none">
                                <h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2>
                            </div>
                            <!-- Process Status -->
                            <div class="col-md-3 form-group ">
                                <label for="status_id">Status</label>
                                <select class="form-control required process_status_id" x-model="selectedStatusId"
                                        @change="getDeskListByProcessStatusId()" id="process_status_id"
                                        name="process_status_id" required>
                                    <option value="">Select</option>
                                    <template x-for="item,index in statusList()" :key="index">
                                        <option :value="item.id" x-text="item.status_name"></option>
                                    </template>
                                </select>
                                <span x-show="statusLoading">Loading...</span>
                            </div>
                            <!-- User Desk -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6" x-show="isShowUserDeskDropDown">
                                        <div class="col-md-12 form-group">
                                            <label for="desk_id">Send to Desk</label>
                                            <select x-model="selectedDeskId" name="process_desk_id"
                                                    @change="getUserListByDeskId()" class="form-control"
                                                    id="process_desk_id" :required="selectedStatusId ? true : false;">
                                                <option value="">Select Below</option>
                                                <template x-for="item, index in deskList" :key="index">
                                                    <option :value="item.id" x-text="item.name"></option>
                                                </template>
                                            </select>
                                            {{--                                            <span class="help-block"></span>--}}
                                            <span x-show="deskLoading">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 is_user" x-show="isShowUserDropDown">
                                        <div class="form-group">
                                            <label for="is_user">User</label><br>
                                            <select name="process_user_id" class="form-control" id="process_user_id">
                                                <option value="">Select user</option>
                                                <template x-for="item, index in userList" :key="index">
                                                    <option :value="item.id" x-text="item.name"></option>
                                                </template>
                                            </select>
                                            {{--                                            <span class="help-block"></span>--}}
                                            <span x-show="userLoading">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--add custom field if needed--}}
                        @includeIf('dashboard.extendBatchProcess')

                        {{-- Remarks --}}
                        <div class="row">
                            <div class="col-md-12 form-group maxTextCountDown">
                                <label for="remarks">Remarks <span class="text-danger" style="font-size: 9px; font-weight: bold">(Maximum length 250)</span></label>
                                <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks"
                                          maxlength="1000" rows="2"  :required="isRemarksRequired"></textarea>
                                <input name="is_remarks_required" type="hidden" :value="isRemarksRequired">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        {{-- AddOns form --}}
                        <div class="row">
                            <div x-html="addonsFormHtml"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <input name="is_file_required" type="hidden" :value="isFileRequired">
                            </div>
                            <div class="col-sm-6">
                                <div class="text-right">
                                    {{-- encrypted application information  --}}
                                    @if(isset($verificationData))
                                        <input type="hidden" name="process_verification_data" value="{{ \App\Libraries\Encryption::encode(\App\Core\ProcessEngine\ProcessHelper::generateVerificationString($verificationData)) }}"/>
                                    @endif
                                    {{-- encrypted process list id  --}}
                                    <input name="process_list_id" type="hidden"
                                           value="{{ \App\Libraries\Encryption::encodeId($processInfo->process_list_id) }}">
                                    {{-- encrypted process type id  --}}
                                    <input name="process_type_id" type="hidden"
                                           value="{{ \App\Libraries\Encryption::encodeId($processInfo->process_type_id) }}">
                                    <button class="btn btn-success send" type="submit" value="submit">
                                        Process
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Batch Process Panel -->
                </div>
            </div>
        </div>
    </form>
</div>
<script >
    // Alpine Data Init
    function dataInit() {
        return {
            // Init Data
            selectedStatusId: 0,
            selectedDeskId: 0,
            isShowUserDeskDropDown: false,
            isShowUserDropDown: false,
            deskList: [],
            userList: [],
            addonsFormHtml: '',
            isRemarksRequired: false,
            isFileRequired: false,
            statusLoading: false,
            deskLoading: false,
            userLoading: false,
            // get process status list
            statusList: async function () {
                try {
                    this.statusLoading = true;
                    const url = '{{ route('process.status.list',[\App\Libraries\Encryption::encodeId($processInfo->process_list_id)]) }}';
                    const {data : {data: statusList}} = await axios.get(url);
                    this.statusLoading = false;
                    return statusList;
                } catch (error) {
                    console.error('Error in statusList:', error);
                    return [];
                }
            },
            // get desk list by process ID
            getDeskListByProcessStatusId: async function () {
                if (!this.selectedStatusId) return;
                try {
                    this.statusLoading = true;
                    const response = await axios.post("{{ route('process.desk.list') }}", {
                        _token: '{{ csrf_token() }}',
                        process_type_id: '{{ $encoded_process_type_id }}',
                        process_list_id: '{{ \App\Libraries\Encryption::encodeId($processInfo->process_list_id) }}',
                        status_from: '{{ $processInfo->process_status_id }}',
                        statusId: this.selectedStatusId
                    });
                    this.statusLoading = false;
                    if (response.data.responseCode === 1 && response.data.data.length > 0) {
                        this.isShowUserDeskDropDown = true;
                        this.deskList = response.data.data;
                        this.isRemarksRequired = (response.data.remarks != '') ? true : false;
                        this.isFileRequired = (response.data.file_attachment != '') ? true : false;
                        this.addonsFormHtml = response.data.addons_form;
                    }
                } catch (error) {
                    console.error('Error in DeskList:', error);
                }
            },
            // get user list by desk ID
            getUserListByDeskId: async function () {
                if (!this.selectedDeskId) return;
                try {
                    this.deskLoading = true;
                    const response = await axios.post("{{ route('process.user.list') }}", {
                        _token: '{{ csrf_token() }}',
                        processListId: '{{ $encoded_process_type_id }}',
                        deskId: this.selectedDeskId
                    });
                    this.deskLoading = false;
                    if (response.data.responseCode === 1 ) {
                        this.isShowUserDropDown = true;
                        this.userList = response.data.data;
                    }
                } catch (error) {
                    console.error('Error in DeskList:', error);
                }
            }
        }
    }
</script>



