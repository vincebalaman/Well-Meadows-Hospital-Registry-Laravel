<?php

namespace App\Models;

use App\Models\InPatientStays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientBilling extends Model
{
    protected $table = 'patient_billing';
    protected $primaryKey = 'bill_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'bill_id';
    }

    protected $fillable = [
        'stay_id',
        'total_amount',
        'amount_paid',
        'payment_status',
    ];

    public function stay(): BelongsTo
    {
        return $this->belongsTo(InPatientStays::class, 'stay_id', 'stay_id');
    }
}
