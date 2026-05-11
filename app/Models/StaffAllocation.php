<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAllocation extends Model
{
    protected $table = 'staff_allocation';
    protected $primaryKey = 'allocation_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'staff_no',
        'ward_id',
        'week_beginning',
        'shift_type',
    ];

    protected $casts = [
        'week_beginning' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }
}
