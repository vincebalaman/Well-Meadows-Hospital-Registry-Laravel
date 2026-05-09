<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    // PostgreSQL table name
    protected $table = 'wards'; 

    // Primary key configuration
    protected $primaryKey = 'ward_id';
    // Even though it's an INT, Laravel expects incrementing true by default. 
    // If you manually assign IDs, set $incrementing to false.
    public $incrementing = true;

    // Timestamps (Disabled as they are missing from your SQL schema)
    public $timestamps = false;

    protected $fillable = [
        'ward_id',
        'ward_name',
        'location',
        'total_beds',
        'tel_extn',
        'charge_nurse_id',
    ];

    /**
     * Relationship: A Ward is managed by a Charge Nurse (Staff).
     */
    public function chargeNurse(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'charge_nurse_id', 'staff_no');
    }

    /**
     * Relationship: A Ward contains many Beds.
     */
    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class, 'ward_id', 'ward_id');
    }
}