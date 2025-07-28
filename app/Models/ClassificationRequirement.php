<?php

namespace App\Models;

use App\Models\Classification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassificationRequirement extends Model
{
    public function classification()
    {
        return $this->hasOne(Classification::class, 'id', 'classifications_id');
    }

}
