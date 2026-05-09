<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $table = 'job_positions';
    protected $primaryKey = 'position_id';
    public $timestamps = false;

    protected $fillable = [
        'position_name',
    ];
}
