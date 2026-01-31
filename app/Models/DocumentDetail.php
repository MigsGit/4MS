<?php

namespace App\Models;

use App\Models\RapidxUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentDetail extends Model
{
    use HasFactory;

    /**
     * Get the user associated with the DocumentDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rapidx_user_person_in_charge()
    {
        return $this->hasOne(RapidxUser::class, 'id', 'person_in_charge');
    }
}
