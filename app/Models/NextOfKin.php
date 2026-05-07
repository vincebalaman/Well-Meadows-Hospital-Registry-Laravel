<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    protected $table = 'next_of_kin';
    protected $primaryKey = 'nok_id'; // This is your SERIAL primary key
    public $timestamps = false;

    protected $fillable = ['patient_no', 'full_name', 'relationship', 'address', 'tel_no'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_no', 'patient_no');
    }
}
