<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InPatientStay extends Model
{
    protected $table = 'in_patient_stays';
    protected $primaryKey = 'stay_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_no', 'ward_id', 'bed_no',
        'date_placed_waiting', 'expected_duration', 'date_placed_ward',
        'expected_leave', 'actual_leave', 'status',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_no', 'patient_no');
    }
}
