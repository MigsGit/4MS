<?php

namespace App\Models;

use App\Models\EcrApproval;
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
}
