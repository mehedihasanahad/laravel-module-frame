<?php

namespace App\Core\Document\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessType extends Model
{
    use HasFactory;
    protected $table = 'process_types';
    protected $fillable = array(
        'id',
        'name',
        'name_bn',
        'group_name',
        'app_table_name',
        'module_folder_name',
        'active_for_permissions',
        'status',
        'final_status',
        'panel_color',
        'icon_class',
        'order',
        'created_at',
        'updated_at'
    );


}
