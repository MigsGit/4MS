<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecr extends Model
{
    protected $table = 'ecr_details';
    protected $fillable = [
        'ecrs_id' =>  1,
        'description_of_change',
        'reason_of_change',
        'created_at',
    ];
}
