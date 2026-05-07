<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalDoctor extends Model 
{
    protected $table = 'local_doctors'; 

    protected $primaryKey = 'clinic_no';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}