<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'staff_no',
        'patient_no'
    ];

    public function isStaff(): bool
{
    return in_array($this->role, ['admin', 'doctor', 'nurse', 'staff']);
}

public function isPatient(): bool
{
    return $this->role === 'patient';
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

// Optional: link to the matching domain models
public function staff()
{
    return $this->belongsTo(Staff::class, 'staff_no', 'staff_no');
}

public function patient()
{
    return $this->belongsTo(Patient::class, 'patient_no', 'patient_no');
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
