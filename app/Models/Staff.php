<?php

namespace App\Models;

use App\Models\StaffAllocation;
use App\Models\StaffContract;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    // PostgreSQL table name (using lowercase to match your pgAdmin schema)
    protected $table = 'staff'; 

    // Primary key configuration
    protected $primaryKey = 'staff_no';
    protected $keyType = 'string';
    public $incrementing = false;

    // Timestamps (Set to false if your SQL doesn't have created_at/updated_at)
    public $timestamps = false;

    protected $fillable = [
        'staff_no',
        'first_name',
        'last_name',
        'address',
        'tel_no',
        'dob',
        'sex',
        'nin',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function contract()
    {
        return $this->hasOne(StaffContract::class, 'staff_no', 'staff_no');
    }

    public function allocations()
    {
        return $this->hasMany(StaffAllocation::class, 'staff_no', 'staff_no');
    }
}
