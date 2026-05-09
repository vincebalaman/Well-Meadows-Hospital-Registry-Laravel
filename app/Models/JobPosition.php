<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosition extends Model
{
    protected $table = 'job_positions';
    protected $primaryKey = 'position_id';
    public $timestamps = false;

    protected $fillable = [
        'position_name',
        'salary_scale',
        'pay_type',
        'current_salary',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(StaffContract::class, 'position_id', 'position_id');
    }
}