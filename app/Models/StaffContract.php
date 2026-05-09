<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffContract extends Model
{
    protected $table = 'staff_contracts';
    protected $primaryKey = 'staff_no';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staff_no',
        'position_id',
        'contract_type',
        'hours_per_week',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
    }

    public function position()
    {
        return $this->belongsTo(JobPosition::class, 'position_id', 'position_id');
    }
}
