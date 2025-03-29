<?php

namespace App\Modules\ISP\Models;

use App\Models\ShareHolder;
use App\Traits\CreatedByUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IspLicenseMaster extends Model
{
    use CreatedByUpdatedBy;

    protected $table = 'isp_license_master';

    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'tracking_no',
        'status',
        'updated_at',
        'updated_by',
    ];

    public function shareHolder(): HasOne
    {
        return $this->hasOne(ShareHolder::class, 'app_id', 'id');
    }
}
