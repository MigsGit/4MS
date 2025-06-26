<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
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

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function material_approvals()
    {
        return $this->hasMany(MaterialApproval::class, 'materials_id', 'id')->whereNull('deleted_at');
    }
    public function material_approvals_pending()
    {
        return $this->hasMany(MaterialApproval::class, 'materials_id', 'id')->where('status','PEN')->whereNull('deleted_at');
    }

}
