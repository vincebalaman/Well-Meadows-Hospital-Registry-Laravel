<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAllocation extends Model
{
    protected $table = 'staff_allocations';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'staff_no',
        'ward_id',
        'week_beginning',
        'shift_type',
    ];

    protected $casts = [
        'week_beginning' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }
}
