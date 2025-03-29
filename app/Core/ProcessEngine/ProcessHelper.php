<?php

namespace App\Core\ProcessEngine;

use App\Core\User\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProcessHelper
{
    public static function getUserDeskIds(): array
    {
        if (Auth::user()) {
            $deskIds = Auth::user()->desk_id;
            return explode(',', $deskIds);
        } else {
            return [];
        }
    }

    public static function getOrganizationId(): int
    {
        if (Auth::user()) {
            return  Auth::user()->org_id;
        } else {
            return 0;
        }
    }

    public static function getUserType()
    {
        return Auth::user()->user_type ?? '';
    }

    public static function getUserOfficeIds(): array
    {
        if (Auth::user()) {
            $officeIds = Auth::user()->office_ids;
            return explode(',', $officeIds);
        } else {
            return [];
        }
    }


    public static function getDelegatedUserDeskIds(): array
    {
        $userId = self::getUserId();
        $delegated_usersArr = User::where('delegate_to_user_id', $userId)->get(['id','desk_id']);
        $delegatedDeskOfficeIds = [];
        foreach ($delegated_usersArr as $user) {
            $delegatedDeskOfficeIds[$user->id] = [
                'user_id' =>$user->id,
                'desk_ids' =>explode(',', $user->desk_id),
            ];
        }
        return $delegatedDeskOfficeIds;
    }

    public static function getUserId(): int
    {
        if (Auth::user()) {
            return Auth::user()->id;
        } else {
            return 0;
        }
    }

    public static function getSelfAndDelegatedUserDeskOfficeIds(): array
    {

        $userId = self::getUserId();
        $delegated_usersArr = User::where('delegate_to_user_id', $userId)
            ->orWhere('id', $userId)
            ->get([
                'id as user_id',
                'desk_id',
                'office_ids'
            ]);

        $delegatedDeskOfficeIds = array();
        foreach ($delegated_usersArr as $value) {
            $userDesk = explode(',', $value->desk_id);
            $userOffice = explode(',', $value->office_ids);
            $tempArr = array();
            $tempArr['user_id'] = $value->user_id;
            $tempArr['desk_ids'] = $userDesk;
            $tempArr['office_ids'] = $userOffice;
            $delegatedDeskOfficeIds[$value->user_id] = $tempArr;
        }

        return $delegatedDeskOfficeIds;
    }

    public static function getDataFromJson($json): string
    {
        $jsonDecoded = json_decode($json);
        $string = '';
        foreach ($jsonDecoded as $key => $data) {
            $string .= $key . ": " . $data . ', ';
        }
        return $string;
    }

    public static function updatedOn($updated_at = ''): string
    {
        $update_was = '';
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }
        return $update_was;
    }

    public static function generateVerificationString($applicationInfo): string
    {
        return '#ID' . $applicationInfo->id . '#S' . $applicationInfo->status_id . '#D' . $applicationInfo->desk_id .
            '#U' . $applicationInfo->user_id . '#T' . $applicationInfo->tracking_no;
    }

}
