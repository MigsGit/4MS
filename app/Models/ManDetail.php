<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManDetail extends Model
{
    protected $fillable = [
        'approval_status',
        'status',
    ];
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ecr()
    {
        return $this->hasOne(Ecr::class, 'id', 'ecrs_id')->whereNull('deleted_at');
    }
    public function man_approvals()
    {
        return $this->hasMany(ManApproval::class, 'ecrs_id', 'ecrs_id')->whereNull('deleted_at');
    }
    public function man_approvals_pending()
    {
        return $this->hasMany(ManApproval::class, 'ecrs_id', 'ecrs_id')->where('status','PEN')->whereNull('deleted_at');
    }
}
