<?php

namespace App\Core\ProcessEngine\Handlers;

use App\Core\ProcessEngine\Requests\ProcessRequest;

interface ProcessCallbackInterface
{
    /**
     * Process the operation based on the process type, status ID, desk ID, and request data.
     *
     * @param int $processTypeId The process type ID.
     * @param int $statusId The status ID.
     * @param int $deskId The desk ID.
     * @param mixed $request The request data.
     * @return bool Returns true on successful processing.
     */
    public function processCallback(int $processTypeId,int $statusId, int $deskId,ProcessRequest $request):bool;
}
