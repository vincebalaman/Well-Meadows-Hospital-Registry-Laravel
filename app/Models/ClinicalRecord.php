<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalRecord extends Model
{
    protected $table = 'clinical_records';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $fillable = [
        'app_no',
        'diagnosis',
        'treatment_plan',
        'outcome',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'app_no', 'app_no');
    }
}
