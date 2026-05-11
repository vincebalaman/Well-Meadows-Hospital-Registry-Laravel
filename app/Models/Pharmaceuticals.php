<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pharmaceuticals extends Model
{
    protected $table = 'pharmaceuticals';
    protected $primaryKey = 'drug_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'drug_no',
        'dosage',
        'method_admin',
    ];

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supplies::class, 'drug_no', 'item_no');
    }
}
