<?php

namespace App\Modules\Mutation\Http\Controllers;

use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MutationController extends ApplicationFormHandler
{

    public function create(): string
    {
        return (string) view("Mutation::create");
    }

    public function view($processTypeId, $applicationId): string
    {
        return (string) view("Mutation::create");
    }

    public function edit($processTypeId, $applicationId): string
    {
        // TODO: Implement edit() method.
    }
}
