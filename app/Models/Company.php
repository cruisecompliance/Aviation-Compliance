<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->latest();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function flows()
    {
        return $this->hasMany(Flow::class);
    }

}
