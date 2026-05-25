<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isStaff(): bool { return $this->role === 'staff'; }
    public function isPatient(): bool { return $this->role === 'patient'; }

    // Relationship to Patient profile
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    // Relationship to Staff profile
    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }
    
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
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
