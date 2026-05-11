<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bed extends Model
{
    protected $table = 'beds';
    protected $primaryKey = 'bed_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ward_id',
        'bed_number',
        'status',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }

    public function stays(): HasMany
    {
        return $this->hasMany(InPatientStays::class, 'bed_no', 'bed_id');
    }
}
