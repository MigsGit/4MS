<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManDetail extends Model
{
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
    public function rapidx_user_trainer()
    {
       return $this->rapidx_user('trainer');
    }
    public function rapidx_user_lqc_supervisor()
    {
       return $this->rapidx_user('lqc_supervisor');
    }
}
