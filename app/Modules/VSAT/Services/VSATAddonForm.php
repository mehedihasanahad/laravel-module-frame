<?php

namespace App\Modules\VSAT\Services;

use App\Core\ProcessEngine\Handlers\AddonFormHandler;


class VSATAddonForm extends AddonFormHandler
{
    /**
     * prepareData addon form data. This will automatically available in addon blade
     * @return array
     */
    public function prepareData(): array
    {
        $data = [];
        switch ($this->statusId) {
            case 1 :
                //TODO:: prepare data
                break;
        }
        return $data;
    }
}

        