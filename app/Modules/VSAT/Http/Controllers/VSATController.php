<?php

namespace App\Modules\VSAT\Http\Controllers;

use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VSATController extends ApplicationFormHandler
{
    /**
     * This method will be used by the framework. This will render form
     * @param Request $request
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function create(): string
    {
         return (string) view('VSAT::form');
    }

    /**
     * This method will be used by the framework. This will render form edit page
     * @param Request $request
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function edit($processTypeId, $applicationId): string
    {
        return (string) view('VSAT::form');
    }

    /**
     * This method will be used by the framework. This will render view page
     * @param Request $request
     * @param $processTypeId
     * @param $applicationId
     * @return string
     */
    public function view($processTypeId, $applicationId): string
    {
        return (string) view('VSAT::form');
    }
}
        