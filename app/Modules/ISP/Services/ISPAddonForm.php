<?php

namespace App\Modules\ISP\Services;


use App\Core\ProcessEngine\Handlers\AddonFormHandler;
use App\Core\ProcessEngine\Models\ProcessList;


class ISPAddonForm extends AddonFormHandler
{
    public function prepareData(): array
    {
        $data = [];
        switch ($this->statusId) {
            case 2 :
                $data['appInfo'] = ProcessList::where('ref_id', $this->applicationId)->first();
                break;
            case 3 :
                //TODO:: handle request
                break;
        }

        return $data;
    }
}
