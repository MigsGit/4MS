<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialApproval extends Model
{
    protected $fillable = [
        'status',
        'remarks',
    ];
    public function rapidx_user()
    {
        return $this->hasOne(RapidxUser::class, 'id', 'rapidx_user_id');
    }
}
