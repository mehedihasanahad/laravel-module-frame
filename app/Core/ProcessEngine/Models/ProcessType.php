<?php

namespace App\Core\ProcessEngine\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class ProcessType extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'process_types';

    protected $fillable = [
        'name',
        'name_bn',
        'group_name',
        'app_table_name',
        'module_folder_name',
        'active_for_permissions',
        'panel_color',
        'icon_class',
        'order',
        'status',
        'final_status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function getPermissions($processTypeId): array
    {
        $permissionNames = self::find($processTypeId)->value('active_for_permissions');
        return explode("|",$permissionNames);
    }

}
