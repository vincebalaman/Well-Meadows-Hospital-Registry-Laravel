<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'wards';
    protected $primaryKey = 'ward_id';
    public $timestamps = false;

    protected $fillable = [
        'ward_name',
        'charge_nurse_id',
    ];

    public function chargeNurse()
    {
        return $this->belongsTo(Staff::class, 'charge_nurse_id', 'staff_no');
    }

    public function allocations()
    {
        return $this->hasMany(StaffAllocation::class, 'ward_id', 'ward_id');
    }
}
