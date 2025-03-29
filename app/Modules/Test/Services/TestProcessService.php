<?php

namespace App\Modules\Test\Services;

use App\Core\ProcessEngine\Handlers\ProcessCallbackInterface;

class TestProcessService implements ProcessCallbackInterface {

    /**
     * When application will process this method will be executed every time.
     * @param $processTypeId
     * @param $statusId
     * @param $deskId
     * @param $request
     * @return string
     */
    public function processCallback($processTypeId, $statusId, $deskId, $request):bool
    {
        switch ($statusId) {
            case 1:
                //TODO:: execute operations
                break;
        }
        return true;
    }

}

        