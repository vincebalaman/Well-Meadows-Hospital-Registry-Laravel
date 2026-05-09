<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffContract extends Model
{
    protected $table = 'staff_contracts';
    protected $primaryKey = 'contract_no'; // Or whatever your PK is in SQL
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'contract_no',
        'staff_no',
        'position_id',
        'start_date',
        'finish_date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class, 'position_id', 'position_id');
    }
}