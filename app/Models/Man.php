<?php

namespace App\Models;

use App\Models\RapidxUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Man extends Model
{
    protected $table = 'man';

    /**
     * Get the DropdownDetail associated with the EcrDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rapidx_user($column)
    {
        return $this->hasOne(RapidxUser::class, 'id', $column);
    }
    public function rapidx_user_qc_inspector_operator()
    {
       return $this->rapidx_user('qc_inspector_operator');
    }
}
