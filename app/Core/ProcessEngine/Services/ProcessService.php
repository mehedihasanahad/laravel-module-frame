<?php

namespace App\Core\ProcessEngine\Services;


use App\Core\ProcessEngine\Handlers\AddonFormHandler;
use App\Core\ProcessEngine\Models\ProcessDocument;
use App\Core\ProcessEngine\Models\ProcessList;
use App\Core\ProcessEngine\Models\ProcessPath;
use App\Core\ProcessEngine\Models\ProcessStatus;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\Models\ProcessUserDesk;
use App\Core\ProcessEngine\ProcessHelper;
use App\Libraries\Encryption;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ProcessService
{
    /**
     * Processes the list based on the given request, status, and desk.
     *
     * @param Request $request
     * @param string $status
     * @param string $desk
     * @return mixed
     */
    public function processList(Request $request, int $status = 0, string $desk = ''): mixed
    {
        try {
            $processTypeId = Encryption::decodeId($request->get('processTypeId'));
            $status = ($status == '-1000') ? 0 : $status;
            return ProcessList::listData($processTypeId, $status, $request, $desk);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1041]');
            return redirect()->back();
        }
    }

    /**
     * Retrieves the status list as a JSON response based on the given request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatusListByProcessListId($processListId): Collection
    {
        # Retrieve the application information based on the decoded process list ID
        $appInfo = ProcessList::where('id', $processListId)->first([
            'process_type_id',
            'id as process_list_id',
            'process_status_id as status_id',
            'ref_id',
            'id',
            'json_object',
            'process_user_desk_id as desk_id',
            'updated_at'
        ]);
        # Extract relevant information from the appInfo object
        $statusFrom = $appInfo->status_id;
        $deskId = $appInfo->desk_id;
        $processTypeId = $appInfo->process_type_id;

        # Retrieve the status list based on the extracted information
        $statuses_to = processPath::where('status_from', $statusFrom)
                                  ->where('desk_from', $deskId)
                                  ->where('process_type_id', $processTypeId)
                                  ->pluck('status_to')
                                  ->toArray();

        return ProcessStatus::select('id', 'status_name')
            ->whereIn('id', $statuses_to)
            ->where('process_type_id', $processTypeId)
            ->orderBy('status_name')
            ->get();
    }

    /**
     * Retrieves the process data by the application ID and process type ID.
     *
     * @param int $applicationId
     * @param int $processTypeId
     */
    public function getProcessDataByApplicationId(int $applicationId, int $processTypeId)
    {
        return ProcessList::join('process_types', 'process_types.id', '=', 'process_lists.process_type_id')
            ->join('process_statuses as ps', function ($join) use ($processTypeId) {
                $join->on('ps.id', '=', 'process_lists.process_status_id')
                    ->where('ps.process_type_id', $processTypeId);
            })
            ->join('process_types as pt', 'pt.id', '=', 'process_lists.process_type_id')
            ->leftJoin('process_user_desks', 'process_user_desks.id', '=', 'process_lists.process_user_desk_id')
            ->where('process_lists.ref_id', $applicationId)
            ->where('process_lists.process_type_id', $processTypeId)
            ->first([
                'process_lists.id as process_list_id',
                'process_lists.process_user_desk_id',
                'process_lists.process_type_id',
                'process_lists.process_status_id',
                'process_types.name',
                'ps.status_name',
                'process_user_desks.name as desk_name',
                'process_lists.tracking_no',
                'process_lists.user_id',
                'process_lists.remarks'
            ]);
    }

    /**
     * Performs a batch update of classes based on the given request and desk.
     *
     * @param Request $request
     * @param string $desk
     * @return array
     */
    public function batchUpdateClass(Request $request, string $desk): array
    {
        //this is for batch update code
        $class = [];
        if ($request->has('process_search')) { //work for search parameter
            $class['button_class'] = 'common_batch_update_search';
            $class['input_class'] = 'batchInputSearch';
        } elseif ($request->has('status_wise_list')) {
            $class['button_class'] = "status_wise_batch_update";
            $class['input_class'] = "batchInputStatus";

            if ($request->get('status_wise_list') == 'is_delegation') {
                $class['button_class'] = 'is_delegation';
            }
        } else {
            $class['button_class'] = "common_batch_update";
            $class['input_class'] = '';
            if ($desk == 'my-desk' || $desk == 'my-delg-desk') { //for batch update
                $class['input_class'] = 'batchInput';
            }
        }

        return $class;
    }

    /**
     * Updates the process read status for the specified application ID.
     *
     * @param int $application_id
     */
    public function updateProcessReadStatus(int $application_id): void
    {
        ProcessList::where('ref_id', $application_id)->update(['read_status' => 1]);
    }

    /**
     * Retrieves the delegate users.
     */
    public function getDelegateUsers()
    {
        try {
            $delegateUser = User::where('delegate_to_user_id', Auth::user()->id)->pluck('desk_id')->toArray();
            $tempArr = [];
            foreach ($delegateUser as $value) {
                $userDesk = explode(',', $value);
                $tempArr[] = $userDesk;
            }
            $arraySingle = [];
            if (!empty($tempArr)) {
                $arraySingle = call_user_func_array('array_merge', $tempArr);
            }
            return $arraySingle;
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1001]');
        }
    }

    /**
     * Checks if the user has desk office-wise permission for the specified desk ID.
     *
     * @param int $desk_id
     * @return bool
     */
    public function hasDeskOfficeWisePermission(int $desk_id): bool
    {
        $getSelfAndDelegatedUserDeskOfficeIds = $this->getSelfAndDelegatedUserDeskOfficeIds();
        foreach ($getSelfAndDelegatedUserDeskOfficeIds as $value) {
            if (in_array($desk_id, $value['desk_ids'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieves an array of self and delegated user desk office IDs.
     *
     * @return array
     */
    public function getSelfAndDelegatedUserDeskOfficeIds(): array
    {
        $userId = ProcessHelper::getUserId();
        $delegatedUsers = User::where('id', $userId)->get(['id as user_id', 'desk_id']);

        $delegatedDeskOfficeIds = [];
        foreach ($delegatedUsers as $user) {
            $userDeskIds = explode(',', $user->desk_id);
            $tempArr = [
                'user_id' => $user->user_id,
                'desk_ids' => $userDeskIds,
            ];
            $delegatedDeskOfficeIds[$user->user_id] = $tempArr;
        }
        return $delegatedDeskOfficeIds;
    }

    /**
     * Retrieves desk list by process status.
     * @param int $processListId
     * @param int $statusFrom
     * @param int $statusId
     * @return array
     * @throws \Exception
     */
    public function getDeskByProcessStatusId(int $processListId, int $statusFrom, int $statusId, AddonFormHandler $addonFormInstance): array
    {
        # Get process information
        $processInfo = ProcessList::where('id', $processListId)->first(['process_type_id', 'process_user_desk_id', 'ref_id']);
        if (empty($processInfo)) {
            throw new \Exception('Process Information not found.[PS-239]');
        }

        # Get desk list
        $desks_to = processPath::where('desk_from', 'LIKE', '%' . $processInfo->process_user_desk_id . '%')
                               ->where('status_from', $statusFrom)
                               ->where('process_type_id', $processInfo->process_type_id)
                               ->where('status_to', 'LIKE', '%'. $statusId. '%')
                               ->pluck('desk_to')
                               ->toArray();

        $deskList = ProcessUserDesk::whereIn('id', $desks_to)->get(['id', 'name']);

        # Get process path information
        $processPathInfo = ProcessPath::select('id', 'file_attachment', 'remarks')
            ->where('desk_from', 'like', '%' . $processInfo->desk_id . '%')
            ->where('status_from', $statusFrom)
            ->where('process_type_id', $processInfo->process_type_id)
            ->where('status_to', 'LIKE', '%'. $statusId. '%')
            ->first();
        if (empty($processPathInfo)) {
            throw new \Exception('Process Path Information not found.[PS-263]');
        }

        # Retrieve the addon status for the specified process type and status ID
        $isActiveAddonForm = ProcessStatus::where(['process_type_id' => $processInfo->process_type_id, 'id' => $statusId])->value('addon_status');

        # Retrieve blade file name by status ID
        $addonBladeFileName = $addonFormInstance->getAddonForm();
        $addonBladeForm = "";
        # Check if addon blade filename is exists
        if ($addonBladeFileName) {
            # Prepare the data for the addon blade form
            $addonFormData = $addonFormInstance->prepareData();
            $addonBladeForm = (string) view($addonBladeFileName, $addonFormData);
        }

        # Prepare the response array and return
        return [
            'responseCode' => 1,
            'data' => $deskList,
            'addons_form' => $isActiveAddonForm ? $addonBladeForm : "",
            'remarks' => $processPathInfo->remarks ?? '',
            'file_attachment' => $processPathInfo->file_attachment ?? '',
        ];
    }

    /**
     * Retrieves user list by user desk id.
     * @param int $deskID
     * @return array
     */
    public function getUserListByDeskId(int $deskID): array
    {
        $userList = User::select('id', 'first_name', 'last_name')
            ->where('is_approved', 1)
            ->whereStatus(1)
            ->where('desk_id', 'LIKE', '%' .$deskID. '%')
            ->get();

        return ['responseCode' => 1, 'data' => $userList];
    }

    /**
     * Verification process data
     * @param string $encodedGenaratedString
     * @param ProcessList $processInfo
     * @return bool
     */
    public function verifyProcessData(string $encodedGenaratedString, ProcessList $processInfo): bool
    {
        # Process data verification, if verification is true then proceed for Processing
        $verificationData = [];
        $verificationData['id'] = $processInfo->id;
        $verificationData['status_id'] = $processInfo->process_status_id;
        $verificationData['desk_id'] = $processInfo->process_user_desk_id;
        $verificationData['user_id'] = $processInfo->user_id;
        $verificationData['tracking_no'] = $processInfo->tracking_no;
        $verificationData = (object)$verificationData;

        return Encryption::decode($encodedGenaratedString) === ProcessHelper::generateVerificationString($verificationData);
    }

    public function uploadAttachedFiles(Request $request, int $processListId, int $processTypeId,): void
    {
        $attachedFiles = $request->file('attach_file');
        $fileDetails = [];
        foreach ($attachedFiles as $file) {
            $originalFileName = $file->getClientOriginalName();
            $timestamp = time();
            $filePath = 'uploads/' . $timestamp . $originalFileName;
            $file->move('uploads/', $filePath);

            $fileDetails[] = [
                'process_type_id' => $processTypeId,
                'ref_id' => $processListId,
                'process_desk_id' => $request->get('process_desk_id'),
                'process_status_id' => $request->get('process_status_id'),
                'file' => $filePath,
            ];
        }
        ProcessDocument::insert($fileDetails);
    }

    /**
     * Updates the "closed_by" field for a process based on the status ID.
     *
     * @param int $processTypeId The process information object.
     * @param int $statusID The status ID.
     * @return int
     */
    public function closedByBasedOnStatusId(int $processTypeId, int $statusID): int
    {
        $finalStatus = ProcessType::whereId($processTypeId)->value('final_status');
        $finalStatusArray = explode(",", $finalStatus);
        $isClosed = in_array($statusID, $finalStatusArray);
        return $isClosed ? ProcessHelper::getUserId() : 0;
    }

    /**
     * Fetch all process status by process type id
     *
     * @param int $processTypeId
     * @return array
     */
    public function getStatusListByProcessTypeId(int $processTypeId) : array
    {
        return ProcessStatus::where('process_type_id', $processTypeId)
            ->when(Auth::user()->can('access-draft-application-list') === false,function ($query){ # This permission is assigned to the "Applicant".
                $query->where('id', '!=', -1);
            })
            ->select('status_name', 'id')
            ->orderBy('status_name')
            ->get()->toArray();

    }
}
