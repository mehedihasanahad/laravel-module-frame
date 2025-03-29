<?php

namespace App\Core\ProcessEngine\Handlers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class ApplicationFormHandler extends Controller
{
    protected string $processTypeName;
    protected int $processTypeId;
    protected string $apiUrl;
    public function __construct($processInfo = null)
    {
        $this->processTypeId = $processInfo->process_type_id ?? 0;
        $this->processTypeName = $processInfo->name ?? '';
        $this->apiUrl = env('API_URL', '');
    }
    public abstract function create(): string;
    public abstract function view($processTypeId, $applicationId): string;
    public abstract function edit($processTypeId, $applicationId): string;

}
