<?php

namespace App\Models;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcrRequirement extends Model
{
    public function machine()
    {
        return $this->hasOne(Machine::class, 'ecrs_id', 'ecrs_id');
    }
}
