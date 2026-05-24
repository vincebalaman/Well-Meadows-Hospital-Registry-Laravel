<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // A staff member might have multiple contracts over time (history)
        return $this->hasMany(StaffContract::class, 'staff_no', 'staff_no');
    }

    public function user(): BelongsTo
    {
        // Ensure you are using strings inside relationships, NOT variables!
        return $this->belongsTo(User::class, 'user_id');
    }
}
