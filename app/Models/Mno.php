<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mno extends Model
{


    public function contactPerson(): HasMany
    {
        return $this->hasMany(ContactPerson::class, 'app_id', 'id');
    }
    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class, 'app_id', 'id');
    }
    public function shareHolder(): HasMany
    {
        return $this->hasMany(ShareHolder::class, 'app_id', 'id');
    }
}
