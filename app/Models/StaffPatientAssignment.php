<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPatientAssignment extends Model
{

    protected $table = 'staff_patient_assignment';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_no',
        'stay_id',
        'role_description',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
    }

    public function stay(): BelongsTo
    {
        return $this->belongsTo(InPatientStay::class, 'stay_id', 'stay_id');
    }
}