<?php

namespace App\Models;

use App\Models\Material;
use App\Models\EcrApproval;
use App\Models\Environment;
use App\Models\PmiApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ecr extends Model
{
    /**
     * Get the user associated with the EcrDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ecr_details()
    {
        return $this->hasMany(EcrDetail::class, 'ecrs_id', 'id');
    }
    public function ecr_approvals()
    {
        return $this->hasMany(EcrApproval::class, 'ecrs_id', 'id');
    }
    public function ecr_approval()
    {
        return $this->hasOne(EcrApproval::class, 'ecrs_id', 'id');
    }
    public function ecr_approval_pending()
    {
        return $this->hasOne(EcrApproval::class, 'ecrs_id', 'id')->where('status','PEN');
    }
    public function pmi_approvals()
    {
        return $this->hasMany(PmiApproval::class, 'ecrs_id', 'id');
    }
    public function pmi_approvals_pending()
    {
        return $this->hasMany(PmiApproval::class, 'ecrs_id', 'id')->where('status','PEN');
    }
   
    public function environment()
    {
        return $this->hasMany(Environment::class, 'ecrs_id', 'id');
    }
    public function material()
    {
        return $this->hasMany(Material::class, 'ecrs_id', 'id');
    }
    public function machine()
    {
        return $this->hasMany(Machine::class, 'ecrs_id', 'id');
    }

}
