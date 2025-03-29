<?php

namespace App\Modules\ISP\Http\Controllers;

use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use App\Core\ProcessEngine\Models\ProcessList;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Models\ShareHolder;
use App\Modules\ISP\Models\IspLicenseMaster;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ISPController extends ApplicationFormHandler
{
    public function create(): string
    {
        return view('ISP::create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $ispLicenseMaster = new IspLicenseMaster();
            $processList = new ProcessList();

            $ispLicenseMaster->company_id = 0;
            $ispLicenseMaster->tracking_no = "ISP-" . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $ispLicenseMaster->status = 11;
            $ispLicenseMaster->save();

            $shareHolders = [];
            $shareHolders[] = [
                'app_id' => $ispLicenseMaster->id,
                'process_type_id' => 1,
                'name' => $request->get('shareholder_name'),
                'nationality' => $request->get('shareholder_nationality'),
                'nid' => $request->get('shareholder_nid'),
                'passport' => $request->get('shareholder_passport'),
                'dob' => !empty($request->shareholder_dob) ? date('Y-m-d', strtotime($request->shareholder_dob)) : null,
                'designation' => $request->get('shareholder_designation'),
                'mobile' => $request->get('shareholder_mobile'),
                'email' => $request->get('shareholder_email'),
                'image' => null,
                'no_of_share' => $request->get('no_of_share'),
                'share_value' => $request->get('share_value'),
                'share_percent' => $request->get('shareholder_share_of', '0.0'),
                'created_at' => now(),
                'created_by' => auth()->id(),
            ];
            ShareHolder::insert($shareHolders);

            $processList->process_type_id = 1;
            $processList->org_id = 0;
            $processList->process_user_desk_id = 1;
            $processList->ref_id = $ispLicenseMaster->id;
            $processList->tracking_no = $ispLicenseMaster->tracking_no;
            $processList->json_object = json_encode([
                "Applicant Name" => $request->shareholder_name,
                "Email" => $request->shareholder_email,
                "Phone" => $request->shareholder_mobile
            ]);
            $processList->process_status_id = -1;
            $processList->user_id = 0;
            $processList->read_status = 0;
            $processList->previous_hash = 0;
            $processList->hash_value = 0;
            $processList->remarks = 0;
            $processList->save();
            DB::commit();
            return redirect()->route('application.list', Encryption::encodeId(1))->with('success', 'Successfully Application Submitted !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('application.create', Encryption::encodeId(1))->with('error', $e->getMessage());
        }


    }

    public function view($processTypeId, $applicationId): string
    {
        $ispLicenseData = IspLicenseMaster::query()
            ->where('id', $applicationId)
            ->with('shareHolder')
            ->first();
        return view('ISP::view',compact('ispLicenseData'));
    }

    /**
     * @param Request $request
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function edit($processTypeId, $applicationId): string
    {
        $ispLicenseData = IspLicenseMaster::query()
            ->where('id', $applicationId)
            ->with('shareHolder')
            ->first();

        return view('ISP::edit', compact('ispLicenseData'));
    }

    public function update(Request $request, $applicationId)
    {
        try {
            DB::beginTransaction();
            $decodeId = Encryption::decodeId($applicationId);
            $processList = ProcessList::query()->whereRefId($decodeId)->first();
            $processList->update([
                $processList->json_object = json_encode([
                    "Applicant Name" => $request->get('shareholder_name'),
                    "Email" => $request->get('shareholder_email'),
                    "Phone" => $request->get('shareholder_mobile'),
                ]),
                'process_status_id' => 1,
                'updated_at' => now(),
                'updated_by' => auth()->id(),
            ]);

            $ispLicenseData = IspLicenseMaster::query()->find($decodeId);

            $ispLicenseData->update([
                'updated_at' => now(),
                'updated_by' => auth()->id(),
            ]);

            $shareHolder = ShareHolder::query()->whereAppId($decodeId)->first();
            $shareHolder->name = $request->get('shareholder_name');
            $shareHolder->nationality = $request->get('shareholder_nationality');
            $shareHolder->nid = $request->get('shareholder_nid');
            $shareHolder->passport = $request->get('shareholder_passport');
            $shareHolder->dob = $request->get('shareholder_dob');
            $shareHolder->designation = $request->get('shareholder_designation');
            $shareHolder->mobile = $request->get('shareholder_mobile');
            $shareHolder->email = $request->get('shareholder_email');
            $shareHolder->image = null;
            $shareHolder->no_of_share = $request->get('no_of_share');
            $shareHolder->share_value = $request->get('share_value');
            $shareHolder->share_percent = $request->get('shareholder_share_of');
            $shareHolder->updated_by = auth()->id();
            $shareHolder->update();
            DB::commit();
            return redirect()->route('application.list', Encryption::encodeId(1))->with('success', 'Successfully Application Updated !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('application.edit', [Encryption::encodeId(1), $applicationId])->with('error', $e->getMessage());
        }
    }

}
