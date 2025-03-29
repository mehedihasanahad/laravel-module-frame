<?php

namespace App\Core\ProcessEngine\Http\Controllers;

use App\Core\FormBuilder\Models\Form;
use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use App\Core\ProcessEngine\Models\ProcessList;
use App\Core\ProcessEngine\Models\ProcessStatus;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Core\ProcessEngine\ProcessHelper;
use App\Core\ProcessEngine\Services\ProcessService;
use App\Core\ProcessEngine\Traits\HTMLGeneratorTrait;
use App\Core\ProcessEngine\Traits\ProcessEligibilityCheckTrait;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;


class ApplicationController extends Controller
{
    use HTMLGeneratorTrait, ProcessEligibilityCheckTrait;

    private ProcessService $processService;

    /**
     * @throws \ReflectionException
     */
    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    /**
     * Add a new application.
     *
     * @param string $encoded_process_type_id
     * @return View|Factory|Application|RedirectResponse
     */
    public function create(string $encoded_process_type_id, ApplicationFormHandler $moduleInstance): View|Factory|Application|RedirectResponse
    {
        try {
            $processTypeId = Encryption::decodeId($encoded_process_type_id);

            # Get process type information by process type ID
            $processType = ProcessType::find($processTypeId);
            if (empty($processType)) {
                Session::flash('error', 'Invalid process type information [AC-90]');
                return redirect()->back();
            }

            # load add blade file as a string
            $moduleBladeForm = $moduleInstance->create();

            $hasDeskOfficeWisePermission = false;
            # dynamic form builder data
            $processTypeId = Route::getFacadeRoot()->current()->parameters['process_type_id'];
            $formType = Route::getFacadeRoot()->current()->parameters['form_type'];
            $decodedProcessTypeId = Encryption::decodeId($processTypeId);
            $decodedFormType = Encryption::decodeId($formType);
            $form = Form::query()
                ->where([
                    'process_type_id' => $decodedProcessTypeId,
                    'form_type' => $decodedFormType
                ])
                ->first();

            return view("ProcessEngine::application.form", compact(
                'processType',
                'moduleBladeForm',
                'hasDeskOfficeWisePermission',
                'form'
            ));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic(null,$e->getMessage()) . ' [AC-72]');
            return Redirect::back();
        }
    }

    /**
     * View an application.
     *
     * @param string $encoded_app_id
     * @param string $encoded_process_type_id
     * @return View|Factory|Application|RedirectResponse
     */
    public function view(string $encoded_process_type_id, string $encoded_app_id, ApplicationFormHandler $moduleInstance): View|Factory|Application|RedirectResponse
    {
        try {
            $processTypeId = Encryption::decodeId($encoded_process_type_id);
            $applicationId = Encryption::decodeId($encoded_app_id);

            # Get process type information by process type ID
            $processType = ProcessType::find($processTypeId);
            if (empty($processType)) {
                Session::flash('error', 'Invalid process type information [AC-90]');
                return redirect()->back();
            }

            # Get process information by application ID and process type ID
            $processInfo = $this->processService->getProcessDataByApplicationId($applicationId, $processTypeId);
            if (empty($processInfo)) {
                Session::flash('error', 'Invalid process information [AC-95]');
                return redirect()->back();
            }

            # Variables for Ajax calling from the form page
            $encodedProcessListId = Encryption::encodeId($processInfo->processListId);

            $hasDeskOfficeWisePermission = $this->processService->hasDeskOfficeWisePermission($processInfo->process_user_desk_id);

            # Update process read status from the applicant user if conditions are met
            if ($hasDeskOfficeWisePermission && $this->isReadStatusUpdateEligible($processInfo)) {
                $this->processService->updateProcessReadStatus($applicationId);
            }

            $verificationData = null;
            # Check user type and perform additional actions
            if (Auth::user()->can('verification-process-data')) {
                if ($this->isUpdateEligibleForLock($processInfo)) {
                    # Update locked_by and locked_at fields in the process list
                    ProcessList::where('id', $processInfo->processListId)
                        ->update([
                            'locked_by' => Auth::user()->id,
                            'locked_at' => date('Y-m-d H:i:s')
                        ]);
                }
                # process data ready for verification
                $verificationData = (object)[
                    'id' => $processInfo->process_list_id,
                    'status_id' => $processInfo->process_status_id,
                    'desk_id' => $processInfo->process_user_desk_id,
                    'user_id' => $processInfo->user_id,
                    'tracking_no' => $processInfo->tracking_no,
                ];
            }
            # dynamic form builder data
            $formType = Route::getFacadeRoot()->current()->parameters['form_type'];
            $decodedFormType = Encryption::decodeId($formType);
            $form = Form::query()
                ->where([
                    'process_type_id' => $processTypeId,
                    'form_type' => $decodedFormType
                ])
                ->first();
            # load view form blade file as a string
            $moduleBladeForm = $moduleInstance->view($processTypeId, $applicationId);

            return view("ProcessEngine::application.form", compact(
                'processType',
                'processInfo',
                'encoded_app_id',
                'encoded_process_type_id',
                'encodedProcessListId',
                'moduleBladeForm',
                'verificationData',
                'hasDeskOfficeWisePermission',
                'form'
            ));

        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong!. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[AC-144]'.$e->getLine());
            return \redirect()->back();
        }
    }

    /**
     * Edit an application.
     *
     * @param string $encoded_app_id
     * @param string $encoded_process_type_id
     * @return Application|Factory|View
     */
    public function edit(string $encoded_process_type_id, string $encoded_app_id, ApplicationFormHandler $moduleInstance)
    {
        try {
            $process_type_id = Encryption::decodeId($encoded_process_type_id);
            $application_id = Encryption::decodeId($encoded_app_id);
            $formType = request()->route()->parameters['form_type'];
            $decodedFormType = Encryption::decodeId($formType);

            # Get process type information by process type ID
            $processType = ProcessType::find($process_type_id);
            if (empty($processType)) {
                Session::flash('error', 'Invalid application information [AC-165]');
                return \redirect()->back();
            }

            $hasDeskOfficeWisePermission = false;
            # load edit form blade file as a string
            $moduleBladeForm = $moduleInstance->edit($process_type_id, $application_id);

            $form = Form::query()
                ->where([
                    'process_type_id' => $process_type_id,
                    'form_type' => $decodedFormType
                ])
                ->first();
            return view("ProcessEngine::application.form", compact(
                'processType',
                'moduleBladeForm',
                'hasDeskOfficeWisePermission',
                'form'
            ));
        } catch (\Exception $exception) {
            Session::flash('error', 'Something went wrong! [PPC-1019]' . $exception->getMessage() .$exception->getLine());
            return \redirect()->back();
        }
    }

    /**
     * List application.
     *
     * @param string $process_type_id
     * @param ApplicationFormHandler $moduleInstance
     * @return View|Factory|Application|RedirectResponse
     */
    public function list(string $process_type_id): View|Factory|Application|RedirectResponse
    {
        try {
            # Decode the process type ID if it is not empty
            $processTypeId = ($process_type_id !== '') ? Encryption::decodeId($process_type_id) : 0;

            # Retrieve the process types based on user type
            $processTypes = ProcessType::select(['name', 'id'])
                ->whereStatus(1)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            # Retrieve the process info for the specified process type ID
            $processInfo = ProcessType::where('id', $processTypeId)->select('id', 'name', 'group_name')->first(['id', 'name', 'group_name']);

            # Retrieve the status options for the specified process type ID
            $status = ProcessStatus::query()
                ->when($processTypeId !== 0, function ($query) use ($processTypeId) {
                    $query->where('process_type_id', $processTypeId);
                }, function ($query) {
                    $query->where('process_type_id', -1);
                })
                ->select('id', 'status_name')
                ->where('id', '!=', -1)
                ->where('status', 1)
                ->orderBy('status_name')
                ->pluck('status_name', 'id')
                ->prepend('Select one', '')
                ->toArray();

            # Define the search timeline options
            $searchTimeline = [
                '' => 'Select One',
                '1' => '1 Day',
                '7' => '1 Week',
                '15' => '2 Weeks',
                '30' => '1 Month'
            ];

            # Return the view with the necessary data
            return view("ProcessEngine::application.list", compact(
                'status',
                'processTypes',
                'processInfo',
                'searchTimeline',
                'processTypeId'
            ));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something went wrong. ' . $e->getMessage() . ' [AC-314]');
            return redirect()->back();
        }
    }

    /**
     * Get list data for the applications.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function listData(Request $request): JsonResponse
    {
        $status = $request->get('status', 0);
        $desk = $request->get('desk', '');
        # Retrieve the list data from the process service
        $application_list = $this->processService->processList($request, $status, $desk);

        # Generate the class for batch update
        $class = $this->processService->batchUpdateClass($request, $desk);

        # Create the Datatables instance and configure the columns
        return Datatables::of($application_list)
            ->addColumn('action', function ($application_list) use ($status, $request, $desk, $class) {
                # Generate the action HTML for the list item
                return $this->generateActionHtml($application_list, $class);
            })
            ->editColumn('tracking_no', function ($application_list) use ($desk, $request, $class) {
                # Generate the HTML for the favorite list
                return $this->generateFavoriteListHtml($application_list);
            })
            ->editColumn('json_object', function ($application_list) {
                # Extract data from the JSON object
                return ProcessHelper::getDataFromJson($application_list->json_object);
            })
            ->editColumn('desk_name', function ($application_list) {
                # Edit the column value for 'desk_name'
                return $application_list->process_user_desk_id == 0 ? 'Applicant' : $application_list->desk_name;
            })
            ->editColumn('updated_at', function ($application_list) {
                # Format the 'updated_at' column value
                return ProcessHelper::updatedOn($application_list->updated_at);
            })
            ->rawColumns(['tracking_no', 'action'])
            ->setRowAttr($this->generateRowAttributeInHtml($application_list)) # Set row attributes using generated HTML
            ->make(true);
    }

}
