<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplies extends Model
{
    protected $table = 'supplies';
    protected $primaryKey = 'item_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_no',
        'name',
        'description',
        'qty_in_stock',
        'reorder_level',
        'cost_per_unit',
        'supplier_no',
    ];

    public function pharmaceutical(): HasOne
    {
        return $this->hasOne(Pharmaceuticals::class, 'drug_no', 'item_no');
    }
}
