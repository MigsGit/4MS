<?php

namespace App\Models;

use App\Models\RapidxUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PmiApproval extends Model
{
    public function rapidx_user()
    {
        return $this->hasOne(RapidxUser::class, 'id', 'rapidx_user_id');
    }
}
