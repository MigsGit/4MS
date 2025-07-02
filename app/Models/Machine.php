<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'status',
        'approval_status',
        'ecrs_id',
        'original_filename',
        'filtered_document_name',
        'file_path',
    ];
    public function machine_approvals()
    {
        return $this->hasMany(MachineApproval::class, 'machines_id', 'id')->whereNull('deleted_at');
    }
    public function machine_approvals_pending()
    {
        return $this->hasMany(MachineApproval::class, 'machines_id', 'id')->where('status','PEN')->whereNull('deleted_at');
    }

}
