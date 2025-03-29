<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationsLog extends Model {
    protected $table = 'notifications_log';
    protected $fillable = array(
            'id',
            'mobile',
            'message',
            'type',
            'source_project',
            'response',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by'
    );
}
