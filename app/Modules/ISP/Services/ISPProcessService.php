<?php

namespace App\Modules\ISP\Services;


use App\Core\ProcessEngine\Handlers\ProcessCallbackInterface;

class ISPProcessService implements ProcessCallbackInterface {

    public function processCallback($processTypeId, $statusId, $deskId, $request):bool
    {
        switch ($statusId) {
            case 2 :
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                }
        }
        return true;
    }

}
