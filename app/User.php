<?php

namespace App;

use App\Enums\RoleName;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Impersonate;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','azure_name', 'email', 'password','status','company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_DISABLED);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAuditors($query)
    {
        return $query->role(RoleName::AUDITOR);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAuditees($query)
    {
        return $query->role(RoleName::AUDITEE);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInvestigators($query)
    {
        return $query->role(RoleName::INVESTIGATOR);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAM($query)
    {
        return $query->role(RoleName::ACCOUNTABLE_MANAGER);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCMM($query)
    {
        return $query->role(RoleName::COMPLIANCE_MONITORING_MANAGER);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
