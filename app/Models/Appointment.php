<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'app_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'patient_no',
        'consultant_staff_no',
        'app_date_time',
        'exam_room',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_no', 'patient_no');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'consultant_staff_no', 'staff_no');
    }

    public function clinicalRecord(): HasOne
    {
        return $this->hasOne(ClinicalRecord::class, 'app_no', 'app_no');
    }
}
