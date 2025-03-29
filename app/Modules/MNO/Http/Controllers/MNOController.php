<?php

namespace App\Modules\MNO\Http\Controllers;

use App\Core\Document\Models\ProcessList;
use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use App\Libraries\Encryption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MNOController extends ApplicationFormHandler
{
    /**
     * This method will be used by the framework. This will render form
     * @return string
     */
    public function create(): string
    {
        return (string) view("MNO::add");
    }

    /**
     * This method will be used by the framework. This will store & update form data
     * @param Request $request
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function store(Request $request): RedirectResponse
    {
        $app_id = Encryption::decodeId($request->app_id);
        $process_type_id = Encryption::decodeId($request->process_type_id);
        if ($process_type_id && $app_id) {
            $processList = ProcessList::firstWhere(['process_type_id' => $process_type_id, 'ref_id' => $app_id]);
            $processList->process_user_desk_id = 1;
            $processList->process_status_id = 1;
            $processList->save();
            return redirect()->route('application.list', Encryption::encodeId(5))->with('success', 'Successfully Application Updated !');
        }
        $processList = new ProcessList();
        $processList->process_type_id = 5;
        $processList->org_id = 0;
        $processList->process_user_desk_id = 1;
        $processList->ref_id = 1;
        $processList->tracking_no = '1234';
        $processList->json_object = json_encode([
            "Applicant Name" => 'Mehedi Hasan Ahad',
            "Email" => 'mehedi@gmail.com',
            "Phone" => '01822077995'
        ]);
        $processList->process_status_id = 1;
        $processList->user_id = 0;
        $processList->read_status = 0;
        $processList->previous_hash = 0;
        $processList->hash_value = 0;
        $processList->remarks = 0;
        $processList->save();

        return redirect()->route('application.list', Encryption::encodeId(5))->with('success', 'Successfully Application Submitted !');
    }

    /**
     * This method will be used by the framework. This will render form edit page
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function edit($processTypeId, $applicationId): string
    {
        return (string)view("MNO::edit");
    }

    /**
     * This method will be used by the framework. This will render view page
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function view($processTypeId, $applicationId): string
    {
        return (string)view("MNO::view");
    }
}
