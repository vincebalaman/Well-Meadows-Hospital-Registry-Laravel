<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // Define the custom primary key from your SQL query
    protected $table = 'patients'; // Match your lowercase pgAdmin names
    protected $primaryKey = 'patient_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'patient_no', 'first_name', 'last_name', 'address', 
        'tel_no', 'dob', 'sex', 'marital_status', 'date_registered', 'clinic_no'
    ];

    public function doctor()
    {
        // belongsTo(RelatedModel, foreign_key_on_this_table, owner_key_on_other_table)
        return $this->belongsTo(LocalDoctor::class, 'clinic_no', 'clinic_no');
    }

    // Relationship to Next_of_Kin
    public function nextOfKin()
    {
        // hasOne(RelatedModel, foreign_key_on_other_table, local_key_on_this_table)
        return $this->hasOne(NextOfKin::class, 'patient_no', 'patient_no');
    }

    public function user()
    {
        return $table->belongsTo(User::class, 'user_id');
    }
}

