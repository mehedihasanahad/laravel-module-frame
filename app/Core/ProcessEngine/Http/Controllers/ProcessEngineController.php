<?php

namespace App\Core\ProcessEngine\Http\Controllers;

use App\Core\ProcessEngine\Handlers\AddonFormHandler;
use App\Core\ProcessEngine\Handlers\ProcessCallbackInterface;
use App\Core\ProcessEngine\Models\ProcessList;
use App\Core\ProcessEngine\Models\ProcessPath;
use App\Core\ProcessEngine\Requests\ProcessRequest;
use App\Core\ProcessEngine\Services\ProcessService;
use App\Core\User\Models\User;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class ProcessEngineController extends Controller
{
    private ProcessService $processService;

    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    /**
     *  update process list
     *
     * @param ProcessRequest $request
     * @param ProcessCallbackInterface $moduleProcessInstance
     * @return RedirectResponse
     */
    public function processUpdate(ProcessRequest $request, ProcessCallbackInterface $moduleProcessInstance): RedirectResponse
    {
        try {
            DB::beginTransaction();

            # Decode process list ID from request
            $processListId = Encryption::decodeId($request->get('process_list_id'));
            $statusId = trim($request->get('process_status_id'));
            $deskId = (empty($request->get('process_desk_id')) ? 0 : trim($request->get('process_desk_id')));

            # Retrieve process information
            $processInfo = ProcessList::where('id', $processListId)->first(['id', 'ref_id', 'tracking_no', 'process_type_id', 'process_status_id', 'process_user_desk_id', 'hash_value', 'user_id', 'locked_by']);

            # Check if process information is empty
            if (empty($processInfo)) {
                Session::flash('error', 'Sorry, process information not found. [PEC-54]');
                return redirect()->route('application.list', [Encryption::encodeId($processInfo->process_type_id)]);
            }

            # Count process paths that match the given criteria
            $processPathCount = ProcessPath::where('process_type_id', $processInfo->process_type_id)
                ->where('status_from', $processInfo->process_status_id)
                ->where('desk_from', $processInfo->process_user_desk_id)
                ->where('desk_to', $deskId)
                ->where('status_to', 'LIKE', '%' . $statusId . '%')
//                ->whereRaw("status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$'")
                ->count();

            # Check if process path count is zero
            if ($processPathCount === 0) {
                Session::flash('error', 'Sorry, invalid process request. [PPC-1002]');
                return redirect()->route('application.list', [Encryption::encodeId($processInfo->process_type_id)]);
            }

            # Check user selected
            $userId = $request->get('process_user_id') ?? 0;
            if ($userId) {
                # Check if user exists in the database
                $findUser = User::find($userId);
                if (empty($findUser)) {
                    Session::flash('error', 'Desk user not found! [PEC-78]');
                    return redirect()->back()->withInput();
                }
            }

            # Verify process data
            if ($this->processService->verifyProcessData($request->get('process_verification_data'), $processInfo) === false) {
                Session::flash('error', 'Sorry, Process data verification failed. [PEC-85]');
            }

            # Store process attachments
            if ($request->hasFile('attach_file')) {
                $this->processService->uploadAttachedFiles($request, $processListId, $processInfo->process_type_id);
            }

            # Generate hash data for hash calculation
            $hashData = implode("-", [$processInfo->id, $processInfo->tracking_no, $deskId, $statusId, $userId, Auth::user()->id]);

            # Prepare process data for modification
            $processData = [
                'process_user_desk_id' => $deskId,
                'process_status_id' => $statusId,
                'remarks' => $request->get('remarks'),
                'user_id' => $userId,
                'updated_by' => Auth::user()->id,
                'locked_by' => 0,
                'locked_at' => null,
                'read_status' => 0,
                'closed_by' => $this->processService->closedByBasedOnStatusId($processInfo->process_type_id, $statusId),
                'previous_hash' => $processInfo->hash_value,
                'hash_value' => Encryption::encodeId($hashData),
            ];

            # Update process data in the database
            ProcessList::whereId($processInfo->id)->update($processData);

            # Perform module process callback
            $callBackResult = $moduleProcessInstance->processCallback($processInfo->id, $statusId, $processInfo->desk_id, $request);

            # Check if process callback was successful
            if ($callBackResult === false) {
                DB::rollback();
                Session::flash('error', 'Sorry! Something is wrong. [PEC-120]');
                return Redirect::back()->withInput();
            }
            DB::commit();
            // TODO :: new code for batch update

            # redirect to application list
            Session::flash('success', 'Process has been updated successfully.');
            return \redirect()->route('application.list', [Encryption::encodeId($processInfo->process_type_id)]);

        } catch (\Exception $exception) {
            DB::rollback();
            Session::flash('error', 'Sorry! Something is Wrong. [PEC-1003]');
            return Redirect::back()->withInput();
        }
    }

    /**
     *  get process status list by process type id
     *
     * @param string $processTypeId
     * @return JsonResponse
     */
    public function getStatusListByProcessListId(string $processListId): JsonResponse
    {
        try {
            # Decode the process list ID using the Encryption class
            $decodedProcessListId = Encryption::decodeId($processListId);
            # retrieves status list data
            $statusList = $this->processService->getStatusListByProcessListId($decodedProcessListId);
            # Prepare the response data
            $responseData = [
                'responseCode' => 1,
                'data' => $statusList
            ];
        } catch (\Exception $exception) {
            # Handle exception
            $responseData = [
                'responseCode' => 0,
                'message' => $exception->getMessage(),
                'data' => []
            ];
        }
        # Return the response as JSON
        return response()->json($responseData);
    }

    /**
     *  get desk list by process status id
     *
     * @param Request $request
     * @param ProcessCallbackInterface $moduleProcessServiceInstance
     * @return JsonResponse
     */
    public function getDeskListByProcessStatusId(Request $request, AddonFormHandler $addonFormInstance): JsonResponse
    {
        try {
            # Decode encrypted ID
            $decodedProcessListId = Encryption::decodeId($request->get('process_list_id'));
            $statusFrom = $request->get('status_from');
            $statusId = trim($request->get('statusId'));
            # Get desk information by process status
            $responseData = $this->processService->getDeskByProcessStatusId($decodedProcessListId, $statusFrom, $statusId, $addonFormInstance);
        } catch (\Exception $e) {
            # Handle exception
            $responseData = ['responseCode' => 0, 'message' => $e->getMessage(), 'data' => []];
        }
        # Return JSON response
        return response()->json($responseData);
    }

    /**
     *  get user list by desk id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserListByUserDeskId(Request $request): JsonResponse
    {
        try {
            $deskID = $request->get('deskId');
            $responseData = $this->processService->getUserListByDeskId($deskID);
        } catch (\Exception $exception) {
            # Handle exception
            $responseData = ['responseCode' => 0, 'message' => $exception->getMessage(), 'data' => []];
        }
        # Return JSON response
        return response()->json($responseData);
    }

    public function getStatusListByProcessTypeId(Request $request): JsonResponse
    {
        $encodedProcessTypeId = $request->get('process_type_id');
        $processTypeId = Encryption::decodeId($encodedProcessTypeId);
        $statusList = $this->processService->getStatusListByProcessTypeId($processTypeId);
        return response()->json(['responseCode' => 1, 'data' => $statusList]);
    }

}
