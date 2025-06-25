<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /**
     * Get the user associated with the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ecr()
    {
        return $this->hasOne(Ecr::class, 'id', 'ecrs_id')->whereNull('deleted_at');
    }
}
