<?php

namespace App\Models;

use App\Models\RapidxUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcrApproval extends Model
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
