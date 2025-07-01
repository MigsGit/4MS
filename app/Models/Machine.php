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

}
