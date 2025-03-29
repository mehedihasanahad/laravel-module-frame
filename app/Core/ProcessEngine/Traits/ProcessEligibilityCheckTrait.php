<?php

namespace App\Core\ProcessEngine\Traits;

use App\Core\ProcessEngine\ProcessHelper;
use App\Libraries\Encryption;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ProcessEligibilityCheckTrait
{
    /**
     * Check if the process is eligible for updating the read status.
     *
     * @param mixed $processInfo The process information object.
     * @return bool True if eligible, false otherwise.
     */
    public function isReadStatusUpdateEligible(mixed $processInfo): bool
    {
        $processStatusIdsToUpdateReadStatus = [5, 6, 25];
        return $processInfo->createdBy == Auth::user()->id && in_array($processInfo->statusId, $processStatusIdsToUpdateReadStatus) && $processInfo->readStatus == 0;
    }

    /**
     * Check if the process is eligible for updating the lock.
     *
     * @param mixed $processInfo The process information object.
     * @return bool True if eligible, false otherwise.
     */
    public function isUpdateEligibleForLock(mixed $processInfo): bool
    {
        $userDeskIDs = ProcessHelper::getUserDeskIds();
        return (Auth::user()->desk_id != 0 && in_array($processInfo->process_user_desk_id, $userDeskIDs)) || (in_array($processInfo->process_user_desk_id, $this->processService->getDelegateUsers()));
    }
}
