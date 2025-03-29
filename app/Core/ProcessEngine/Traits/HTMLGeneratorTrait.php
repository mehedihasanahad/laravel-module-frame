<?php

namespace App\Core\ProcessEngine\Traits;

use App\Core\ProcessEngine\ProcessHelper;
use App\Libraries\Encryption;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HTMLGeneratorTrait
{
    /**
     * generate html for process list
     * @param object $list process list data
     * @return string
     */
    function generateActionHtml(object $list, $class): string
    {
        $html = '';
        if ($list->locked_by > 0 && Carbon::createFromFormat('Y-m-d H:i:s', $list->locked_at)->diffInMinutes() < 3 && $list->locked_by != Auth::user()->id) {
            $lockedByUser = User::where('id', $list->locked_by)
                ->select('id', 'first_name', 'last_name')
                ->value('name');

            $html .= '<img width="20" src="' . url('/assets/images/Lock-icon_2.png') . '"/>' .
                '<a onclick="return confirm(' . "'The record locked by $lockedByUser, would you like to force unlock?'" . ')"
        target="_blank" href="' . route('application.view', [Encryption::encodeId($list->ref_id), Encryption::encodeId($list->process_type_id)]) . '"
        class="btn btn-xs btn-primary"> Open</a> &nbsp;';
        } else {
            if (in_array($list->process_status_id, [-1, 5]) && $list->created_by == Auth::user()->id) {
                $html .= '<a class="subSectorEditBtn btn btn-xs btn-info"  href="' . route('application.edit', [Encryption::encodeId($list->process_type_id), Encryption::encodeId($list->ref_id),Encryption::encodeId(2)]) . '" class="btn btn-xs btn-success button-color ' . $class['button_class'] . ' " style="color: white"> <i class="fa fa-edit"></i> Edit</a><br>';
            } else {
                $html .= '<a class="subSectorEditBtn btn btn-xs btn-info"  href="' . route('application.view', [Encryption::encodeId($list->process_type_id), Encryption::encodeId($list->ref_id),Encryption::encodeId(1)]) . '" class="btn btn-xs btn-primary button-color ' . $class['button_class'] . ' " style="color: white"> <i class="fa fa-folder-open"></i> Open</a><br>';
            }

        }

        $html .= '<input type="hidden" class="' . $class['input_class'] . '" name="batch_input"  value=' . Encryption::encodeId($list->id) . '>';
        return $html;
    }

    /**
     * generate html for process list
     * @param object $list process list data
     * @return string
     */
    function generateFavoriteListHtml(object $list): string
    {
        $existingFavoriteItem = 0;
        $html = '';
        if ($existingFavoriteItem > 0) {
            $html .= '<i style="cursor: pointer;color:#f0ad4e" class="fas fa-star remove_favorite_process" title="Added to your favorite list. Click to remove." id=' . Encryption::encodeId($list->id) . '></i> ' . $list->tracking_no;
        } else {
            $html .= '<i style="cursor: pointer" class="far fa-star favorite_process"  title="Add to your favorite list" id=' . Encryption::encodeId($list->id) . '></i> ' . $list->tracking_no;
        }
        return $html;
    }

    /**
     * generate html attribute for process list
     * @param object $list process list data
     * @return array
     */
    function generateRowAttributeInHtml(object $list): array
    {
        $userDeskIds = ProcessHelper::getUserDeskIds();
        $userId = Auth::id();
        return [
            'style' => function ($list) {
                $color = '';
                if ($list->priority == 1) {
                    $color .= '';
                } elseif ($list->priority == 2) {
                    $color .= '    background: -webkit-linear-gradient(left, rgba(220,251,199,1) 0%, rgba(220,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                } elseif ($list->priority == 3) {
                    $color .= '    background: -webkit-linear-gradient(left, rgba(255,251,199,1) 0%, rgba(255,251,199,1) 40%, rgba(255,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                }
                return $color;
            },
            'class' => function ($list) use ($userDeskIds, $userId) {
                if (!in_array($list->status_id, [-1, 5, 6, 25]) && $list->read_status == 0 && in_array($list->desk_id, $userDeskIds)) {
                    return 'unreadMessage';
                } elseif (in_array($list->status_id, [5, 6, 25]) && $list->read_status == 0 && $list->created_by == $userId) {
                    return 'unreadMessage';
                }
            }
        ];
    }
}
